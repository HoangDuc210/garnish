<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:' . INPUT_MAX_LENGTH],
            'post_code' => ['required', 'regex:/^[0-9]{3}-[0-9]{4}$/'],
            'address' => ['required', 'string', 'max:' . INPUT_MAX_LENGTH],
            'address_more' => ['nullable', 'string', 'max:' . INPUT_MAX_LENGTH],
            'tel' => ['required', 'regex:/^(([0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4})|([0-9]{8,11}))$/'],
            'fax' => ['nullable', 'regex:/^(([0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4})|([0-9]{8,11}))$/'],
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
            'name' => trans('company.name'),
            'zip_code' => trans('company.zip_code'),
            'address_one' => trans('company.address_one'),
            'address_two' => trans('company.address_two'),
            'tel' => trans('company.tel'),
            'fax' => trans('company.fax'),
        ];
    }
}
