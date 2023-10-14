<?php

namespace App\Http\Services;

use App\Models\Department;
use App\Models\ItemDailyJournal;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}