<?php

namespace App\Http\Requests;

use App\Actions\GenerateNewBondNumAction;
use Illuminate\Foundation\Http\FormRequest;

class StoreOpeningBalanceRequest extends FormRequest
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
            'item_id' => 'array|required',
            'item_id.*' => 'required|exists:items,id',
            'weight' => 'array|required',
            'weight.*' => 'required|numeric',
            'actual_shares' => 'array|required',
            'actual_shares.*' => 'nullable|numeric',
            'quantity' => 'array|required|min:1',
            'unit' => 'array|required',
            'quantity.*' => 'required|min:1',
            'unit.*' => 'required|in:gram,kilogram,ounce',
            'salary' => 'array',
            'salary.*' => 'nullable',
            'total_cost' => 'array',
            'total_cost.*' => 'nullable',
            'bond_num' => 'required|unique:opening_balances,bond_num',
        ];
    }

   /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'bond_num' => (new GenerateNewBondNumAction())->generateNewBondNum('opening_balances'),
        ]);
    } 
}
