<?php

namespace App\Http\Resources\Store;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'address' => $this->address,
        ];
        if (! $request->routeIs('categories.show')) {
            $data['category'] = $this->category->name;
        }
        if ($request->routeIs('stores.show')) {
            $data['products'] = ProductResource::collection($this->products);
        }

        return $data;
    }
}
