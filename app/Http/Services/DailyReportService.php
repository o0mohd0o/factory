<?php

namespace App\Http\Services;

use App\Models\DepartmentItem;
use App\Models\Department;
use App\Models\DepartmentDailyReport;
use App\Models\OpeningBalance;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DailyReportService
{
    function addNewDailyReport($department, $departmentItem, $weight, $type='debit') {
        try {
            $newReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $department->id)
                ->where('kind', $departmentItem->kind)
                ->where('shares', $departmentItem->shares)
                ->firstOrFail();

            $newReport->update([
                'current_balance' => $departmentItem->current_weight,
                'debit' => $newReport->debit + ($type=='debit'? $weight:0),
                'credit' => $newReport->credit + ($type=='credit'? $weight:0),
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $departmentItem->previous_weight,
                'current_balance' => $departmentItem->current_weight,
                'debit' => ($type=='debit'? $weight:0),
                'credit' => ($type=='credit'? $weight:0),
                'date' => Carbon::today()->format('Y-m-d'),
                'kind' => $departmentItem->kind,
                'kind_name' => $departmentItem->kind_name,
                'shares' => $departmentItem->shares,
                'karat' => $departmentItem->karat,
                'department_id' => $department->id,
                'department_name' => $department->name,
            ]);
        }
    }
}
