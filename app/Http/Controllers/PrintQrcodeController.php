<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\PrintQrcode;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PrintQrcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function printData(Request $request)
    {
        $settings = GeneralSettings::all();

        $postData = json_decode($request->input('data'));
        // dd($postData);

        if (!empty($postData)) {
            $postData =  explode(";", $postData);
            foreach ($postData as $row) {   // split the content by return then newline
                $result[] = explode(",", $row);             // split each row by semi-colon then space
            }
            array_shift($result);
            $result = serialize($result);
            Storage::disk('local')->put('printQR.txt', $result);
        }
        $postData = Storage::disk('local')->get('printQR.txt');
        $postData = unserialize($postData);
        return view('print-qrcode.print', compact('postData', 'settings'));
    }
    public function printDataAll(Request $request)
    {
        $settings = GeneralSettings::all();
        $postData = json_decode($request->input('data'));
        // dd($postData);
        if (!empty($postData)) {
            $postData =  explode(";", $postData);
            foreach ($postData as $row) {   // split the content by return then newline
                $result[] = explode(",", $row);             // split each row by semi-colon then space
            }
            array_shift($result);
            $result = serialize($result);
            Storage::disk('local')->put('printQRAll.txt', $result);
        }
        $postData = Storage::disk('local')->get('printQRAll.txt');
        $postData = unserialize($postData);

        return view('print-qrcode.print', compact('postData', 'settings'));
    }

    public function fetchitems(Request $request)
    {
        dd($request);
        $items = Items::where('name', 'like', '%' . $request->value . '%')
            ->orWhere('karat', 'like', '%' . $request->value . '%')
            ->get();
        return $items;
    }
    public function getLastSerial($id)
    {
        $serial = DB::table('printqr_details')->orderBy('serial','desc')->where('item_id', $id)->first();
        if (isset($serial)) {
            $serial = $serial->serial;
        } else {
            $serial = 0;
        }
        return $serial;
    }
 
}