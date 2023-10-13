<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemCardUpdateRequest extends FormRequest
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
            'fare' => 'nullable|numeric',
            'name' => 'required',
            'karat' => 'nullable',
            'shares' => 'nullable',
            'desc_1' => 'nullable|string',
            'desc_2' => 'nullable|string',
            'desc_3' => 'nullable|string',
            'desc_4' => 'nullable|string',
            'desc_5' => 'nullable|string',
        ];
    }
}
