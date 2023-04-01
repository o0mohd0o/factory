<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartmentItem;
use App\Models\HesabatTransfer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HesabatTransferController extends Controller
{

    //Note this function needed to be updated.
    //Note this function needed to be updated.
    //Note this function needed to be updated.
    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_branch_id' => 'required',
            'sender_branch_name' => 'required|string',
            'date' =>  'nullable|sometimes|date_format:Y-m-d',
            'person_on_charge' => 'required|string',
            'transfer_sanad_num' => 'required',
            'department_id' => [
                'require', Rule::exists('departments')->where(function ($query) use ($request) {
                    return $query->where([
                        'main_department' => true,
                        'id' => $request->department_id
                    ]);
                })
            ],
            'secret_key' => [
                'require', Rule::exists('departments')->where(function ($query) use ($request) {
                    return $query->where([
                        'secret_key' => $request->secret_key,
                        'id' => $request->department_id
                    ]);
                })
            ]
        ]);

        try {
            DB::beginTransaction();

            $hesabatTransfer = HesabatTransfer::create($data);

            $requiredColumns = ['kind', 'kind_name', 'karat', 'unit', 'quantity'];

            foreach ($request->details as $transferDetails) {
                if (!collect($transferDetails)->has($requiredColumns)) {
                    throw new Exception("من فضلك قم بملئ جميع البيانات", 422);
                }
                $hesabatTransfer->details()->save(collect($transferDetails)->only($requiredColumns));
            }

            //Here to add the transfered blanace from hesabat to factory department item
            try {
                $departmentItem = DepartmentItem::where('department_id', $data['department_id'])
                    ->where('kind', $data['kind'])
                    ->firstOrFail();
                $departmentItem->update(
                    [
                        'previous_weight' => $departmentItem->current_weight,
                        'current_weight' => $departmentItem->current_weight + $data[''],
                    ]
                );
            } catch (ModelNotFoundException $e) {
                $departmentItem = DepartmentItem::create([
                    'kind' =>  $data['kind'],
                    'current_weight' => $data['kind'],
                    'previous_weight' => 0,
                    'karat' => $data['karat'],
                    'department_id' => $data['transfer_to'],
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transfer done successfully'
        ]);
    }
}
