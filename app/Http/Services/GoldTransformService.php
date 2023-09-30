<?php

namespace App\Http\Services;

use App\Models\DepartmentItem;
use App\Models\Department;
use App\Models\GoldTransform;
use App\Models\OpeningBalance;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
        return abs($diff / 1000) > 0.01 ?  $diff / 875 : null;
    }

    public function saveGoldTransform(
        string $date,
        string $worker,
        string $person_on_charge,
        int $department_id,

    ): GoldTransform {
        $goldTransform = GoldTransform::create([
            'date' => $date,
            'worker' => $worker,
            'person_on_charge' => $person_on_charge,
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
        return $goldTransform->newItemsData()->createMany($newItemsData);
    }

    public function deleteGoldTranform(GoldTransform $goldTransform)
    {
        $goldTransform->usedItems()->delete();
        $goldTransform->newItemsData()->delete();
        $goldTransform->delete();
    }

    public function createGoldTransform($request):?GoldTransform
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

        try {
            $goldTransform =  $this->saveGoldTransform($request->date, $request->worker, $request->person_on_charge, $request->department_id);
            $this->saveGoldTransformNewItems($goldTransform, $newItemsData);
            $this->saveGoldTransformUsedItems($goldTransform, $usedItemsData);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $goldTransform;
    }
}
