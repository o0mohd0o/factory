<?php

namespace App\Http\Services;

use App\Models\Department;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferService
{
    public static function getDateOfTransfersForNavigator(Request $request, Department $department)
    {
        $data = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $transfer = Transfer::where(function ($query) use ($department) {
            $query->where('transfer_to', $department->id)
                ->orWhere('transfer_from', $department->id);
        })
            ->when($request->ordering == 'last', function ($query) {
                return $query->latest();
            })
            ->when($request->ordering == 'next', function ($query) use ($data) {
                return $query->where('date', '>', $data['date']);
            })
            ->when($request->ordering == 'previous', function ($query) use ($data) {
                // dd($data['date']);
                return $query->where('date', '<', $data['date'])->latest();
            })
            ->when(!$request->ordering, function ($query) {
                return $query->latest();
            })
            ->first();

        return $transfer ? $transfer->date : null;
    }

    public function transferToDepartment($data) : void {
        
    }
}
