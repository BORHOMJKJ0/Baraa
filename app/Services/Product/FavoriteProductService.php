<?php

namespace App\Services\Product;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteProductService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function index(Request $request): JsonResponse
    {
        $items = $request->query('items', 20);

        $user = $this->userRepository->findById(auth()->user()->id);
        $favoriteProducts = $user->favoriteProducts()->paginate($items, ['*']);
        $data = [
            'products' => ProductResource::collection($favoriteProducts),
            'total_pages' => $favoriteProducts->lastPage(),
            'current_page' => $favoriteProducts->currentPage(),
            'hasMorePages' => $favoriteProducts->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'retrieve all favorite products');
    }

    public function store($product_id): JsonResponse
    {
        $user = $this->userRepository->findById(auth()->user()->id);
        $user->favoriteProducts()->attach($product_id);

        return ResponseHelper::jsonResponse([], 'Product added to favorites');
    }

    public function destroy($product_id): JsonResponse
    {
        $user = $this->userRepository->findById(auth()->user()->id);
        $user->favoriteProducts()->detach($product_id);

        return ResponseHelper::jsonResponse([], 'Product removed from favorites');
    }
}
