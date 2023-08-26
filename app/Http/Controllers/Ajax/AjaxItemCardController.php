<?php

namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\Controller;
use App\Http\Requests\ItemCardStoreRequest;
use App\Http\Requests\ItemCardUpdateRequest;
use App\Http\Services\ItemCardService;
use App\Models\Items;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AjaxItemCardController extends Controller
{

    public function index()
    {
        $items = Items::where('level_num', 1)->get();

        return response()->json([
            view('components.item-cards.index', [
                'items' => $items,
                'levelNum' => 1,
                'parentId' => null
            ])->render()
        ]);
    }

    public function refreshCurrentLevelItems(Request $request)
    {
        $data = $request->validate([
            'level_num' => 'required|integer|in:1,2,3,4,5',
            'parent_id' => ['nullable', 'integer', 'exists:items,id', Rule::requiredIf(function () use ($request) {
                return $request->level_num > 1;
            })],
        ], [
            'parent_level.in' => 'أقصى عدد للمستويات هى خمس مستويات',
        ]);

        $items = Items::where('level_num',  $data['level_num'])
            ->when($data['level_num'] > 1, function ($query) use ($data) {
                return $query->where('parent_id', $data['parent_id']);
            })
            ->get();

        return response()->json([
            view('components.item-cards.components.items-index', [
                'items' => $items,
                'levelNum' => $data['level_num'],
                'parentId' => $data['level_num'] == 1 ? null : $data['parent_id']
            ])->render()
        ]);
    }

    public function getItemsPerParent(Request $request)
    {

        $data = $request->validate([
            'level_num' => 'required|integer|in:1,2,3,4,5',
            'type' => 'required|in:next,previous',
            'parent_id' => ['nullable', 'integer', 'exists:items,id', Rule::requiredIf(function () use ($request) {
                return $request->level_num > 1;
            })],
        ], [
            'level_num.in' => 'أقصى عدد للمستويات هى خمس مستويات',
        ]);

        // dd($data);
        if ($data['type'] == 'previous' && isset($data['parent_id'])) {
            $parentItem = Items::find($data['parent_id']);
            $data['parent_id'] = $parentItem->parent_id;
        }


        $items = Items::where('level_num',  $data['level_num'])
            ->when($data['level_num'] > 1, function ($query) use ($data) {
                return $query->where('parent_id', $data['parent_id']);
            })
            ->get();

        return response()->json([
            view('components.item-cards.index', [
                'items' => $items,
                'levelNum' => $data['level_num'],
                'parentId' => $data['level_num'] == 1 ? null : $data['parent_id']
            ])->render()
        ]);
    }



    public function create(Request $request)
    {
        $data = $request->validate([
            'level_num' => 'required|integer|in:1,2,3,4,5',
            'parent_id' => ['nullable', 'integer', 'exists:items,id', Rule::requiredIf(function () use ($request) {
                return $request->level_num > 1;
            })],
        ], [
            'parent_level.in' => 'أقصى عدد للمستويات هى خمس مستويات',
        ]);


        $newItemCodes = ItemCardService::getNewItemCodes($data['level_num'], $data['parent_id']);

        return response()->json([
            view('components.item-cards.create', [
                'newItemCode' => $newItemCodes['newItemCode'],
                'parentItemCode' => $newItemCodes['parentItemCode'],
                'parentId' => $data['parent_id'],
                'levelNum' => $data['level_num'],
            ])->render()
        ]);
    }


    public function store(ItemCardStoreRequest $request)
    {
        $data = $request->validated();
        //Check if the parent item has been used before
        //If it is used then we can not make a sub item from it
        if ($data['parent_id'] != null) {
            $itemCard = Items::find($data['parent_id']);
            if (ItemCardService::usedBefore($itemCard)) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('We can not create new item from the parent item,It is used before'),
                ], 403);
            }
        }

        $itemCard = Items::create($data);
        return response()->json([
            'status' => 'success',
            'message' => __('Item Created Successfully'),
            'itemCard' => $itemCard,
        ]);
    }


    public function edit(Items $itemCard)
    {
        return response()->json([
            view('components.item-cards.edit', [
                'itemCard' => $itemCard,
            ])->render()
        ]);
    }

    public function update(ItemCardUpdateRequest $request, Items $itemCard)
    {
        // if (ItemCardService::usedBefore($itemCard) || $itemCard->hasChilds()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => __('This Item Has been used Before'),
        //     ], 403);
        // }
        $data = $request->validated();
        $itemCard->update($data);
        return response()->json([
            'status' => 'success',
            'message' => __('Item updated successfully'),
            'itemCard' => $itemCard,
        ]);
    }

    public function delete(Items $itemCard)
    {
        if (ItemCardService::usedBefore($itemCard) || $itemCard->hasChilds()) {
            return response()->json([
                'status' => 'error',
                'message' => __('This Item Has been used Before'),
            ], 403);
        }
        $itemCard->delete();
        return response()->json([
            'status' => 'success',
            'message' => __('Deleted Successfuly'),
            'levelNum' => $itemCard->level_num,
            'parentId' => $itemCard->parent_id,
        ]);
    }

    public function fetchItemCards(Request $request)
    {
        $items = Items::query()
            ->where(function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->value . '%')
                    ->orWhere('name', 'like', '%' . $request->value . '%');
            })
            ->doesntHave('childs')
            ->get();

        return $items;
    }

    public function fetchAllItemCards(Request $request)
    {
        $items = Items::query()
            ->where(function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->value . '%')
                    ->orWhere('name', 'like', '%' . $request->value . '%');
            })
            ->doesntHave('childs')
            ->get();

        return $items;
    }
}
