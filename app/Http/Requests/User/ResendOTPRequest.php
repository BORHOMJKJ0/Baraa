<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ResendOTPRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validationData(): array
    {
        return array_merge($this->all(), $this->query());
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'subject' => 'required|string|in:resetPassword,emailVerify',
        ];
    }
}
