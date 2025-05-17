<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mergedItems = [];

        // Merge duplicate cart items by product_id
        foreach ($this->cart_items as $cartItem) {
            $productId = $cartItem->product->id;

            if (isset($mergedItems[$productId])) {
                // Increase quantity of existing item
                $mergedItems[$productId]->quantity += $cartItem->quantity;
            } else {
                // Clone the cartItem (to avoid modifying the original)
                $mergedItems[$productId] = clone $cartItem;
            }
        }

        $totalPrice = 0;

        // Calculate total price for merged items
        foreach ($mergedItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        return [
            'id' => $this->id,
            'total_price' => round($totalPrice, 2),
            'items' => CartItemsResource::collection(array_values($mergedItems)),
        ];
    }
}
