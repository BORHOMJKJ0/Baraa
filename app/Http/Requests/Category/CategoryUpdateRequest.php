<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;

class CategoryUpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|image',
        ];
    }
}
