<?php

namespace App\Listeners;

use App\Events\TransferEvent;
use App\Models\Department;
use App\Models\DepartmentDailyReport;
use App\Models\DepartmentItem;
use App\Models\Report;
use App\Models\TransferReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;

class TransferListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TransferEvent  $event
     * @return void
     */
    public function handle(TransferEvent $event)
    {

        //Create Or update the transfered to department item
        try {
            $departmentItem = DepartmentItem::where('department_id', $event->transfer->transfer_to)
                ->where('kind', $event->transfer->kind)
                ->where('shares', $event->transfer->shares_to_transfer)
                ->firstOrFail();
            $departmentItem->previous_weight = $departmentItem->current_weight;
            $departmentItem->current_weight += $event->transfer->net_weight;
            $departmentItem->save();
        } catch (ModelNotFoundException $e) {
            $departmentItem = DepartmentItem::create([
                'kind' => $event->transfer->kind,
                'current_weight' => $event->transfer->net_weight,
                'previous_weight' => 0,
                'karat' => $event->transfer->karat,
                'kind_name' => $event->transfer->kind_name,
                'shares' => $event->transfer->shares_to_transfer,
                'department_id' => $event->transfer->transfer_to,
            ]);
        }
        //End of Create Or update the transfered to department item


        //Create Report for that transfer
        TransferReport::create([
            'weight' => $event->transfer->net_weight,
            'transfer_to_previous_balance' => $departmentItem->previous_weight,
            'transfer_to_current_balance' => $departmentItem->current_weight,
            'transfer_from_previous_balance' => $event->transferFromDepartmentItem->previous_weight,
            'transfer_from_current_balance' => $event->transferFromDepartmentItem->current_weight,
            'date' => $event->transfer->date,
            'kind' => $event->transfer->kind,
            'kind_name' => $event->transfer->kind_name, 
            'karat' => $event->transfer->karat,
            'shares' => $event->transfer->shares,
            'shares_to_transfer' => $event->transfer->shares_to_transfer,
            // 'doc_type' => 2, //[transfering to ,transfering from, opening balance]
            'doc_num' => $event->transfer->id,
            'transfer_from' => $event->transfer->transfer_from, //transfering from department id
            'transfer_to' => $event->transfer->transfer_to,  //transfering to department id
            'transfer_from_name' => $event->transfer->transfer_from_name, //transfering from department name
            'transfer_to_name' => $event->transfer->transfer_to_name,  //transfering to department name
        ]);


        //Create department daily report for that transfer for the transfered to department
        try {
            $transferToDepartmentReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $departmentItem->department_id)
                ->where('kind', $departmentItem->kind)
                ->where('shares', $departmentItem->shares)
                ->firstOrFail();

            $transferToDepartmentReport->update([
                'current_balance' => $departmentItem->current_weight,
                'debit' => $transferToDepartmentReport->debit + $event->transfer->net_weight,
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => 0,
                'current_balance' => $departmentItem->current_weight,
                'debit' => $event->transfer->net_weight,
                'kind' => $departmentItem->kind,
                'kind_name' => $departmentItem->kind_name,
                'karat' => $departmentItem->karat,
                'shares' => $departmentItem->shares,
                'date' => Carbon::today()->format('Y-m-d'),
                'department_id' => $departmentItem->department_id,
                'department_name' => $event->transfer->transfer_to_name,
            ]);
        }
        //End for creating or updating daily transfer to department item report.


        //Create or update department daily report for that transfer for the transfered from department
        try {
            $transferFromDepartmentReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $event->transferFromDepartmentItem->department_id)
                ->where('kind', $event->transferFromDepartmentItem->kind)
                ->where('shares', $event->transferFromDepartmentItem->shares)
                ->firstOrFail();

            $transferFromDepartmentReport->update([
                'current_balance' => $event->transferFromDepartmentItem->current_weight,
                'credit' => $transferFromDepartmentReport->credit + $event->transfer->net_weight,
            ]);
            //End for creating or updating daily transfer from department item report.

        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $event->transferFromDepartmentItem->previous_weight,
                'current_balance' => $event->transferFromDepartmentItem->current_weight,
                'credit' => $event->transfer->net_weight,
                'kind' => $event->transferFromDepartmentItem->kind,
                'kind_name' => $event->transferFromDepartmentItem->kind_name,
                'karat' => $event->transferFromDepartmentItem->karat,
                'shares' => $event->transferFromDepartmentItem->shares,
                'date' => Carbon::today()->format('Y-m-d'),
                'department_id' => $event->transferFromDepartmentItem->department_id,
                'department_name' => $event->transfer->transfer_from_name,
            ]);
        }
    }
}
