<?php

namespace App\Http\Services;

class GeneralService
{
    public function prepareTableDateToUse(array $arrayOfData, array $keys, int $lengthOfSingleArray): ?array
    {
        $readyData = [];
        for ($i = 0; $i < $lengthOfSingleArray; $i++) {
            foreach ($keys as $key) {
                $readyData[$i][$key] = $arrayOfData[$key][$i];
            }
        }
        return $readyData;
    }

    /**
     * check if the current department item weight is more than the weight to transfer.
     */
    public function canTransferItemFromDepartment($departmentId, $data): bool
    {
        $groupedItemsWithActualShares =  collect($data)->mapToGroups(function (array $item, int $key) {
            return [$item['item_id'] . '-' . $item['actual_shares'] => $item['weight']];
        });

        foreach ($groupedItemsWithActualShares as $item => $weightsToTransfer) {
            [$itemId, $actualShares] =  explode('-', $item);
            $currentItemWeight = (new ItemDailyJournalService())->getDepartmentItemCurrentWeight($departmentId, $itemId, $actualShares);
            if ($currentItemWeight < $weightsToTransfer->sum()) {
                return false;
            }
        }
        return true;
    }
}
