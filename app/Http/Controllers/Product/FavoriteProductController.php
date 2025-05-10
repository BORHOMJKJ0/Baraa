<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Services\Product\FavoriteProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteProductController extends Controller
{
    public function __construct(protected FavoriteProductService $favoriteProductService) {}

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
     *     path="/products/favorites/index",
     *     summary="Retrieve all favorite products",
     *     description="Get a paginated list of the user's favorite products with details, images, category, and other associated data.",
     *     tags={"Favorite Products"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="items",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="retrieve all favorite products"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Baumbach, Wiegand and Monahan"),
     *                         @OA\Property(property="image", type="string", nullable=true, example="https://via.placeholder.com/640x480.png/00aaaa?text=apple+ut"),
     *                         @OA\Property(property="description", type="string", example="I shouldn't want YOURS..."),
     *                         @OA\Property(property="price", type="number", format="float", example=11.44),
     *                         @OA\Property(property="amount", type="integer", example=3),
     *                         @OA\Property(property="store", type="string", example="Zieme Group"),
     *                         @OA\Property(property="isFavorite", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="total_pages", type="integer", example=1),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="hasMorePages", type="boolean", example=false)
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        return $this->favoriteProductService->index($request);
    }

    /**
     * @OA\Post(
     *     path="/products/favorites/store/{product_id}",
     *     summary="Add a product to the user's favorites",
     *     description="Adds the specified product to the authenticated user's list of favorite products.",
     *     tags={"Favorite Products"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         description="ID of the product to add to favorites",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product added to favorites successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product added to favorites"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function store(Product $product): JsonResponse
    {
        return $this->favoriteProductService->store($product->id);
    }

    /**
     * @OA\Delete(
     *     path="/products/favorites/destroy/{product_id}",
     *     summary="Remove a product from the user's favorites",
     *     description="Removes the specified product from the authenticated user's list of favorite products.",
     *     tags={"Favorite Products"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         description="ID of the product to remove from favorites",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from favorites successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product removed from favorites"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in favorites",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        return $this->favoriteProductService->destroy($product->id);
    }
}
