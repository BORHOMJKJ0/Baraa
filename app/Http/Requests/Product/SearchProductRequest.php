<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class SearchProductRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
        ];
    }
}
