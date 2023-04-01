<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\ItemCardSettings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjaxItemCardSettingsController extends Controller
{
    public function show()
    {
        try {
            $itemCardSettings = ItemCardSettings::findOrFail(1);
        } catch (ModelNotFoundException $e) {
            $itemCardSettings = ItemCardSettings::create([
                'level_1' => '2',
                'level_2' => '2',
                'level_3' => '3',
                'level_4' => '3',
                'level_5' => '3',
            ]);
        }
        return response()->json([
            view('modals.item-card-settings-show', [
                'itemCardSettings' => $itemCardSettings
            ])->render()
        ]);
    }

    public function update(Request $request, ItemCardSettings $itemCardSettings)
    {
        $data = $request->validate([
            'level_1' => 'required|integer',
            'level_2' => 'required|integer',
            'level_3' => 'required|integer',
            'level_4' => 'required|integer',
            'level_5' => 'required|integer',
        ]);

        $itemCardSettings->update($data);

        return response()->json([
            'status' => 'success',
            'message' => __('Edited successfully'),
        ]);
    }
}
