<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QrcodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date_format:Y-m-d',
            'person_on_charge' => 'required|string',
            'count' => 'required',
            'total_weight' => 'required',
            'total_fare' => 'required',
            'gold18' => 'nullable',
            'gold21' => 'nullable',
            'gold22' => 'nullable',
            'gold24' => 'nullable',
            'weight_in21' => 'required',
            'weight_in24' => 'required',
            'item_id' => 'array|required',
            'serial' => 'array|required',
            'quantity' => 'array|required',
            'sales_price' =>'array|required',
            'fare' =>'array|required',
            'item_id' => 'array|required',
            'item_id.*' =>'required',
            'serial.*' =>'required',
        ];
    }
}
