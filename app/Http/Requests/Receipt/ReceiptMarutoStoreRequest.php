<?php

namespace App\Http\Requests\Receipt;

use App\Enums\Receipt\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceiptMarutoStoreRequest extends FormRequest
{

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'type_code' => Type::ChainStore,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = $this->id ? null : Rule::unique('receipts')->ignore($this->code)->where('type_code', Type::ChainStore);
        $codeNull = $this->id ? 'required' : 'nullable';
        return [
            'code' => [$codeNull, 'max:50', $unique],
            'receipt_details' => ['required', 'array'],
            // 'products.*.code' => ['required'],
            // 'products.*.unit_code' => ['required'],
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
            'products' => trans('app.product'),
            'products.*.id' => trans('product.id'),
            'products.*.code' => trans('product.code'),
            'products.*.unit_code' => trans('product.unit'),
        ];
    }
}
