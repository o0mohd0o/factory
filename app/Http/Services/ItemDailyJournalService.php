<?php

namespace App\Http\Services;

use App\Models\Department;
use App\Models\ItemDailyJournal;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemDailyJournalService
{
    public function getDepartmentItems($departmentId, $filterKey, $filterValue)
    {
        DB::statement("SET SQL_MODE=''");
        return ItemDailyJournal::query()
            ->join('items', 'item_daily_journals.item_id', 'items.id')
            ->select(
                'item_daily_journals.id as id',
                'item_id',
                'actual_shares',
                'department_id',
                DB::raw("sum(debit) as debit_total"),
                DB::raw("sum(credit) as credit_total"),
                DB::raw("sum(debit)-sum(credit) as current_weight"),
                'items.id',
                'items.code',
                'items.name',
                DB::raw("items.karat as karat"),
                DB::raw("items.shares as shares"),
            )
            ->department($departmentId)
            ->where(
                fn ($query) => $query->where('items.code', 'like', '%' . $filterValue . '%')
                    ->orWhere('items.name', 'like', '%' . $filterValue . '%')
            )
            ->groupBy(DB::raw('item_id ,actual_shares'))
            ->havingRaw('sum(debit) > sum(credit)')
            ->get();
    }


    public function getDepartmentItemCurrentWeight($departmentId, $itemId, $actualShares): ?float
    {
        DB::statement("SET SQL_MODE=''");
        return ItemDailyJournal::query()
            ->select(
                'id',
                DB::raw("sum(debit)-sum(credit) as current_weight"),
            )
            ->department($departmentId)
            ->where('item_id', $itemId)
            ->where('actual_shares', $actualShares)
            ->value('current_weight');
    }

    public function createEntery(
        string $date,
        int $itemId,
        int $departmentId,
        int $docId,
        string $docType,
        float $credit = 0,
        float $debit = 0,
        ?float $actual_shares = null,
        ?int $relatedDepartmentId = null,
        ?int $workerId = null,
    ): ItemDailyJournal {
        $entry = ItemDailyJournal::create([
            'date' => $date,
            'item_id' => $itemId,
            'debit' => $debit,
            'credit' => $credit,
            'department_id' => $departmentId,
            'related_department_id' => $relatedDepartmentId,
            'worker_id' => $workerId,
            'actual_shares' => $actual_shares,
            'doc_id' => $docId,
            'doc_type' =>  $docType,
        ]);
        return $entry;
    }

    public function canDeleteDoc($itemId, $departmentId, $actualShares, $weightToDelete): bool
    {
        DB::statement("SET SQL_MODE=''");
        return ItemDailyJournal::query()
            ->select(
                'id',
                DB::raw("sum(debit)-sum(credit) as current_weight"),
            )
            ->department($departmentId)
            ->where('item_id', $itemId)
            ->where('actual_shares', $actualShares)
            ->value('current_weight') >= $weightToDelete;
    }

    public function getPurityDifference(?string $fromDate, ?string $toDate, ?int $departmentId): Collection
    {
        $purityDifferences = ItemDailyJournal::query()
            ->enteryDateBetween($fromDate, $toDate)
            ->department($departmentId)
            ->purityDifference()
            ->whereDocType("App\Models\GoldTransform")
            ->get();

        return $purityDifferences;
    }

    public function getDepartmentsBalanceCalibIn21(?string $fromDate,?string $toDate): Collection
    {
        DB::statement("SET SQL_MODE=''");

        $departmentsBalances = ItemDailyJournal::query()
            ->enteryDateBetween(from: $fromDate, to: $toDate)
            ->select(
                "department_id",
                DB::raw("sum(ifnull(debit,0)-ifnull(credit,0))*actual_shares /875 as balance"),
                DB::raw("sum(ifnull(debit,0))*actual_shares /875 as total_debit"),
                DB::raw("sum(ifnull(credit,0))*actual_shares /875 as total_credit"),
            )
            ->groupBy("department_id")
            ->get();

        return $departmentsBalances;
    }
}
