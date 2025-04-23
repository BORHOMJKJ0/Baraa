<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'location' => 'sometimes|string',
            'email' => 'sometimes|unique:users,email,'.$this->user()->email,
        ];
    }
}
