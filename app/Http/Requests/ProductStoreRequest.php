<?php

namespace App\Http\Requests;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => ['required', 'string', $this->checkUniqueAttribute('code', $this->code)],
            'name' => ['required', 'string', 'max:' . INPUT_MAX_LENGTH],
            'name_kana' => ['nullable', 'string', 'max:' . INPUT_MAX_LENGTH],
            'price' => ['nullable', 'numeric', 'lte:9999999999.99'],
            'quantity' => ['nullable', 'numeric', 'lte:9999999999'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => trans('product.name'),
            'name_kana' => trans('product.name_kana'),
            'code' => trans('product.code'),
            'price' => trans('product.price'),
            'quantity' => trans('product.quantity'),
        ];
    }

    /**
     * unique code product
     * @return bool
     */
    protected function checkUniqueAttribute($column, $value)
    {
        $product = Product::where($column, $value)->first();

        if (!$this->id || !is_null($product) && $this->id != $product->id) {
            return Rule::unique('products')->ignore($value);
        }

        return null;
    }
}
