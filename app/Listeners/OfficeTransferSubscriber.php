<?php

namespace App\Listeners;

use App\Events\OfficeTransferCreateEvent;
use App\Events\OfficeTransferDeleteEvent;
use App\Models\DepartmentDailyReport;
use App\Models\OfficeTransferReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfficeTransferSubscriber
{
    /**
     * Handle office transfer Creation events.
     */
    public function handleOfficeTransferCreation($event)
    {
        // Create office transfer report
        OfficeTransferReport::create([
            'weight' => $event->weight,
            'department_previous_balance' => $event->departmentItem->previous_weight,
            'department_current_balance' => $event->departmentItem->current_weight,
            'date' => Carbon::today()->format('Y-m-d'),
            'kind' => $event->departmentItem->kind,
            'kind_name' => $event->departmentItem->kind_name, 
            'shares' => $event->departmentItem->shares, 
            'karat' => $event->departmentItem->karat, 
            'transfer_type' => $event->officeTransferType, //Opening
            'doc_num' => $event->officeTransferId, //Opening
            'department_id' => $event->department->id,  //transfering to department id
            'department_name' => $event->department->name,  //transfering to department name
            'type' => $event->type //create, delete, edit office transfer
        ]);
        // End of Create office transfer report

        // Creating or updating daily department item report after office transfer creation or editing.
        try {
            $officeTransferCreationReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $event->department->id)
                ->where('kind', $event->departmentItem->kind)
                ->where('shares', $event->departmentItem->shares)
                ->firstOrFail();

            $officeTransferCreationReport->update([
                'current_balance' => $event->departmentItem->current_weight,
                'debit' => $officeTransferCreationReport->debit + $event->weight,
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $event->departmentItem->previous_weight,
                'current_balance' => $event->departmentItem->current_weight,
                'debit' => $event->officeTransferType=='from'?$event->weight:0,
                'credit' => $event->officeTransferType=='to'?$event->weight:0,
                'date' => Carbon::today()->format('Y-m-d'),
                'kind' => $event->departmentItem->kind,
                'kind_name' => $event->departmentItem->kind_name,
                'shares' => $event->departmentItem->shares,
                'karat' => $event->departmentItem->karat,
                'department_id' => $event->department->id,
                'department_name' => $event->department->name,
            ]);
        }
        //End for creating or updating daily department item report after office transfer creation or editing.
    }

    /**
     * Handle office transfer Deletion events.
     */
    public function handleOfficeTransferDeletion($event)
    {
        OfficeTransferReport::create([
            'weight' => $event->weight,
            'department_previous_balance' => $event->departmentItem->previous_weight,
            'department_current_balance' => $event->departmentItem->current_weight,
            'date' => Carbon::today()->format('Y-m-d'),
            'kind' => $event->departmentItem->kind,
            'kind_name' => $event->departmentItem->kind_name, 
            'shares' => $event->departmentItem->shares, 
            'karat' => $event->departmentItem->karat, //refers to Karat
            'transfer_type' => $event->officeTransferType, //
            'doc_num' => $event->officeTransferId, //
            'department_to' => $event->department->id,  //transfering to department id
            'department_name' => $event->department->name,  //transfering to department name
            'type' => $event->type //create, delete, edit office transfer
        ]);

         //Creating or updating daily department item report after office transfer deletion or editing.
         try {
            $officeTransferDeletionReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $event->department->id)
                ->where('kind', $event->departmentItem->kind)
                ->where('shares', $event->departmentItem->shares)
                ->firstOrFail();

            $officeTransferDeletionReport->update([
                'current_balance' => $event->departmentItem->current_weight,
                'credit' => $officeTransferDeletionReport->credit + $event->weight,
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $event->departmentItem->previous_weight,
                'current_balance' => $event->departmentItem->current_weight,
                'debit' => $event->officeTransferType=='to'?$event->weight:0,
                'credit' => $event->officeTransferType=='from'?$event->weight:0,
                'date' => Carbon::today()->format('Y-m-d'),
                'kind' => $event->departmentItem->kind,
                'kind_name' => $event->departmentItem->kind_name,
                'karat' => $event->departmentItem->karat,
                'department_id' => $event->department->id,
                'department_name' => $event->department->name,
            ]);
        }
        //End for creating or updating daily department item report after office transfer deletion or editing.
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            OfficeTransferCreateEvent::class,
            [OfficeTransferSubscriber::class, 'handleOfficeTransferCreation']
        );

        $events->listen(
            OfficeTransferDeleteEvent::class,
            [OfficeTransferSubscriber::class, 'handleOfficeTransferDeletion']
        );
    }
}
