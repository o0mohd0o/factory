<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoldTransformStoreRequest extends FormRequest
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
            'department_id' => 'required|exists:departments,id',
            'date' => 'required|date_format:Y-m-d',
            'inventory_record_date' => 'required|date_format:Y-m-d',
            'person_on_charge' => 'required|string',
            'inventory_record_num' => 'nullable|string',
            'kind' => 'array|required|min:1',
            'kind_name' => 'array|required|min:1',
            'karat' => 'array|required',
            'karat.*' => 'nullable|string',
            'shares' => 'array|required',
            'shares.*' => 'nullable|numeric',
            'quantity' => 'array|required|min:1',
            'unit' => 'array|required',
            'kind.*' => 'required|string',
            'kind_name.*' => 'required|string',
            'quantity.*' => 'required|min:1',
            'unit.*' => 'required|in:gram,kilogram,ounce',
            'salary' => 'array',
            'salary.*' => 'nullable',
            'total_cost' => 'array',
            'total_cost.*' => 'nullable',
        ];
    }
}
