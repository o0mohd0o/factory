<?php

namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\Controller;
use App\Http\Requests\QrcodeRequest;

use App\Models\PrintQrcode;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjaxQrcodeController extends Controller
{

    public function index(Request $request)
    {
        try {
            $qrcode = PrintQrcode::with(['details', 'details.item'])
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
        // dd($qrcode->details);

        return response()->json([
            view('components.qrcodes.index', [
                'qrcode' => $qrcode,
            ])->render()
        ]);
    }

    public function create()
    {
        $newBondNum = DB::table('print_qrcodes')->max('id');
        return response()->json([
            view('components.qrcodes.create', [
                'newBondNum' => $newBondNum,
            ])->render()
        ]);
    }


    public function store(QrcodeRequest $request)
    {

        $data = $request->validated();
        $data['weight_all21'] = $data['weight_in21'];
        $data['weight_all24'] = $data['weight_in24'];

        try {
            DB::beginTransaction();

            $qrcode = PrintQrcode::create($data);

            $dataDetails = [];
            //Loop through the input using the count of item_id
            for ($i = 0; $i < count($data['item_id']); $i++) {
                $dataDetails[$i]['item_id'] = $data['item_id'][$i];
                $dataDetails[$i]['serial'] = $data['serial'][$i];
                $dataDetails[$i]['quantity'] = $data['quantity'][$i];
                $dataDetails[$i]['printqr_details_fare'] = $data['fare'][$i];
                $dataDetails[$i]['sales_price'] = $data['sales_price'][$i];
            }


            $qrcode->details()->createMany($dataDetails);

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
            'message' => __('Qr Print Doc Created Successfully')
        ]);
    }


    public function edit(PrintQrcode $qrcode)
    {
        $qrcode->load(['details', 'details.item']);
        $newBondNum = DB::table('print_qrcodes')->max('id');
        return response()->json([
            view('components.qrcodes.edit', [
                'qrcode' => $qrcode,
                'newBondNum' => $newBondNum + 1,
            ])->render()
        ]);
    }

    public function update(QrcodeRequest $request, PrintQrcode $qrcode)
    {
        $qrcode->load(['details']);

        try {
            DB::beginTransaction();
            //Delete opening balance and their details
            $qrcode->delete();



            //Call store function and passing nesseccary arguments to it.
            $this->store($request);

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
            'message' => __('QrDoc updated successfully')
        ]);
    }

    public function delete(PrintQrcode $qrcode)
    {
        $qrcode->delete();
        return response()->json([
            'status' => 'success',
            'message' => __('Deleted Successfuly')
        ]);
    }
}
