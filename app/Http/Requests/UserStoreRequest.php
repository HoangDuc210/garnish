<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required', Rule::unique('users')->ignore($this->id)],
            'role'     => ['required'],
            'password' => [
                'required_with:password_confirmation',
                'confirmed',
                Password::min(8),
            ],
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
            'username' => trans('user.username'),
            'password' => trans('user.password'),
            'role' => trans('user.role'),
        ];
    }
}
