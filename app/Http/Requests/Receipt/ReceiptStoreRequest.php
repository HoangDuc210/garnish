<?php

namespace App\Http\Requests\Receipt;

use App\Enums\Receipt\Type;
use App\Enums\ReceiptStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ReceiptStoreRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'type_code' => Type::Common,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = $this->id ? null : Rule::unique('receipts')->ignore($this->code)->where('type_code', Type::Common);
        $codeNull = $this->id ? 'required' : 'nullable';
        return [
            'code' => [$codeNull, 'max:50', $unique],
            'agent_id' => ['required'],
            'receipt_details' => ['required', 'array'],
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
            'code' => trans('receipt.code'),
            'receipt_details' => trans('app.product'),
        ];
    }
}
