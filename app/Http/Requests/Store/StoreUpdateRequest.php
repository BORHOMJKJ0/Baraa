<?php

namespace App\Http\Requests\Store;


use App\Http\Requests\BaseRequest;

class StoreUpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|string',
            'address' => 'sometimes|string',
        ];
    }
}
