<?php

namespace App\Services\Store;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Store\StoreCreateRequest;
use App\Http\Resources\Store\StoreResource;
use App\Models\Store\Store;
use App\Repositories\Store\StoreRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreService
{
    use AuthTrait;

    public function __construct(protected StoreRepository $storeRepository) {}

    public function getAllStores(Request $request)
    {
        try {
            $items = $request->query('items', 20);
            $column = $request->query('column', 'name');
            $direction = $request->query('direction', 'asc');
            $validColumns = ['name', 'address'];
            $validDirections = ['asc', 'desc'];

            if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
                return ResponseHelper::jsonResponse(
                    [],
                    'Invalid sort column or direction. Allowed columns: '.implode(', ', $validColumns).
                    '. Allowed directions: '.implode(', ', $validDirections).'.',
                    400,
                    false
                );
            }
            $stores = $this->storeRepository->getAll($items, $column, $direction);

            $data = [
                'Stores' => StoreResource::collection($stores),
                'total_pages' => $stores->lastPage(),
                'current_page' => $stores->currentPage(),
                'hasMorePages' => $stores->hasMorePages(),
            ];
            $response = ResponseHelper::jsonResponse($data, 'Stores retrieved successfully');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;

    }

    public function getStoreById(Store $store): JsonResponse
    {
        $data = ['Store' => StoreResource::make($store)];

        return ResponseHelper::jsonResponse($data, 'Store retrieved successfully!');
    }

    public function createStore(array $data, StoreCreateRequest $request): JsonResponse
    {
        $data['user_id'] = auth()->id();
        $path = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $data['image'] = $path;
        $store = $this->storeRepository->create($data);
        $data = [
            'Store' => StoreResource::make($store),
        ];

        return ResponseHelper::jsonResponse($data, 'Store created successfully!', 201);
    }

    public function updateStore(Store $store, array $data)
    {
        $this->checkOwnership($store, 'Store', 'update');
        try {
            if (isset($data['image'])) {
                if ($store->image && Storage::disk('public')->exists($store->image)) {
                    Storage::disk('public')->delete($store->image);
                }
                $path = $data['image']->store('images', 'public');
                $data['image'] = $path;
            }
            $store = $this->storeRepository->update($store, $data);
            $data = [
                'Store' => StoreResource::make($store),
            ];

            $response = ResponseHelper::jsonResponse($data, 'Store updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteStore(Store $store)
    {
        try {
            $this->checkOwnership($store, 'Store', 'delete');
            $this->storeRepository->delete($store);
            $response = ResponseHelper::jsonResponse([], 'Store deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}
