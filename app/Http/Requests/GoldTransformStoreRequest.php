<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'transfer_to_department_id' => 'nullable|exists:departments,id',
            'department_id' => 'required|exists:departments,id',
            'date' => 'required|date_format:Y-m-d',
            'worker_id' => 'nullable|exists:workers,id',
            'used_item_id' => 'array|required',
            'used_item_id.*' => ['required', Rule::exists('item_daily_journals', 'item_id')],
            'weight_to_use' => 'array|required',
            'weight_to_use.*' => ['required', 'numeric', 'min:0'],
            'used_item_shares' => 'array|required',
            'used_item_shares.*' => ['nullable', 'numeric'],
            'new_item_id' => 'array|required',
            'new_item_id.*' => 'required|exists:items,id',
            'new_item_shares' => 'array|required',
            'new_item_shares.*' => 'nullable|numeric',
            'new_item_weight' => 'array|required',
            'new_item_weight.*' => 'required|min:0|numeric',
            'new_item_qty' => 'array|required',
            'new_item_qty.*' => 'nullable|min:1|numeric',
            'new_item_stone_weight' => 'array|required',
            'new_item_stone_weight.*' => 'nullable|min:0|numeric',
        ];
    }
}
