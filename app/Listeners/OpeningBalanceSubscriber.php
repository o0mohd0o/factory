<?php

namespace App\Listeners;

use App\Events\OpeningBalanceCreateEvent;
use App\Events\OpeningBalanceDeleteEvent;
use App\Models\DepartmentDailyReport;
use App\Models\OpeningBalanceReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OpeningBalanceSubscriber
{
    /**
     * Handle Opening Balance Creation events.
     */
    public function handleOpeningBalanceCreation($event)
    {
        //Create Opening balance report
        OpeningBalanceReport::create([
            'weight' => $event->weight,
            'transfer_to_previous_balance' => $event->departmentItem->previous_weight,
            'transfer_to_current_balance' => $event->departmentItem->current_weight,
            'date' => Carbon::today()->format('Y-m-d'),
            'kind' => $event->departmentItem->kind,
            'kind_name' => $event->departmentItem->kind_name, 
            'shares' => $event->departmentItem->shares, 
            'karat' => $event->departmentItem->karat, 
            'doc_num' => $event->openingBalanceId, //Opening
            'transfer_to' => $event->department->id,  //transfering to department id
            'transfer_to_name' => $event->department->name,  //transfering to department name
            'type' => $event->type //create, delete, edit opening balance
        ]);
        //End of Create Opening balance report

        //Creating or updating daily department item report after opening balance creation or editing.
        try {
            $openingBalanceCreationReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $event->department->id)
                ->where('kind', $event->departmentItem->kind)
                ->where('shares', $event->departmentItem->shares)
                ->firstOrFail();

            $openingBalanceCreationReport->update([
                'current_balance' => $event->departmentItem->current_weight,
                'debit' => $openingBalanceCreationReport->debit + $event->weight,
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $event->departmentItem->previous_weight,
                'current_balance' => $event->departmentItem->current_weight,
                'debit' => $event->weight,
                'date' => Carbon::today()->format('Y-m-d'),
                'kind' => $event->departmentItem->kind,
                'kind_name' => $event->departmentItem->kind_name,
                'shares' => $event->departmentItem->shares,
                'karat' => $event->departmentItem->karat,
                'department_id' => $event->department->id,
                'department_name' => $event->department->name,
            ]);
        }
        //End for creating or updating daily department item report after opening balance creation or editing.
    }

    /**
     * Handle Opening Balance Deletion events.
     */
    public function handleOpeningBalanceDeletion($event)
    {
        OpeningBalanceReport::create([
            'weight' => $event->weight,
            'transfer_to_previous_balance' => $event->departmentItem->previous_weight,
            'transfer_to_current_balance' => $event->departmentItem->current_weight,
            'date' => Carbon::today()->format('Y-m-d'),
            'kind' => $event->departmentItem->kind,
            'kind_name' => $event->departmentItem->kind_name, 
            'shares' => $event->departmentItem->shares, 
            'karat' => $event->departmentItem->karat, //refers to Karat
            'doc_num' => $event->openingBalanceId, //Opening
            'transfer_to' => $event->department->id,  //transfering to department id
            'transfer_to_name' => $event->department->name,  //transfering to department name
            'type' => $event->type //create, delete, edit opening balance
        ]);

         //Creating or updating daily department item report after opening balance deletion or editing.
         try {
            $openingBalanceDeletionReport = DepartmentDailyReport::day(Carbon::today()->format('Y-m-d'))
                ->where('department_id', $event->department->id)
                ->where('kind', $event->departmentItem->kind)
                ->where('shares', $event->departmentItem->shares)
                ->firstOrFail();

            $openingBalanceDeletionReport->update([
                'current_balance' => $event->departmentItem->current_weight,
                'credit' => $openingBalanceDeletionReport->credit + $event->weight,
            ]);
        } catch (ModelNotFoundException $e) {
            DepartmentDailyReport::create([
                'previous_balance' => $event->departmentItem->previous_weight,
                'current_balance' => $event->departmentItem->current_weight,
                'credit' => $event->weight,
                'date' => Carbon::today()->format('Y-m-d'),
                'kind' => $event->departmentItem->kind,
                'kind_name' => $event->departmentItem->kind_name,
                'karat' => $event->departmentItem->karat,
                'department_id' => $event->department->id,
                'department_name' => $event->department->name,
            ]);
        }
        //End for creating or updating daily department item report after opening balance deletion or editing.
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
            OpeningBalanceCreateEvent::class,
            [OpeningBalanceSubscriber::class, 'handleOpeningBalanceCreation']
        );

        $events->listen(
            OpeningBalanceDeleteEvent::class,
            [OpeningBalanceSubscriber::class, 'handleOpeningBalanceDeletion']
        );
    }
}
