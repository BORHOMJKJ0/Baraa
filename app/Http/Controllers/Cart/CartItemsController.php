<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartItemsCreateRequest;
use App\Http\Requests\Cart\CartItemsUpdateRequest;
use App\Models\Cart\Cart_items;
use App\Services\Cart\Cart_Items_Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{
    public function __construct(protected Cart_Items_Service $cart_items_Service) {}

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
     *     path="/cart_items",
     *     summary="Get my Cart_items",
     *     tags={"Cart_items"},
     *     security={{"bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Page number",
     *
     *         @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Parameter(
     *          name="items",
     *          in="query",
     *          required=false,
     *          description="Number of items per page",
     *
     *          @OA\Schema(type="integer", example=5)
     *      ),
     *
     *      @OA\Parameter(
     *          name="direction",
     *          in="query",
     *          required=false,
     *          description="Sorting direction (asc or desc)",
     *
     *          @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *      ),
     *
     *     @OA\Parameter(
     *          name="column",
     *          in="query",
     *         required=false,
     *          description="Column to sort by (quantity,product_name)",
     *
     *          @OA\Schema(type="string", enum={"quantity,product_name"}, example="quantity,product_name")
     *     ),
     *
     * @OA\Response(
     *      response=200,
     *      description="Cart_items retrieved successfully!",
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="successful", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Cart_items retrieved successfully!"),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="Cart_items", type="array",
     *
     *                  @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="id", type="integer", example=16),
     *                      @OA\Property(property="quantity", type="integer", example=10),
     *                      @OA\Property(property="product", type="object",
     *                          @OA\Property(property="id", type="integer", example=5),
     *                          @OA\Property(property="name", type="string", example="Langworth, Larson and Heller"),
     *                          @OA\Property(property="image", type="string", nullable=true, example=null),
     *                          @OA\Property(property="price", type="number", format="float", example=531.41),
     *                          @OA\Property(property="isFavorite", type="integer", example=0)
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="total_pages", type="integer", example=1),
     *              @OA\Property(property="current_page", type="integer", example=1),
     *              @OA\Property(property="hasMorePages", type="boolean", example=false)
     *          ),
     *          @OA\Property(property="status_code", type="integer", example=200)
     *      )
     * ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid parameters",
     *
     *         @OA\JsonContent(
     *
     *               @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid parameters"),
     *     @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        return $this->cart_items_Service->getAllCart_items($request);
    }

    /**
     * @OA\Post(
     *     path="/cart_items",
     *     summary="Create a Cart_items",
     *     tags={"Cart_items"},
     *     security={{"bearerAuth": {} }},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *      @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *             required={"quantity", "product_id"},
     *
     *             @OA\Property(property="quantity", type="number", example=100,description="Cart_items quantity"),
     *             @OA\Property(property="product_id", type="integer", example=1,description="product ID that you want to add this Cart_items from it")
     *             )
     *         )
     *     ),
     *
     *      @OA\Header(
     *         header="Content-Type",
     *         description="Content-Type header",
     *
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *
     *  @OA\Response(
     *       response=201,
     *       description="Cart_item created successfully!",
     *
     *       @OA\JsonContent(
     *           type="object",
     *
     *           @OA\Property(property="successful", type="boolean", example=true),
     *           @OA\Property(property="message", type="string", example="Cart_item created successfully!"),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="Cart_items", type="object",
     *                   @OA\Property(property="id", type="integer", example=16),
     *                   @OA\Property(property="quantity", type="string", example="10"),
     *                   @OA\Property(property="product", type="object",
     *                       @OA\Property(property="id", type="integer", example=5),
     *                       @OA\Property(property="name", type="string", example="Langworth, Larson and Heller"),
     *                       @OA\Property(property="image", type="string", nullable=true, example=null),
     *                       @OA\Property(property="price", type="number", format="float", example=531.41),
     *                       @OA\Property(property="isFavorite", type="integer", example=0)
     *                   )
     *             )
     *           ),
     *          @OA\Property(property="status_code", type="integer", example=201)
     *       )
     *  ),
     *
     *    @OA\Response(
     *         response=403,
     *         description="forbidden error",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to create this Cart_items ."),
     *     @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *     @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     * )
     */
    public function store(CartItemsCreateRequest $request): JsonResponse
    {
        return $this->cart_items_Service->createCart_items($request->validated());
    }

    /**
     * @OA\Put(
     *     path="/cart_items/{id}",
     *     summary="Update a Cart_items",
     *     tags={"Cart_items"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *     description="your Cart_items ID that you want to update it",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="quantity",
     *         in="query",
     *         required=false,
     *     description="The quantity of this Cart_items",
     *
     *         @OA\Schema(type="integer", example=100)
     *     ),
     *
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         required=false,
     *     description="product ID of this Cart_items",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Header(
     *         header="Content-Type",
     *         description="Content-Type header",
     *
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart_items updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="amount", type="integer", example=0),
     *             @OA\Property(property="expiry_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(
     *                 property="cart",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Iphone 15"),
     *                 @OA\Property(property="price", type="number", format="float", example="499.99"),
     *                 @OA\Property(property="category", type="string", example="Smartphone"),
     *                 @OA\Property(property="user", type="string", example="Hasan Zaeter"),
     *    @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cart_item updated successfully"),
     *     @OA\Property(property="status_code", type="integer", example=200)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *     @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *
     *  @OA\Response(
     *         response=403,
     *         description="forbidden error",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this Cart_items ."),
     *     @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart_items not found",
     *
     *         @OA\JsonContent(
     *
     *@OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart_items not found"),
     *     @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function update(Cart_items $cart_item, CartItemsUpdateRequest $request): JsonResponse
    {
        return $this->cart_items_Service->updateCart_items($cart_item, $request->validated());
    }

    /**
     * @OA\Get(
     *     path="/cart_items/{id}",
     *     summary="Get a Cart_items by ID",
     *     tags={"Cart_items"},
     *     security={{"bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *     description="your Cart_items ID you want to show it",
     *
     *        @OA\Schema(type="integer", example=1)
     *     ),
     *
     *  @OA\Response(
     *      response=200,
     *      description="Cart_item retrieved successfully!",
     *
     *      @OA\JsonContent(
     *
     *          @OA\Property(property="successful", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Cart_item retrieved successfully!"),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="Cart_items", type="object",
     *                  @OA\Property(property="id", type="integer", example=16),
     *                  @OA\Property(property="quantity", type="integer", example=10),
     *                  @OA\Property(property="product", type="object",
     *                      @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Langworth, Larson and Heller"),
     *                      @OA\Property(property="image", type="string", format="url", nullable=true, example=null),
     *                      @OA\Property(property="price", type="number", format="float", example=531.41),
     *                      @OA\Property(property="isFavorite", type="integer", example=0)
     *                  )
     *              )
     *          ),
     *          @OA\Property(property="status_code", type="integer", example=200)
     *      )
     * ),
     *
     *    @OA\Response(
     *         response=403,
     *         description="forbidden error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="You are not authorized to view this Cart_items.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="cart not found",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="cart not found"),
     *     @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(Cart_items $cart_item): JsonResponse
    {
        return $this->cart_items_Service->getCart_itemById($cart_item);
    }

    /**
     * @OA\Delete(
     *     path="/cart_items/{id}",
     *     summary="Delete a Cart_items",
     *     tags={"Cart_items"},
     *     security={{"bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *     description="your Cart_items ID you want to delete it",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart_items deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cart_item deleted successfully"),
     *     @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *    @OA\Response(
     *         response=403,
     *         description="forbidden error",
     *
     *         @OA\JsonContent(
     *
     *     @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this Cart_items ."),
     *     @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart_items not found",
     *
     *         @OA\JsonContent(
     *
     *@OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart_items not found"),
     *     @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(Cart_items $cart_item): JsonResponse
    {
        return $this->cart_items_Service->deleteCart_items($cart_item);
    }

    /**
     * @OA\Get(
     *     path="/cart_items/archive",
     *     summary="Get archived cart items grouped by deleted timestamp",
     *     tags={"Cart Items"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Page number",
     *
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Parameter(
     *          name="items",
     *          in="query",
     *          required=false,
     *          description="Number of items per page",
     *
     *          @OA\Schema(type="integer", example=5)
     *      ),
     *
     *      @OA\Parameter(
     *          name="direction",
     *          in="query",
     *          required=false,
     *          description="Sorting direction (asc or desc)",
     *
     *          @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *      ),
     *
     *      @OA\Parameter(
     *          name="column",
     *          in="query",
     *          required=false,
     *          description="Column to sort by (quantity,product_name)",
     *
     *          @OA\Schema(type="string", enum={"quantity,product_name"}, example="quantity,product_name")
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="archived Cart_items retrieved successfully!",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="archived Cart_items retrieved successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Cart_items",
     *                     type="object",
     *                     @OA\Property(
     *                         property="grouped_items",
     *                         type="array",
     *
     *                         @OA\Items(
     *                             type="object",
     *
     *                             @OA\Property(property="group_ordered_at", type="string", example="2025-05-22 15:59"),
     *                             @OA\Property(
     *                                 property="items",
     *                                 type="array",
     *
     *                                 @OA\Items(
     *                                     type="object",
     *
     *                                     @OA\Property(property="id", type="integer", example=11),
     *                                     @OA\Property(property="quantity", type="integer", example=10),
     *                                     @OA\Property(
     *                                         property="product",
     *                                         type="object",
     *                                         @OA\Property(property="id", type="integer", example=1),
     *                                         @OA\Property(property="name", type="string", example="Mills-Stokes"),
     *                                         @OA\Property(property="image", type="string", format="url", nullable=true, example="https://via.placeholder.com/640x480.png/00bbff?text=apple+sint"),
     *                                         @OA\Property(property="price", type="number", format="float", example=682.92),
     *                                         @OA\Property(property="isFavorite", type="integer", example=0)
     *                                     )
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="total_pages", type="integer", example=1),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="hasMorePages", type="boolean", example=false)
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart not found."),
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
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function archive(Request $request)
    {
        return $this->cart_items_Service->getDeletedCart_items($request);
    }
}
