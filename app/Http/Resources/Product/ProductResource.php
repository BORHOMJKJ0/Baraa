<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $imageUrl,
            'description' => $this->description,
            'price' => $this->price,
            'amount' => $this->amount,
            'store' => $this->store->name,
        ];
        if ($request->routeIs('products.index')) {
            unset($data['description']);
            unset($data['amount']);
        }

        return $data;
    }
}
