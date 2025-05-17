<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class CartItemsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity'=>$this->quantity,
            'product'=>ProductDetailsResource::make($this->product)
            ];
    }
}
