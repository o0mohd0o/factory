<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class TransferStoreRequest extends FormRequest
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
            'transfer_to' => 'required|string|exists:departments,id',
            'transfer_to_name' => 'required|string',
            'kind' => 'required|string',
            'kind_name' => 'required|string',
            'shares' => 'nullable|numeric|gte:shares_to_transfer',
            'shares_to_transfer' => 'nullable|numeric',
            'weight_to_transfer' => 'required|string|gte:total_loss|min:1',
            'karat' => 'nullable|string',
            'total_loss' => 'required|string|min:0',
            'total_gain' => 'required|string|min:0',
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
            'date' => Carbon::today()->format('Y-m-d')
        ]);
    }
}
