<?php

namespace App\Http\Services;

use App\Events\TransferEvent;
use App\Models\DepartmentItem;
use App\Models\Department;
use App\Models\GoldLoss;
use App\Models\GoldTransform;
use App\Models\GoldTransformUsedItem;
use App\Models\Items;
use App\Models\OpeningBalance;
use App\Models\Transfer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GoldTransformService
{
    function prepareUsedItems(array $usedItems, array $usedWeights): array
    {
        $usedItemsData = [];
        for ($i = 0; $i < count($usedItems); $i++) {
            array_push($usedItemsData, [
                'department_item_id' => $usedItems[$i],
                'weight' => $usedWeights[$i]
            ]);
        }
        return $usedItemsData;
    }

    function prepareNewItems(array $newItems, array $newWeights, array $newItemsShares, ?array $quantity, ?array $stone_weight): array
    {
        $newItemsData = [];
        for ($i = 0; $i < count($newItems); $i++) {
            array_push($newItemsData, [
                'item_id'  => $newItems[$i],
                'actual_shares' => $newItemsShares[$i],
                'weight' => $newWeights[$i],
                'quantity' => $quantity[$i],
                'stone_weight' => $stone_weight[$i],
            ]);
        }
        return $newItemsData;
    }

    /**
     * calculate gold transform loss,
     * return gold transform loss in 21
     */
    public function getGoldLoss(array $usedItems, array $usedWeights, array $newWeights, array $newGoldItemsShares): ?float
    {
        $departmentUsedItems = DepartmentItem::find($usedItems);
        $totalUsedGoldWeight = 0;
        foreach ($usedItems as $key => $usedItemid) {
            $totalUsedGoldWeight += ($departmentUsedItems->where('id', $usedItemid)->first()?->shares * $usedWeights[$key]);
        }

        $totalNewGoldWeight = 0;
        for ($i = 0; $i < count($newWeights); $i++) {
            $totalNewGoldWeight += ($newGoldItemsShares[$i] * $newWeights[$i]);
        }

        $diff  = $totalUsedGoldWeight - $totalNewGoldWeight;
        return $diff / 875 > 0.01 ?  $diff / 875 : null;
    }

    public function saveGoldTransform(
        string $date,
        ?int $worker_id,
        int $department_id,

    ): GoldTransform {
        $goldTransform = GoldTransform::create([
            'date' => $date,
            'worker_id' => $worker_id,
            'department_id' => $department_id,
        ]);

        return $goldTransform;
    }

    public function saveGoldTransformUsedItems(GoldTransform $goldTransform, array $usedItemsData): Collection
    {
        return $goldTransform->usedItems()->createMany($usedItemsData);
    }

    public function saveGoldTransformNewItems(GoldTransform $goldTransform, array $newItemsData): Collection
    {
        return $goldTransform->newItems()->createMany($newItemsData);
    }

    public function delete(GoldTransform $goldTransform)
    {
        try {
            DB::beginTransaction();
            $this->deleteGoldTransformItemsWeights($goldTransform);
            $goldTransform->usedItems()->delete();
            $goldTransform->newItems()->delete();
            $goldTransform->goldLoss()->delete();
            $goldTransform->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function createGoldTransform($request): ?GoldTransform
    {
        $usedItemsData = $this->prepareUsedItems(
            $request->used_item_id,
            $request->weight_to_use,
        );

        $newItemsData = $this->prepareNewItems(
            $request->new_item_id,
            $request->new_item_weight,
            $request->new_item_shares,
            $request->new_item_qty,
            $request->new_item_stone_weight,
        );

        $goldLoss = $this->getGoldLoss(
            $request->used_item_id,
            $request->weight_to_use,
            $request->new_item_weight,
            $request->new_item_shares,
        );

        try {
            DB::beginTransaction();
            $tranferToDepartment = Department::find($request->transfer_to_department_id);
            $tranferFromDepartment = Department::find($request->department_id);
            $goldTransform =  $this->saveGoldTransform($request->date, $request->worker_id, $request->department_id);
            $this->saveGoldTransformNewItems($goldTransform, $newItemsData);
            $this->saveGoldTransformUsedItems($goldTransform, $usedItemsData);
            $usedDepartmentItems = $this->removeUsedItemsWeightsFromDepartment(
                $request->used_item_id,
                $request->weight_to_use,
            );

            for ($i = 0; $i < count($usedDepartmentItems['usedDepartmentItems']); $i++) {
                (new DailyReportService())->addNewDailyReport(
                    $tranferFromDepartment, 
                    $usedDepartmentItems['usedDepartmentItems'][$i], 
                    $usedDepartmentItems['usedDepartmentItemsWeights'][$i], 
                    'credit'
                );
            }

            
            
            $newDepartmentItems = $this->addNewItemsInDepartment(
                $request->new_item_id,
                $request->new_item_weight,
                $request->new_item_shares,
                $request->department_id
            );

            for ($i = 0; $i < count($newDepartmentItems['newDepartmentItems']); $i++) {
                (new DailyReportService())->addNewDailyReport(
                    $tranferFromDepartment, 
                    $newDepartmentItems['newDepartmentItems'][$i], 
                    $newDepartmentItems['newDepartmentItemsWeights'][$i], 
                    'debit'
                );
            }

            if ($goldLoss) {
                $goldTransform->load(['usedItems.departmentItem']);
                $goldTransform->goldLoss()->create([
                    'department_id' => $request->department_id,
                    'loss_weight_in_21' => $goldLoss,
                    'total_used_gold_in_21' => $goldTransform->usedItems->reduce(
                        fn (?int $shares, GoldTransformUsedItem $goldTransformUsedItem) => $goldTransformUsedItem->weight * ($goldTransformUsedItem->departmentItem->shares / $shares),
                        875
                    ),
                    'worker_id' => $request->worker,
                    'date' => $request->date,
                ]);
            }

            if ($request->transfer_to_department_id) {
                // $this->addNewItemsInDepartment(
                //     $request->new_item_id,
                //     $request->new_item_weight,
                //     $request->new_item_shares,
                //     $request->transfer_to_department_id
                // );
                $this->removeUsedItemsWeightsFromDepartment(
                    collect($newDepartmentItems['newDepartmentItems'])->pluck('id')->toArray(),
                    $newDepartmentItems['newDepartmentItemsWeights'],
                );
                for ($i = 0; $i < count($newDepartmentItems['newDepartmentItems']); $i++) {
                    $transfer = Transfer::create([
                        'department_item_id' => $newDepartmentItems['newDepartmentItems'][$i]->id,
                        'date' => $request->date,
                        'person_on_charge' => auth()->user()->name_ar,
                        'transfer_from' => $tranferFromDepartment->id,
                        'transfer_to' => $tranferToDepartment->id,
                        'transfer_from_name' => $tranferFromDepartment->name,
                        'transfer_to_name' => $tranferToDepartment->name,
                        'kind' => $newDepartmentItems['newDepartmentItems'][$i]->kind,
                        'kind_name' => $newDepartmentItems['newDepartmentItems'][$i]->kind_name,
                        'shares' => $newDepartmentItems['newDepartmentItems'][$i]->shares,
                        'shares_to_transfer' => $newDepartmentItems['newDepartmentItems'][$i]->shares,
                        'weight_to_transfer' => $newDepartmentItems['newDepartmentItemsWeights'][$i],
                        'karat' => $newDepartmentItems['newDepartmentItems'][$i]->karat,
                        'item_weight_before_transfer' => $newDepartmentItems['newDepartmentItems'][$i]->current_weight + $newDepartmentItems['newDepartmentItemsWeights'][$i],
                        'item_weight_after_transfer' =>  $newDepartmentItems['newDepartmentItems'][$i]->current_weight ,
                        'total_loss' => 0,
                        'total_gain' => 0,
                        'net_weight' => $newDepartmentItems['newDepartmentItemsWeights'][$i],
                    ]);
                    TransferEvent::dispatch($transfer, $newDepartmentItems['newDepartmentItems'][$i]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $goldTransform;
    }

    public function removeUsedItemsWeightsFromDepartment(array $usedItems, array $usedWeights): array
    {
        $usedDepartmentItems = [];
        $usedDepartmentItemsWeights = [];
        for ($i = 0; $i < count($usedItems); $i++) {
            $departmentUsedItem = DepartmentItem::find($usedItems[$i]);
            $departmentUsedItem->update(
                [
                    'previous_weight' => $departmentUsedItem->current_weight,
                    'current_weight' => $departmentUsedItem->current_weight - $usedWeights[$i],
                ]
            );
            array_push($usedDepartmentItems, $departmentUsedItem);
            array_push($usedDepartmentItemsWeights, $usedWeights[$i]);
        }
        return [
            'usedDepartmentItems' => $usedDepartmentItems,
            'usedDepartmentItemsWeights' => $usedDepartmentItemsWeights,
        ];
    }

    public function addNewItemsInDepartment(array $newItemsIds, array $newWeights, array $newGoldItemsShares, int $departmentId): array
    {
        $newDepartmentItems = [];
        $newDepartmentItemsWeights = [];
        $newItems = Items::find($newItemsIds);
        $department = Department::find($departmentId);
        for ($i = 0; $i < count($newItemsIds); $i++) {
            $newItem = $newItems->where('id', $newItemsIds[$i])->first();
            $item = $department->items()->where('kind', $newItem?->code)
                ->where('shares', $newGoldItemsShares[$i])
                ->first();

            if ($item) {
                # code...
                $item->update([
                    'previous_weight' => $item->current_weight,
                    'current_weight' => $item->current_weight + $newWeights[$i],
                ]);
                array_push($newDepartmentItems, $item);
                array_push($newDepartmentItemsWeights, $newWeights[$i]);
            } else {
                $item = $department->items()->create([
                    'kind' => $newItem->code,
                    'shares' => $newGoldItemsShares[$i],
                    'karat' => $newItem->karat,
                    'kind_name' => $newItem->name,
                    'previous_weight' => 0,
                    'current_weight' =>  $newWeights[$i],
                ]);
                array_push($newDepartmentItems, $item);
                array_push($newDepartmentItemsWeights, $newWeights[$i]);
            }
        }
        return [
            'newDepartmentItems' => $newDepartmentItems,
            'newDepartmentItemsWeights' => $newDepartmentItemsWeights,
        ];
    }

    public function deleteGoldTransformItemsWeights($goldTransform): void
    {
        foreach ($goldTransform->newItems as $newItem) {
            try {
                $departmentItem = DepartmentItem::query()
                    ->department($goldTransform->department_id)
                    ->where('kind', $newItem->item->code)
                    ->when(
                        !$newItem->actual_shares,
                        fn ($query) => $query->whereNull('shares'),
                        fn ($query) => $query->where('shares', $newItem->actual_shares)
                    )
                    ->whereRaw('current_weight >= ' . $newItem->weight - 0.01)
                    ->firstOrFail();

                $departmentItem->previous_weight = $departmentItem->current_weight;
                $departmentItem->current_weight -= $newItem->weight;
                $departmentItem->save();
            } catch (\Throwable $th) {
                ValidationException::withMessages(['invalid action' => __("This Item Has been used Before")]);
            }
        }

        foreach ($goldTransform->usedItems as $usedItem) {
            $usedItem->load('departmentItem');
            $usedItem->departmentItem->previous_weight = $usedItem->departmentItem->current_weight;
            $usedItem->departmentItem->current_weight += $usedItem->weight;
            $usedItem->departmentItem->save();
        }
    }


    public function getGoldLosses(
        ?int $departmentId,
        ?int $workerId,
        ?string $fromDate,
        ?string $toDate,
    ): Collection|GoldLoss {
        $goldLosses = GoldLoss::query()
            ->with(['department', 'worker'])
            ->when($departmentId, fn ($query) => $query->filterByDepartment($departmentId))
            ->when($workerId, fn ($query) => $query->filterByWorker($workerId))
            ->when($fromDate, fn ($query) => $query->where('date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->where('date', '<=', $toDate))
            ->get();

        return $goldLosses;
    }
}
