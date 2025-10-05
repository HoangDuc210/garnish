<?php

namespace App\Http\Requests;

use App\Enums\Agent\TaxationMethod;
use App\Enums\PriceFormat;
use App\Enums\PrintType;
use App\Enums\TaxCategory;
use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AgentStoreRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'code' => strlen($this->code) < 3 ? sprintf('%03d', $this->code) : $this->code,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = $this->id ? null : Rule::unique('agents')->ignore($this->code)->where('deleted_at');
        return [
            'code' => ['required', 'unique:agents,code,'.$this->id.',id,deleted_at,NULL'],
            'name' => ['required', 'string', 'max:' . INPUT_MAX_LENGTH],
            'post_code' => ['nullable', 'regex:/^[0-9]{3}-[0-9]{4}$/'],
            'address' => ['nullable', 'string', 'max:' . INPUT_MAX_LENGTH],
            'address_more' => ['nullable', 'string', 'max:' . INPUT_MAX_LENGTH],
            'tel' => ['nullable', 'regex:/^(([0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4})|([0-9]{8,11}))$/'],
            'fax' => ['nullable', 'regex:/^(([0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4})|([0-9]{8,11}))$/'],
            'closing_date' => ['nullable', 'min: 1', 'max: 31', 'integer'],
            'agent.id' => ['nullable'],
            'collection_rate' => ['nullable', 'regex: /^(\d*\.)?\d+$/'],
            'fraction_rounding_code' => ['nullable', new Enum(PriceFormat::class)],
            'tax_type_code' => ['nullable', new Enum(TaxCategory::class)],
            'tax_fraction_rounding_code' => ['nullable', new Enum(PriceFormat::class)],
            'tax_taxation_method_code' => ['nullable', new Enum(TaxationMethod::class)],
            'print_type' => ['nullable', new Enum(PrintType::class)],
            'tax_rate' => ['required', 'min:0', 'integer'],
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
            'fraction_rounding_code' =>  trans('agent.price_format'),
            'tax_type_code' =>  trans('agent.tax_category'),
            'tax_fraction_rounding_code' =>  trans('agent.price_after_tax_format'),
            'tax_taxation_method_code' =>  trans('agent.consumption_taxation_method'),
            'print_type' => trans('agent.print_type'),
            'collection_rate' => trans('agent.collection_rate'),
            'tel' => trans('agent.tel'),
            'post_code' => trans('agent.zip_code'),
            'fax' => trans('agent.fax'),
            'code' => trans('agent.code'),
            'closing_date' => trans('agent.billing_cycle'),
            'tax_rate' => trans('agent.tax_rate'),
        ];
    }

    /**
     * Get custom message for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'collection_rate.gt' =>  trans('agent.billing_cycle').'数字で入力してください。',
            'code.unique' => '得意先番号「'. $this->code .'」は既に登録されています。'
        ];
    }
}
