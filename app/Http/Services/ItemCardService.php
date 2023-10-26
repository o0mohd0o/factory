<?php

namespace App\Http\Services;

use App\Models\DepartmentItem;
use App\Models\ItemCardSettings;
use App\Models\Items;
use App\Models\OpeningBalance;
use App\Models\OpeningBalanceDetails;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemCardService
{
    public static function getNewItemCodes($levelNum = 1, $parentId = null)
    {
        $lastLevelItem = Items::where('level_num', $levelNum)
            ->when($levelNum != 1, function ($query) use ($parentId) {
                return $query->where('parent_id', $parentId);
            })
            ->latest()->first();


            if ($lastLevelItem) {
                if (is_numeric($lastLevelItem->sub_code)) {
                    $newItemCode =  ltrim($lastLevelItem->sub_code) + 1;
                }else{
                    $newItemCode =  $lastLevelItem->sub_code;
                }
            }else{
                $newItemCode =1 ;
            }
        $newItemCode = $lastLevelItem&&is_numeric($lastLevelItem->sub_code) ? ltrim($lastLevelItem->sub_code) + 1 : 1;

        try {
            //Get leading zeros from item card settings
            $itemCardSettings = ItemCardSettings::findOrFail(1);
            $leadingZeros = $itemCardSettings->{'level_' . $levelNum};
            $newItemCode = str_pad($newItemCode, $leadingZeros, "0", STR_PAD_LEFT);
        } catch (ModelNotFoundException $e) {
            //if not found use this defaults
            if (in_array($levelNum, ['1', '2'])) {
                $leadingZeros = 2;
            } else {
                $leadingZeros = 3;
            }
            $newItemCode = str_pad($newItemCode, $leadingZeros, "0", STR_PAD_LEFT);
        }

        //if there is a parent
        if ($parentId) {
            $parent = Items::find($parentId);
            $parentItemCode = $parent->code;
        } else {
            //if there is no parent
            $parentItemCode = null;
        }

        return [
            'newItemCode' => $newItemCode,
            'parentItemCode' => $parentItemCode
        ];
    }

    public static function usedBefore(Items $itemCard)
    {
        // if (
        //     OpeningBalanceDetails::where('kind', $itemCard->code)->exists() ||
        //     Transfer::where('kind', $itemCard->code)->exists() 
        // ) {
        //     return true;
        // }

        return false;
    }

    public static function getParentsObjects(?Items $item): ?array
    {
        $parentItem = Items::find($item?->parent_id);
        $parents = [];
        while ($parentItem) {
            array_push($parents, $parentItem);
            $parentItem = Items::find($parentItem->parent_id);
        }
        return $parents;
    }
}
