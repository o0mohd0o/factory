<?php

namespace App\Http\Controllers\Ajax;

use App\Events\OpeningBalanceCreateEvent;
use App\Events\OpeningBalanceDeleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOpeningBalanceRequest;
use App\Http\Services\ItemDailyJournalService;
use App\Http\Traits\OpeningBalanceTrait;
use App\Http\Traits\WeightTrait;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\OpeningBalance;
use App\Models\OpeningBalanceReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxOpeningBalanceController extends Controller
{
    use WeightTrait, OpeningBalanceTrait;

    protected $itemDailyJournalService;

    public function __construct(ItemDailyJournalService $itemDailyJournalService)
    {
        $this->itemDailyJournalService = $itemDailyJournalService;
    }
    public function index(Request $request)
    {
        try {
            $openingBalance = OpeningBalance::with(['details.item'])
                ->when($request->department_id, function ($query) use($request) {
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
            view('components.opening-balances.index', [
                'openingBalance' => $openingBalance,
                // 'department' => $department,
            ])->render()
        ]);
    }

    public function create()
    {
        $lastId = DB::table('opening_balances')->max('id');

        return response()->json([
            view('components.opening-balances.create', [
                'lastId' => $lastId + 1 ?? '1',
            ])->render()
        ]);
    }


    public function store(StoreOpeningBalanceRequest $request)
    {

        $department = Department::findOrFail($request->department_id);
        $data = $request->validated();

        try {
            DB::beginTransaction();


            $dataDetails = [];
            
            //End of remove duplicate kinds from input to use it to sum its weights
            //Loop through the input using the count of kinds
            for ($i = 0; $i < count($data['item_id']); $i++) {
                $dataDetails[$i]['item_id'] = $data['item_id'][$i];
                $dataDetails[$i]['actual_shares'] = $data['actual_shares'][$i];
                $dataDetails[$i]['unit'] = $data['unit'][$i];
                $dataDetails[$i]['quantity'] = $data['quantity'][$i];
                $dataDetails[$i]['salary'] = $data['salary'][$i];
                $dataDetails[$i]['total_cost'] = $data['total_cost'][$i];
                $dataDetails[$i]['weight'] = $data['weight'][$i];
            }

            $openingBalance = $department->openingBalances()->create($data);
            $openingBalanceDetails = $openingBalance->details()->createMany($dataDetails);
            foreach ($openingBalanceDetails as $openingBalanceDetail) {
                $this->itemDailyJournalService->createEntery(
                    $openingBalance->date,
                    $openingBalanceDetail->item_id,
                    $department->id,
                    $openingBalanceDetail->id,
                    get_class($openingBalanceDetail),
                    debit: $openingBalanceDetail->weight,
                    credit: 0,
                    actual_shares: $openingBalanceDetail->actual_shares,
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
            'message' => __('Opening balance created successfully.')
        ]);
    }


    public function edit(OpeningBalance $openingBalance)
    {
        $openingBalance->load(['department', 'details.item']);
        $lastId = DB::table('opening_balances')->max('id');
        return response()->json([
            view('components.opening-balances.edit', [
                'openingBalance' => $openingBalance,
                'department' => $openingBalance->department,
                'lastId' => $lastId + 1,
            ])->render()
        ]);
    }

    public function update(StoreOpeningBalanceRequest $request, OpeningBalance $openingBalance)
    {
        $openingBalance->load(['department', 'details']);

        //Check if the opening balance used before
        //If it is used before we can not edit or delete it.
        $openingBalanceMovementReport = $this->checkIfTheOpeningBalanceUsed($openingBalance);
        if ($openingBalanceMovementReport['used']) {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry, This opening balance has been used.You can not edit or delete it.')
            ], 404);
        }


        try {
            DB::beginTransaction();
            //Delete opening balance and their details
            $openingBalance->details()->delete();
            $openingBalance->delete();

            //Remove the opening balance credits
            foreach ($openingBalanceMovementReport['items'] as $item) {
                $item['kind']->previous_weight = $item['kind']->current_weight;
                $item['kind']->current_weight -= $item['removedWeight'];
                $item['kind']->save();
                //Fire opening balance delete event
                OpeningBalanceDeleteEvent::dispatch($item['kind'], 'edit', $item['removedWeight'], $openingBalance->id, $openingBalance->department);
            }

            //Call store function and passing nesseccary arguments to it.
            $this->store($request, $openingBalance->department);

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
            'message' => __('Opening balance created successfully.')
        ]);
    }

    public function delete(OpeningBalance $openingBalance)
    {
        $openingBalance->load(['department', 'details.item']);

        //Check if the opening balance used before
        //If it is used before we can not edit or delete it.
        $openingBalanceMovementReport = $this->checkIfTheOpeningBalanceUsed($openingBalance);
        if ($openingBalanceMovementReport['used']) {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry, This opening balance has been used.You can not edit or delete it.')
            ], 404);
        }


        try {
            DB::beginTransaction();
            //Delete opening balance and their details
            $openingBalance->details()->delete();
            $openingBalance->delete();
            //Remove the opening balance credits
            foreach ($openingBalanceMovementReport['items'] as $item) {
                $item['kind']->previous_weight = $item['kind']->current_weight;
                $item['kind']->current_weight -= $item['removedWeight'];
                $item['kind']->save();
                //Fire opening balance delete event
                OpeningBalanceDeleteEvent::dispatch($item['kind'], 'delete', $item['removedWeight'], $openingBalance->id, $openingBalance->department);
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
            'message' => __('Opening balance deleted successfully.')
        ]);
    }
}
