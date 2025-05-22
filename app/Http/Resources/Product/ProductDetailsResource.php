<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'price' => $this->price,
            'isFavorite' => $this->favorites()->where(['user_id' => auth()->id(), 'product_id' => $this->id])->exists() ? 1 : 0,
        ];

        return $data;
    }
}
