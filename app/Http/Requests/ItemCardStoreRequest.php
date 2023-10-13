<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemCardStoreRequest extends FormRequest
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
            'code' => 'required|unique:items,code',
            'sub_code' => 'required',
            'fare' => 'nullable|numeric',
            'parent_id' => ['nullable', 'integer', 'exists:items,id', Rule::requiredIf(function () {
                return $this->level_num > 1;
            })],
            'parent_code' => ['exists:items,code', 'nullable', Rule::requiredIf(function () {
                return $this->level_num > 1;
            })],
            'name' => 'required',
            'karat' => 'nullable',
            'shares' => 'nullable',
            'level_num' => 'required|in:1,2,3,4,5',
            'desc_1' => 'nullable|string',
            'desc_2' => 'nullable|string',
            'desc_3' => 'nullable|string',
            'desc_4' => 'nullable|string',
            'desc_5' => 'nullable|string',
        ];
    }


    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->parent_code)) {
            $this->merge([
                'code' => $this->parent_code . '' . $this->sub_code,
            ]);
        } else {
            $this->merge([
                'code' => $this->sub_code,
            ]);
        }
    }
}
