<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class UpdateProductRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'image' => 'nullable|image',
            'amount' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:1',
            'store_id' => 'sometimes|exists:stores,id',
        ];
    }
}
