<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Enter JWT Bearer token in the format 'Bearer {token}'"
     * )
     */
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Get all products with sorting and pagination",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="items",
     *         in="query",
     *         description="Number of items per page",
     *
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Parameter(
     *         name="column",
     *         in="query",
     *         description="Column to order products by",
     *
     *         @OA\Schema(type="string", enum={"name", "price", "amount", "description"}, example="name")
     *     ),
     *
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Sort direction",
     *
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Products retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Products",
     *                     type="array",
     *
     *                     @OA\Items(
     *
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="name", type="string", example="asperiores"),
     *                         @OA\Property(property="image", type="string", example="https://via.placeholder.com/640x480.png/005599?text=technics+maxime"),
     *                         @OA\Property(property="price", type="integer", example=170),
     *                         @OA\Property(property="store", type="string", example="autem"),
     *                          @OA\Property(property="isFavorite", type="integer", example="1")
     *                     )
     *                 ),
     *                 @OA\Property(property="total_pages", type="integer", example=4),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="hasMorePages", type="boolean", example=true)
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid sort column or direction",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid sort column or direction. Allowed columns: name, price, amount, description. Allowed directions: asc, desc."),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        return $this->productService->getAllProducts($request);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Create product",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "price", "amount","description" ,"store_id"},
     *
     *                 @OA\Property(property="name", type="string", example="New Product Name"),
     *                 @OA\Property(property="description", type="string", example="New Product Description"),
     *                 @OA\Property(property="price", type="integer", example=10),
     *                 @OA\Property(property="amount", type="integer", example=1),
     *                 @OA\Property(property="store_id", type="integer", example=2),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully!",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product created successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Product",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="apples"),
     *                     @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/example.png"),
     *                     @OA\Property(property="description", type="string", example="2"),
     *                     @OA\Property(property="price", type="string", example="1"),
     *                     @OA\Property(property="amount", type="string", example="10"),
     *                     @OA\Property(property="store", type="string", example="voluptas"),
     *                     @OA\Property(property="isFavorite", type="integer", example="1")
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="The name field is required."),
     *                 @OA\Property(property="price", type="string", example="The price field is required."),
     *                 @OA\Property(property="amount", type="string", example="The amount field is required."),
     *                 @OA\Property(property="description", type="string", example="The description field is required."),
     *                 @OA\Property(property="store_id", type="string", example="The store_id field is required.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        return $this->productService->createProduct($request->validated(), $request);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID to retrieve",
     *
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully!",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="product",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="dolor"),
     *                     @OA\Property(property="image", type="string", nullable=true, example="https://via.placeholder.com/640x480.png/00aa22?text=technics+commodi"),
     *                     @OA\Property(property="description", type="string", example="type of money"),
     *                     @OA\Property(property="price", type="integer", example=829),
     *                     @OA\Property(property="amount", type="integer", example=575),
     *                     @OA\Property(property="store", type="string", example="ullam"),
     *                    @OA\Property(property="isFavorite", type="integer", example="1")
     *                  )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function show(Product $product): JsonResponse
    {
        return $this->productService->getProductById($product);
    }

    /**
     * @OA\Post(
     *     path="/products/{id}",
     *     summary="Update product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID to update",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *
     *                 @OA\Property(property="name", type="string", example="New Product Name"),
     *                 @OA\Property(property="description", type="string", example="New Product description"),
     *                 @OA\Property(property="price", type="integer", example=20),
     *                 @OA\Property(property="amount", type="integer", example=2),
     *                 @OA\Property(property="store_id", type="integer", example=3),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully!",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Product",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="apples"),
     *                     @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/QeK7usDiGbr0hMIEbha7fJuni3HSiEWJixmWhJgV.png"),
     *                     @OA\Property(property="description", type="string", example="Red fruit"),
     *                     @OA\Property(property="price", type="string", example="20"),
     *                     @OA\Property(property="amount", type="string", example="2"),
     *                     @OA\Property(property="store", type="string", example="provident"),
     *                     @OA\Property(property="isFavorite", type="integer", example="1")
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized to update this Product.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this Product."),
     *             @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The price field must be a number."),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return $this->productService->updateProduct($product, $request->validated());
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID to delete",
     *
     *         @OA\Schema(
     *             type="integer",
     *             example=10
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully!",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *          @OA\Response(
     *          response=403,
     *          description="You are not authorized to delete this Product.",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="successful", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="You are not authorized to delete this Product."),
     *              @OA\Property(property="status_code", type="integer", example=403)
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        return $this->productService->deleteProduct($product);
    }
}
