<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class ExportCsvByYearMonthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'calculate_date' => ['required', 'string', 'max:' . INPUT_MAX_LENGTH],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'billing_agent_id' => trans('billing_agent_id'),
            'calculate_date' => trans('calculate_date'),
        ];
    }
}
