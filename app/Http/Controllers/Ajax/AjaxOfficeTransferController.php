<?php

namespace App\Http\Controllers\Ajax;

use App\Events\OfficeTransferCreateEvent;
use App\Events\OfficeTransferDeleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficeTransferRequest;
use App\Http\Traits\OpeningBalanceTrait;
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
    use WeightTrait, OpeningBalanceTrait;

    public function index(Request $request)
    {
        try {
            $officeTransfer = OfficeTransfer::with(['details'])
                ->when($request->department_id, function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                })
                ->when($request->ordering == 'last', function ($query) {
                    return $query->latest();
                })
                ->when($request->ordering == 'next', function ($query) use ($request) {
                    return $query->where('id', '>', $request->id);
                })
                ->when($request->ordering == 'previous', function ($query) use ($request) {
                    return $query->where('id', '<', $request->id)->latest();
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
                // 'department' => $department,
            ])->render()
        ]);
    }

    public function create()
    {
        $lastId = DB::table('office_transfers')->max('id');

        return response()->json([
            view('components.office-transfers.create', [
                'lastId' => $lastId + 1 ?? '1',
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
            for ($i = 0; $i < count($data['kind']); $i++) {
                $dataDetails[$i]['kind'] = $data['kind'][$i];
                $dataDetails[$i]['kind_name'] = $data['kind_name'][$i];
                $dataDetails[$i]['karat'] = $data['karat'][$i];
                $dataDetails[$i]['shares'] = $data['shares'][$i];
                $dataDetails[$i]['unit'] = $data['unit'][$i];
                $dataDetails[$i]['quantity'] = $data['quantity'][$i];
                $dataDetails[$i]['salary'] = $data['salary'][$i];
                $dataDetails[$i]['total_cost'] = $data['total_cost'][$i];
                $dataDetails[$i]['weight'] = $this->unitToGram($data['unit'][$i], $dataDetails[$i]['quantity']);
            }

            for ($i = 0; $i < count($data['kind']); $i++) {
                try {
                    $item = $department->items()->where('kind', $dataDetails[$i]['kind'])
                        ->where('shares', $dataDetails[$i]['shares'])
                        ->firstOrFail();

                    if ($request->type == 'to' && $item->current_weight < $dataDetails[$i]['weight']) {
                        throw new Exception(__("Insuficient Balance"), 403);
                    }

                    $item->update([
                        'previous_weight' => $item->current_weight,
                        'current_weight' => $request->type == 'from' ? $item->current_weight + $dataDetails[$i]['weight'] : $item->current_weight - $dataDetails[$i]['weight'],
                    ]);
                } catch (ModelNotFoundException $e) {
                    if ($request->type == 'from') {
                        $item = $department->items()->create([
                            'kind' => $dataDetails[$i]['kind'],
                            'shares' => $dataDetails[$i]['shares'],
                            'karat' => $dataDetails[$i]['karat'],
                            'kind_name' => $dataDetails[$i]['kind_name'],
                            'previous_weight' => 0,
                            'current_weight' =>  $dataDetails[$i]['weight'],
                        ]);
                    }else{
                        throw new Exception(__("Insuficient Balance"), 403);
                    }
                }
                //Fire office transfer create event
                OfficeTransferCreateEvent::dispatch($item, 'create', $dataDetails[$i]['weight'], $officeTransfer->id, $department, $request->type);
            }


            $officeTransfer->details()->createMany($dataDetails);

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
        $lastId = DB::table('office_transfers')->max('id');
        return response()->json([
            view('components.office-transfers.edit', [
                'officeTransfer' => $officeTransfer,
                'department' => $officeTransfer->department,
                'lastId' => $lastId + 1,
            ])->render()
        ]);
    }

    public function update(StoreOfficeTransferRequest $request, OfficeTransfer $officeTransfer)
    {
        $officeTransfer->load(['department', 'details']);

        //Check if the office transfer used before
        //If it is used before we can not edit or delete it.
        $officeTransferMovementReport = $this->checkIfTheOpeningBalanceUsed($officeTransfer);
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
                $itemWeightToRemoveOrAdd = $officeTransfer->type == 'from'?- $item['removedWeight']: $item['removedWeight'];
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
        $officeTransferMovementReport = $this->checkIfTheOpeningBalanceUsed($officeTransfer);
        if ($officeTransferMovementReport['used']&& $officeTransfer->type == 'from') {
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
                $itemWeightToRemoveOrAdd = $officeTransfer->type == 'from'?- $item['removedWeight']: $item['removedWeight'];
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
