<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

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
     * @OA\Post(
     *     path="/carts",
     *     summary="Create a Cart",
     *     tags={"Carts"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Cart created successfully!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cart created successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Cart",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=11),
     *                         @OA\Property(property="name", type="string", example="Abood Sarhan")
     *                     ),
     *                     @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         example={},
     *
     *                         @OA\Items(
     *                             type="object",
     *
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Product A"),
     *                             @OA\Property(property="total_quantity", type="integer", example=3)
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="This User have already Cart"),
     *             @OA\Property(property="status_code", type="integer", example=403)
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
    public function store(): JsonResponse
    {
        return $this->cartService->createCart();
    }

    /**
     * @OA\Put(
     *     path="/carts",
     *     summary="Update a cart",
     *     tags={"Carts"},
     *    description="place order the items",
     *     security={{"bearerAuth": {}}},
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
     *          response=200,
     *         description="Cart updated successfully!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cart updated successfully!"),
     *            @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="Cart",
     *                      type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(
     *                          property="user",
     *                          type="object",
     *                          @OA\Property(property="id", type="integer", example=11),
     *                          @OA\Property(property="name", type="string", example="Abood Sarhan")
     *                      ),
     *                      @OA\Property(
     *                          property="items",
     *                          type="array",
     *                          example={},
     *
     *                          @OA\Items(
     *                              type="object",
     *
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="name", type="string", example="Product A"),
     *                              @OA\Property(property="total_quantity", type="integer", example=3)
     *                          )
     *                     )
     *                  )
     *              ),
     *              @OA\Property(property="status_code", type="integer", example=200)
     *          )
     *      ),
     *
     *      * @OA\Response(
     *     response=400,
     *     description="Insufficient product quantity to fulfill order",
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="successful", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Some products do not have sufficient quantity to fulfill your order."),
     *         @OA\Property(
     *             property="data",
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="product_id", type="integer", example=2),
     *                 @OA\Property(property="product_name", type="string", example="Lind-Macejkovic"),
     *                 @OA\Property(property="available_amount", type="integer", example=3),
     *                 @OA\Property(property="requested_quantity", type="integer", example=200),
     *                 @OA\Property(
     *                     property="cart_item_ids",
     *                     type="array",
     *
     *                     @OA\Items(type="integer", example=19)
     *                 )
     *             )
     *         ),
     *
     *         @OA\Property(property="status_code", type="integer", example=400)
     *     )
     * ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found",
     *
     *         @OA\JsonContent(
     *
     *    @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart not found"),
     *            @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *
     *           @OA\JsonContent(
     *
     *               @OA\Property(property="successful", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Unauthenticated"),
     *               @OA\Property(property="status_code", type="integer", example=401)
     *           )
     *       )
     * )
     */
    public function update(): JsonResponse
    {
        return $this->cartService->updateCart();
    }

    /**
     * @OA\Get(
     *     path="/carts",
     *     summary="Get a single cart by ID",
     *     tags={"Carts"},
     *     security={{"bearerAuth": {}}},
     *
     * @OA\Response(
     *     response=200,
     *     description="Cart retrieved successfully!",
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="successful", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Cart retrieved successfully!"),
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             @OA\Property(
     *                 property="Cart",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=6),
     *                 @OA\Property(property="total_price", type="number", format="float", example=348734.4),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(
     *                             property="product",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Lind-Macejkovic"),
     *                             @OA\Property(property="image", type="string", nullable=true, example=null),
     *                             @OA\Property(property="price", type="number", format="float", example=558.46),
     *                             @OA\Property(property="isFavorite", type="integer", example=0)
     *                         ),
     *                         @OA\Property(property="total_quantity", type="integer", example=600),
     *                         @OA\Property(
     *                             property="cart_items",
     *                             type="array",
     *
     *                             @OA\Items(
     *                                 type="object",
     *
     *                                 @OA\Property(property="id", type="integer", example=19),
     *                                 @OA\Property(property="quantity", type="integer", example=200)
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         ),
     *         @OA\Property(property="status_code", type="integer", example=200)
     *     )
     * ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found",
     *
     *         @OA\JsonContent(
     *
     *    @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart not found"),
     *            @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *
     *           @OA\JsonContent(
     *
     *               @OA\Property(property="successful", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Unauthenticated"),
     *               @OA\Property(property="status_code", type="integer", example=401)
     *           )
     *       )
     * )
     */
    public function show(): JsonResponse
    {
        return $this->cartService->getCartById();
    }

    /**
     * @OA\Delete(
     *     path="/carts",
     *     summary="Delete a Cart",
     *     tags={"Carts"},
     *     security={{"bearerAuth": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *    @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cart deleted successfully"),
     *            @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found",
     *
     *         @OA\JsonContent(
     *
     *    @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart not found"),
     *          @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *
     *           @OA\JsonContent(
     *
     *               @OA\Property(property="successful", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Unauthenticated"),
     *               @OA\Property(property="status_code", type="integer", example=401)
     *           )
     *       )
     * )
     */
    public function destroy(): JsonResponse
    {
        return $this->cartService->deleteCart();
    }
}
