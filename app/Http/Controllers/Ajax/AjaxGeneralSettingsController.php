<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjaxGeneralSettingsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string',
            'company_address' => 'required|string',
            'company_phone' => 'required|string',
            'company_description' => 'nullable|string',
            'reading_data_from_hesabat' => 'required',
        ]);
        $data['reading_data_from_hesabat'] = $data['reading_data_from_hesabat']=='on'?1:0;

        try {
            $generalSettings = GeneralSettings::findOrFail(1);
            $generalSettings->update($data);
        } catch (ModelNotFoundException $e) {
            GeneralSettings::create($data);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => __('Edited successfully'),
        ]);
    }
}
