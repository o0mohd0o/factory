<?php

namespace App\Http\Services;

use App\Models\DepartmentItem;
use App\Models\Department;
use App\Models\OpeningBalance;
use App\Models\Transfer;

class DepartmentService
{

    

    public static function usedBefore(Department $departmentCard)
    {
        if (
            OpeningBalance::where('department_id', $departmentCard->id)->exists() ||
            Transfer::where('transfer_from', $departmentCard->id)->exists() ||
            Transfer::where('transfer_to', $departmentCard->id)->exists()
        ) {
            return true;
        }

        return false;
    }
}
