<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Store\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];
        if ($request->routeIs('categories.show')) {
            $validColumns = ['name', 'address', 'created_at', 'updated_at'];
            $validDirections = ['asc', 'desc'];

            $orderBy = $request->query('order_by');
            $orderDirection = $request->query('order_direction');

            $isOrderValid = in_array($orderBy, $validColumns) && in_array($orderDirection, $validDirections);

            $query = $this->stores();
            if ($isOrderValid) {
                $query->orderBy($orderBy, $orderDirection);
            }

            $stores = $query->paginate($request->query('per_page', 20));
            $Stores = StoreResource::collection($stores);
            $data['stores'] = [
                'data' => StoreResource::collection($stores),
                'pagination' => [
                    'total_pages' => $stores->lastPage(),
                    'current_page' => $stores->currentPage(),
                    'hasMorePages' => $stores->hasMorePages(),
                ],
            ];
        }

        return $data;
    }
}
