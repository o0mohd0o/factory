<?php

namespace App\Http\Controllers\Ajax;

use App\Actions\GenerateNewBondNumAction;
use App\Events\GoldTransformCreateEvent;
use App\Events\GoldTransformDeleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoldTransformStoreRequest;
use App\Http\Requests\GoldTransformUpdateRequest;
use App\Http\Services\GoldTransformService;
use App\Http\Services\ItemDailyJournalService;
use App\Http\Traits\WeightTrait;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\GoldTransform;
use App\Models\GoldTransformReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxGoldTransformController extends Controller
{

    
    protected $itemDailyJournalService;
    public $generateNewBondAction;
    public $goldTransformService;

    public function __construct(GoldTransformService $goldTransformService,GenerateNewBondNumAction $generateNewBondAction, ItemDailyJournalService $itemDailyJournalService)
    {
        $this->itemDailyJournalService = $itemDailyJournalService;
        $this->generateNewBondAction = $generateNewBondAction;
        $this->goldTransformService = $goldTransformService;

    }

    public function index(Request $request)
    {
        try {
            $goldTransform = GoldTransform::with([
                'newItems.item',
                'usedItems.item',
                'goldLoss',
            ])
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
            view('components.gold-transform.index', [
                'goldTransform' => $goldTransform,
            ])->render()
        ]);
    }

    public function create()
    {
        $newBondNum = $this->generateNewBondAction->generateNewBondNum((new GoldTransform())->getTable());

        return response()->json([
            view('components.gold-transform.create', [
                'newBondNum' => $newBondNum,
            ])->render()
        ]);
    }


    public function store(GoldTransformStoreRequest $request)
    {
        
        $this->goldTransformService->createGoldTransform($request);

        session()->put('person_on_charge', $request->person_on_charge);


        return response()->json([
            'status' => 'success',
            'message' => __('Gold Transorm created successfully.')
        ]);
    }


    public function edit(GoldTransform $goldTransform)
    {
        $goldTransform->load([
            'newItems.item',
            'usedItems.item',
            'goldLoss',
        ]);
        return response()->json([
            view('components.gold-transform.edit', [
                'goldTransform' => $goldTransform,
            ])->render()
        ]);
    }

    public function update(GoldTransformUpdateRequest $request, GoldTransform $goldTransform)
    {
        try {
            DB::beginTransaction();
            $this->goldTransformService->updateGoldTransform($request, $goldTransform);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return response()->json([
            'status' => 'success',
            'message' => __('Gold Transorm Updated Successfully.')
        ]);
    }

    public function delete(GoldTransform $goldTransform)
    {
        $this->goldTransformService->delete($goldTransform);
        return response()->json([
            'status' => 'success',
            'message' => __('Gold Transorm deleted successfully.')
        ]);
    }
}
