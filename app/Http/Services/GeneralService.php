<?php

namespace App\Http\Services;

class GeneralService
{
    public function prepareTableDateToUse(array $arrayOfData,array $keys, int $lengthOfSingleArray) : ?array {
        $readyData = [];
        for ($i = 0; $i < $lengthOfSingleArray; $i++) {
            foreach ($keys as $key) {
                $readyData[$i][$key] = $arrayOfData[$key][$i];
            }
        }
        return $readyData;
    }

    public function canTransferItemFromDepartment($departmentId, $data) : bool {
        
    }


}