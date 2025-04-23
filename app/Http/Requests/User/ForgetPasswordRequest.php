<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ForgetPasswordRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }
}
