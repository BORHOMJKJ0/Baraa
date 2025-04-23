<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'remember_me' => 'boolean',
            'fcm_token' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        // معالجة remember_me لتحويلها إلى قيمة boolean
        if ($this->has('remember_me')) {
            $this->merge([
                'remember_me' => filter_var($this->remember_me, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
