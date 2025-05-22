<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartItemsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        if ($request->routeIs('cart_items.archive')) {
            $grouped = $this->collection->groupBy(function ($item) {
                return $item->deleted_at->format('Y-m-d H:i');
            });

            return [
                'grouped_items' => $grouped->map(function ($group, $deletedAt) use ($request) {
                    return [
                        'group_ordered_at' => $deletedAt,
                        'items' => CartItemsResource::collection($group)->toArray($request),
                    ];
                })->values()->toArray(),
            ];
        }

        return CartItemsResource::collection($this->collection)->toArray($request);
    }
}
