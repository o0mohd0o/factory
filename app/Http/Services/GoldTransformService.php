<?php

namespace App\Http\Services;

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
    function prepareUsedItems(array $usedItems, array $usedWeights, array $usedItemShares): array
    {
        $usedItemsData = [];
        for ($i = 0; $i < count($usedItems); $i++) {
            array_push($usedItemsData, [
                'department_item_id' => $usedItems[$i],
                'weight' => $usedWeights[$i],
                'used_item_shares' => $usedItemShares[$i],
            ]);
        }
        return $usedItemsData;
    }

    function prepareNewItems(array $newItems, array $newWeights, array $newItemsShares, ?array $quantity, ?array $stone_weight): array
    {
        $newItemsData = [];
        for  ($i = 0; $i < count($newItems); $i++) {
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
    public function getGoldLoss(array $usedWeights, array $usedShares, array $newWeights, array $newGoldItemsShares): ?float
    {
        $totalUsedGoldWeight = 0;
        for ($i=0; $i < count($usedWeights); $i++) { 
            $totalUsedGoldWeight += $usedShares[$i] * $usedWeights[$i];
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
        dd($request->all());
        $usedItemsData = $this->prepareUsedItems(
            $request->used_item_id,
            $request->weight_to_use,
            $request->used_item_shares,
        );

        $newItemsData = $this->prepareNewItems(
            $request->new_item_id,
            $request->new_item_weight,
            $request->new_item_shares,
            $request->new_item_qty,
            $request->new_item_stone_weight,
        );

        $goldLoss = $this->getGoldLoss(
            $request->weight_to_use,
            $request->used_item_shares,
            $request->new_item_weight,
            $request->new_item_shares,
        );

        try {
            DB::beginTransaction();
            $goldTransform =  $this->saveGoldTransform($request->date, $request->worker_id, $request->department_id);
            $this->saveGoldTransformNewItems($goldTransform, $newItemsData);
            $this->saveGoldTransformUsedItems($goldTransform, $usedItemsData);
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
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $goldTransform;
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
