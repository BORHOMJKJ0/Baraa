<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\BaseRequest;

class StoreCreateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'address' => 'required|string',
        ];
    }
}
