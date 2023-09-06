<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait OpeningBalanceTrait
{
    public function checkIfTheOpeningBalanceUsed($openingBalance)
    {

        //Group opening balance items by kinds
        $kinds = $openingBalance->details->mapToGroups(function ($item, $key) {
            return [$item->kind . '-' . $item->shares => $this->unitToGram($item->unit, $item->quantity)];
        });
        $items = [];
        //Check if the kinds are still in the  department
        foreach ($kinds as $item => $weights) {
            // dd('cast(current_weight as signed) >= '. $weights->sum());
            try {
                $kind = $openingBalance->department->items()
                    ->where('kind', explode('-', $item)[0])
                    ->when(!explode('-', $item)[1], function ($query) {
                        return $query->whereNull('shares');
                    }, function ($query) use ($item) {
                        return $query->where('shares', explode('-', $item)[1]);
                    })
                    ->whereRaw('cast(current_weight as signed) >= ' . $weights->sum())
                    ->firstOrFail();
                $items[] = [
                    'kind' => $kind,
                    'removedWeight' => $weights->sum()
                ];
            } catch (ModelNotFoundException $e) {
                return [
                    'used' => true,
                    'items' => false
                ];
            }
        }
        return [
            'used' => false,
            'items' => $items
        ];;
    }
}
