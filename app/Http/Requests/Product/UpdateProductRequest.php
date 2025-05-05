<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class UpdateProductRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|image',
            'amount'=> 'sometimes|integer|min:1',
            'price'=>'sometimes|double|min:1',
            'store_id'=>'sometimes|exists:stores,id',
        ];
    }
}
