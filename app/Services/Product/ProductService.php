<?php

namespace App\Services\Product;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    use AuthTrait;

    public function __construct(protected ProductRepository $productRepository) {}

    public function getAllProducts(Request $request)
    {
        $items = $request->query('items', 20);
        $column = $request->query('column', 'name');
        $direction = $request->query('direction', 'asc');
        $validColumns = ['name', 'price', 'amount', 'description'];
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
        $products = $this->productRepository->getAll($items, $column, $direction);

        $data = [
            'Products' => ProductResource::collection($products),
            'total_pages' => $products->lastPage(),
            'current_page' => $products->currentPage(),
            'hasMorePages' => $products->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'Products retrieved successfully');
    }

    public function getProductById(Product $product)
    {
        $data = ['product' => ProductResource::make($product)];

        return ResponseHelper::jsonResponse($data, 'Product retrieved successfully!');
    }

    public function createProduct(array $data, CreateProductRequest $request): JsonResponse
    {
        try {
            $path = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
            $data['image'] = $path;
            $this->checkStoreID($data['store_id'], 'Product', 'create');
            $product = $this->productRepository->create($data);
            $data = [
                'Product' => ProductResource::make($product),
            ];

            $response = ResponseHelper::jsonResponse($data, 'Product created successfully!', 201);
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function updateProduct(Product $product, array $data)
    {
        try {
            $this->checkProduct($product, 'Product', 'update');
            $this->checkStoreID($data['store_id'], 'Product', 'update');
            if (isset($data['image'])) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $data['image']->store('images', 'public');
                $data['image'] = $path;
            }
            $product = $this->productRepository->update($product, $data);
            $data = [
                'Product' => ProductResource::make($product),
            ];
            $response = ResponseHelper::jsonResponse($data, 'Product updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteProduct(Product $product)
    {
        try {
            $this->checkProduct($product, 'Product', 'delete');
            $this->productRepository->delete($product);
            $response = ResponseHelper::jsonResponse([], 'Product deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}
