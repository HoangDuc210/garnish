<?php

namespace App\Http\Requests\Deposit;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'string'],
            'type_code' => ['required', 'string'],
            'amount' => ['required', 'string'],
            'memo' => ['nullable', 'string'],
        ];
    }
}
