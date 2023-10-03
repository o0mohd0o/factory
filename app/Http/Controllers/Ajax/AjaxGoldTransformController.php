<?php

namespace App\Http\Controllers\Ajax;

use App\Events\GoldTransformCreateEvent;
use App\Events\GoldTransformDeleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoldTransformStoreRequest;
use App\Http\Services\GoldTransformService;
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
    public $goldTransformService;

    public function __construct(GoldTransformService $goldTransformService)
    {
        $this->goldTransformService = $goldTransformService;
    }

    public function index(Request $request)
    {
        try {
            $goldTransform = GoldTransform::with([
                'newItems.item',
                'usedItems.departmentItem',
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
        $lastId = DB::table('gold_transforms')->max('id');

        return response()->json([
            view('components.gold-transform.create', [
                'lastId' => $lastId + 1 ?? '1',
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
            'usedItems.departmentItem',
            'goldLoss',
        ]);
        $lastId = DB::table('gold_transforms')->max('id');
        return response()->json([
            view('components.gold-transform.edit', [
                'goldTransform' => $goldTransform,
                'lastId' => $lastId + 1,
            ])->render()
        ]);
    }

    public function update(GoldTransformStoreRequest $request, GoldTransform $goldTransform)
    {
        try {
            DB::beginTransaction();
            $goldTransform->load(['newItems.item', 'usedItems', 'goldLoss']);
            $this->goldTransformService->delete($goldTransform);
            $goldTransform = $this->goldTransformService->createGoldTransform($request);
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
        $goldTransform->load(['newItems.item', 'usedItems', 'goldLoss']);
        $this->goldTransformService->delete($goldTransform);
        return response()->json([
            'status' => 'success',
            'message' => __('Gold Transorm deleted successfully.')
        ]);
    }
}
