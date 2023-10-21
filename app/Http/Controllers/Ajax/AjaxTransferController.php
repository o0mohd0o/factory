<?php

namespace App\Http\Controllers\Ajax;

use App\Events\TransferEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStoreRequest;
use App\Http\Services\ItemDailyJournalService;
use App\Http\Services\TransferService;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\GeneralSettings;
use App\Models\Transfer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AjaxTransferController extends Controller
{

    protected $transferService;
    protected $itemDailyJournalService;

    public function __construct(TransferService $transferService, ItemDailyJournalService $itemDailyJournalService)
    {
        $this->transferService = $transferService;
        $this->itemDailyJournalService = $itemDailyJournalService;
    }

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

        $incomingTransfers->load('item');
        $outcomingTransfers->load('item');

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

        try {
            DB::beginTransaction();

            $transfer = Transfer::create($data);
            $itemEntery = $this->itemDailyJournalService->createEntery(
                $transfer->date,
                $transfer->item_id,
                $transfer->transfer_from,
                $transfer->id,
                get_class($transfer),
                credit: $transfer->weight_to_transfer,
                debit: 0,
                actual_shares: $transfer->actual_shares,
                relatedDepartmentId: $transfer->transfer_to
            );
            $toDepartmentItemEntery = $itemEntery->replicate()->fill([
                'department_id' =>    $transfer->transfer_to,
                'debit' =>   $transfer->weight_to_transfer,
                'credit' =>   0,
                'related_department_id' =>  $transfer->transfer_from
            ]);
            $toDepartmentItemEntery->save();
            DB::commit();
            //End of update Department Item
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
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
        $transferItem = Transfer::query()
            ->with(['fromDepartment', 'toDepartment', 'item'])
            ->where('id', $transferId)
            ->firstOrFail();
        $transferDateArr = explode(" ", $transferItem->created_at);
        $date = $transferDateArr[0];
        $time = $transferDateArr[1];
        return view('components.transfer.print', compact('transferItem', 'settings', 'date', 'time'));
    }
}
