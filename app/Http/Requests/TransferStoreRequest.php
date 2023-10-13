<?php

namespace App\Http\Requests;

use App\Http\Services\ItemDailyJournalService;
use App\Models\ItemDailyJournal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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
            'transfer_from' => 'required|exists:departments,id',
            'transfer_to' => 'required|exists:departments,id',
            'item_id' => ['required', Rule::exists('item_daily_journals', 'item_id')],
            'actual_shares' => 'nullable|numeric',
            'weight_to_transfer' => 'required|numeric|lte:' . (new ItemDailyJournalService())->getDepartmentItemCurrentWeight($this->transfer_from, $this->item_id, $this->actual_shares),
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
