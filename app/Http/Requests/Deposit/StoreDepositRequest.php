<?php

namespace App\Http\Requests\Deposit;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_date' => ['required', 'string'],
            'agent' => ['required', 'array'],
            'agent.id' => ['required', 'string'],
            'agent.code' => ['required', 'string'],
            'deposits' => ['required', 'array'],
            'deposits.*.type_code' => ['required'],
        ];
    }
}
