<?php

namespace App\Http\Controllers\Ajax;

use App\Actions\GenerateNewBondNumAction;
use App\Events\OfficeTransferCreateEvent;
use App\Events\OfficeTransferDeleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficeTransferRequest;
use App\Http\Services\GeneralService;
use App\Http\Services\ItemDailyJournalService;
use App\Http\Traits\officeTransferTrait;
use App\Http\Traits\WeightTrait;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\OfficeTransfer;
use App\Models\OfficeTransferReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxOfficeTransferController extends Controller
{
    use WeightTrait;

    protected $itemDailyJournalService;
    public $generateNewBondAction;

    public function __construct(GenerateNewBondNumAction $generateNewBondAction, ItemDailyJournalService $itemDailyJournalService)
    {
        $this->itemDailyJournalService = $itemDailyJournalService;
        $this->generateNewBondAction = $generateNewBondAction;
    }

    public function index(Request $request)
    {
        try {
            $officeTransfer = OfficeTransfer::with(['details.item'])
                ->when($request->department_id, function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                })
                ->when($request->ordering == 'last', function ($query) {
                    return $query->latest();
                })
                ->when($request->ordering == 'next', function ($query) use ($request) {
                    return $query->where('bond_num', '>', $request->bond_num);
                })
                ->when($request->ordering == 'previous', function ($query) use ($request) {
                    return $query->where('bond_num', '<', $request->bond_num)->latest();
                })
                ->when(!$request->ordering, function ($query) {
                    return $query->latest();
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry,There is no document.')
            ], 404);
        }

        return response()->json([
            view('components.office-transfers.index', [
                'officeTransfer' => $officeTransfer,
            ])->render()
        ]);
    }

    public function create()
    {
        $newBondNum = $this->generateNewBondAction->generateNewBondNum((new OfficeTransfer())->getTable());

        return response()->json([
            view('components.office-transfers.create', [
                'newBondNum' => $newBondNum,
            ])->render()
        ]);
    }


    public function store(StoreOfficeTransferRequest $request)
    {
        $department = Department::first();
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $officeTransfer = $department->officeTransfers()->create($data);

            $dataDetails = [];

            //End of remove duplicate kinds from input to use it to sum its weights
            //Loop through the input using the count of kinds
            $dataDetails = (new GeneralService())->prepareTableDateToUse(
                $data,
                ['item_id', 'actual_shares', 'unit', 'quantity', 'salary', 'total_cost', 'weight'],
                count($data['item_id'])
            );

            abort_if($request->type == 'to' && !(new GeneralService())->canTransferItemFromDepartment($department->id, $dataDetails), 422, __("Weights to transfer is less than current department item weight."));
            $officeTransfer = $department->officeTransfers()->create($data);
            $officeTransferDetails = $officeTransfer->details()->createMany($dataDetails);

            foreach ($officeTransferDetails as $officeTransferDetail) {
                $this->itemDailyJournalService->createEntery(
                    $officeTransfer->date,
                    $officeTransferDetail->item_id,
                    $department->id,
                    $officeTransfer->id,
                    get_class($officeTransfer),
                    debit: $request->type == 'to' ? 0 : $officeTransferDetail->weight,
                    credit: $request->type == 'from' ? 0 : $officeTransferDetail->weight,
                    actual_shares: $officeTransferDetail->actual_shares,
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

        session()->put('person_on_charge', $data['person_on_charge']);


        return response()->json([
            'status' => 'success',
            'message' => __('office transfer created successfully.')
        ]);
    }


    public function edit(OfficeTransfer $officeTransfer)
    {
        $officeTransfer->load(['department', 'details']);
        return response()->json([
            view('components.office-transfers.edit', [
                'officeTransfer' => $officeTransfer,
                'department' => $officeTransfer->department,
            ])->render()
        ]);
    }

    public function update(StoreOfficeTransferRequest $request, OfficeTransfer $officeTransfer)
    {
        $officeTransfer->load(['department', 'details']);

        //Check if the office transfer used before
        //If it is used before we can not edit or delete it.
        $officeTransferMovementReport = $this->checkIfTheofficeTransferUsed($officeTransfer);
        if ($officeTransferMovementReport['used'] && $officeTransfer->type == 'from') {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry, This document has been used.You can not edit or delete it.')
            ], 404);
        }


        try {
            DB::beginTransaction();
            //Delete office transfer and their details
            $officeTransfer->details()->delete();
            $officeTransfer->delete();

            //Remove the office transfer credits
            foreach ($officeTransferMovementReport['items'] as $item) {
                $item['kind']->previous_weight = $item['kind']->current_weight;
                $itemWeightToRemoveOrAdd = $officeTransfer->type == 'from' ? -$item['removedWeight'] : $item['removedWeight'];
                $item['kind']->current_weight += $itemWeightToRemoveOrAdd;
                $item['kind']->save();
                //Fire office transfer delete event
                OfficeTransferDeleteEvent::dispatch($item['kind'], 'edit', $itemWeightToRemoveOrAdd, $officeTransfer->id, $officeTransfer->department, $officeTransfer->type);
            }

            //Call store function and passing nesseccary arguments to it.
            $this->store($request, $officeTransfer->department);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('office transfer created successfully.')
        ]);
    }

    public function delete(OfficeTransfer $officeTransfer)
    {
        $officeTransfer->load(['department', 'details']);

        //Check if the office transfer used before
        //If it is used before we can not edit or delete it.
        $officeTransferMovementReport = $this->checkIfTheofficeTransferUsed($officeTransfer, weightStrict: false);
        if ($officeTransferMovementReport['used'] && $officeTransfer->type == 'from') {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry, This office transfer has been used.You can not edit or delete it.')
            ], 404);
        }


        try {
            DB::beginTransaction();
            //Delete office transfer and their details
            $officeTransfer->details()->delete();
            $officeTransfer->delete();
            //Remove the office transfer credits
            foreach ($officeTransferMovementReport['items'] as $item) {
                $item['kind']->previous_weight = $item['kind']->current_weight;
                $itemWeightToRemoveOrAdd = $officeTransfer->type == 'from' ? -$item['removedWeight'] : $item['removedWeight'];
                $item['kind']->current_weight += $itemWeightToRemoveOrAdd;
                $item['kind']->save();
                //Fire office transfer delete event
                OfficeTransferDeleteEvent::dispatch($item['kind'], 'delete', $itemWeightToRemoveOrAdd, $officeTransfer->id, $officeTransfer->department, $officeTransfer->type);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('office transfer deleted successfully.')
        ]);
    }
}
