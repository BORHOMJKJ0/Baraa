<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $groupedItems = [];

        foreach ($this->cart_items as $cartItem) {
            $productId = $cartItem->product->id;

            if (! isset($groupedItems[$productId])) {
                $groupedItems[$productId] = [
                    'product' => $cartItem->product,
                    'cart_items' => [],
                    'total_quantity' => 0,
                ];
            }

            $groupedItems[$productId]['cart_items'][] = [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
            ];

            $groupedItems[$productId]['total_quantity'] += $cartItem->quantity;
        }

        $totalPrice = 0;

        foreach ($groupedItems as &$group) {
            $totalPrice += $group['product']->price * $group['total_quantity'];
        }

        usort($groupedItems, function ($a, $b) {
            return strcmp($a['product']->name, $b['product']->name);
        });

        $items = array_map(function ($group) {
            $product = $group['product'];

            return [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'isFavorite' => $product->favorites()
                        ->where([
                            'user_id' => auth()->id(),
                            'product_id' => $product->id,
                        ])->exists() ? 1 : 0,
                ],
                'total_quantity' => $group['total_quantity'],
                'cart_items' => $group['cart_items'],
            ];
        }, $groupedItems);

        return [
            'id' => $this->id,
            'total_price' => round($totalPrice, 2),
            'items' => $items,
        ];
    }
}
