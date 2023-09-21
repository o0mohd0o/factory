<?php

namespace App\Http\Controllers\Ajax;

use App\Events\TransferEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStoreRequest;
use App\Http\Services\TransferService;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\GeneralSettings;
use App\Models\Transfer;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AjaxTransferController extends Controller
{
    public function index(Request $request, Department $department)
    {
        $data = $request->validate([
            'date' => 'nullable|sometimes|date_format:Y-m-d',
        ]);


        $incomingTransfers = $department->incomingTransfers()
            ->day($data['date'] ?? Carbon::today()->format('Y-m-d'))
            ->get();

        $outcomingTransfers = $department->outcomingTransfers()
            ->day($data['date'] ?? Carbon::today()->format('Y-m-d'))
            ->get();

        return response()->json([
            view('components.transfer.index', [
                'department' => $department,
                'outcomingTransfers' => $outcomingTransfers,
                'incomingTransfers' => $incomingTransfers,
                'outcomingTransfersSum' => $outcomingTransfers->sum('weight_to_transfer'),
                'incomingTransfersSum' => $incomingTransfers->sum('weight_to_transfer'),
                'date' => $data['date'] ?? Carbon::today()->format('Y-m-d'),
            ])->render()
        ]);
    }

    public function navigator(Request $request, Department $department)
    {
        $date = TransferService::getDateOfTransfersForNavigator($request, $department);
        if (!$date) {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry,There is no document.')
            ], 404);
        }

        $incomingTransfers = $department->incomingTransfers()
            ->day($date)
            ->get();

        $outcomingTransfers = $department->outcomingTransfers()
            ->day($date)
            ->get();

        return response()->json([
            view('components.transfer.index', [
                'department' => $department,
                'outcomingTransfers' => $outcomingTransfers,
                'incomingTransfers' => $incomingTransfers,
                'outcomingTransfersSum' => $outcomingTransfers->sum('weight_to_transfer'),
                'incomingTransfersSum' => $incomingTransfers->sum('weight_to_transfer'),
                'date' => $date,
            ])->render()
        ]);
    }

    public function fetchDepartmentItems(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'field_name' => 'required',
            'value' => 'required',
        ]);

        $items = DepartmentItem::whereDepartmentId($request->department)
            ->where(function ($query) use ($request) {
                $query->where('kind', 'like', '%' . $request->value . '%')
                    ->orWhere('kind_name',  'like', '%' . $request->value . '%');
            })
            ->get();
        return $items;
    }

    public function fetchDepartments(Request $request)
    {

        $departments = Department::where('id', 'like', '%' . $request->value . '%')
            ->orWhere('name', 'like', '%' . $request->value . '%')
            ->get()
            ->except($request->department);

        return $departments;
    }


    public function store(TransferStoreRequest $request, Department $department)
    {
        $data = $request->validated();

        $data['net_weight'] = $data['weight_to_transfer'] - $data['total_loss'] + $data['total_gain'];

        try {
            $departmentItem = DepartmentItem::where('kind', $data['kind'])
                ->where('department_id', $department->id)
                ->where('shares', $data['shares'])
                ->where('current_weight', '>', 0)
                ->firstOrFail();
            $data['department_item_id'] = $departmentItem->id;
            $data['item_weight_before_transfer'] = $departmentItem->current_weight;
            $data['item_weight_after_transfer'] = $departmentItem->current_weight - $data['weight_to_transfer'];

            if ($data['weight_to_transfer'] > $departmentItem->current_weight) {
                throw new Exception(__("Insuficient Balance"), 403);
            }

            //Update Department Item
            $departmentItem->previous_weight = $departmentItem->current_weight;
            $departmentItem->current_weight -=  $data['weight_to_transfer'];
            //End of update Department Item
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),

            ], 500);
        }

        $data['transfer_from'] = $department->id;
        $data['transfer_from_name'] = $department->name;


        try {
            DB::beginTransaction();
            //Create the transfer
            $transfer = $department->outcomingTransfers()->create($data);
            $departmentItem->save();
            //Dispatch Transfer Event
            TransferEvent::dispatch($transfer, $departmentItem);

            Db::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }

        session()->put('person_on_charge', $data['person_on_charge']);

        return response()->json([
            'status' => 'success',
            'message' => __('Transfer Created Successfully'),
        ]);
    }

    public function print(Request $request)
    {
        $settings = GeneralSettings::all();
        $transferId = json_decode($request->input('transferId'));
        $transferId = intVal($transferId);
        if (!empty($transferId)) {
            Storage::disk('local')->put('printTransfer.txt', $transferId);
        }

        $transferId = Storage::disk('local')->get('printTransfer.txt');
        $transferItem = Transfer::where('id', $transferId)
            ->firstOrFail();
        $transferDateArr = explode(" ", $transferItem->created_at);
        $date = $transferDateArr[0];
        $time = $transferDateArr[1];
        return view('components.transfer.print', compact('transferItem', 'settings', 'date', 'time'));
    }
}
