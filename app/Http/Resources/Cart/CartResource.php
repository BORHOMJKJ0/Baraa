<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mergedItems = [];

        foreach ($this->cart_items as $cartItem) {
            $productId = $cartItem->product->id;

            if (isset($mergedItems[$productId])) {
                $mergedItems[$productId]->quantity += $cartItem->quantity;
            } else {
                $mergedItems[$productId] = clone $cartItem;
            }
        }

        $totalPrice = 0;

        foreach ($mergedItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }
        usort($mergedItems, function ($a, $b) {
            return strcmp($a->product->name, $b->product->name);
        });

        return [
            'id' => $this->id,
            'total_price' => round($totalPrice, 2),
            'items' => CartItemsResource::collection(array_values($mergedItems)),
        ];
    }
}
