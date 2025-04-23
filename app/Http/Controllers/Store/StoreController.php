<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreCreateRequest;
use App\Http\Requests\Store\StoreUpdateRequest;
use App\Models\Store\Store;
use App\Services\Store\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(protected StoreService $storeService) {}
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
     *     path="/stores",
     *     tags={"Store"},
     *     summary="Retrieve all stores with pagination and sorting",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="items",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         required=false,
     *         description="Sorting direction (asc or desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *     @OA\Parameter(
     *         name="column",
     *         in="query",
     *         required=false,
     *         description="Column to sort by (name, address, created_at, updated_at)",
     *         @OA\Schema(type="string", enum={"name", "address", "created_at", "updated_at"}, example="name")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stores retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Stores retrieved successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Stores",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="name", type="string", example="Dietrich and Sons"),
     *                         @OA\Property(property="image", type="string", example="https://via.placeholder.com/640x480.png/0033ff?text=technics+sit"),
     *                         @OA\Property(property="address", type="string", example="21464 Ortiz Pines\nEast Ethelton, MO 97340"),
     *                         @OA\Property(property="user", type="string", example="Miguel Douglas")
     *                     )
     *                 ),
     *                 @OA\Property(property="total_pages", type="integer", example=3),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="hasMorePages", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid sort column or direction. Allowed columns: name, address, created_at, updated_at. Allowed directions: asc, desc.",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid sort column or direction. Allowed columns: name, address, created_at, updated_at. Allowed directions: asc, desc."),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *  )
     *
     */

    public function index(Request $request): JsonResponse
    {
        return $this->storeService->getAllStores($request);
    }
    /**
     * @OA\Post(
     *     path="/stores",
     *     tags={"Store"},
     *     summary="Create a new store",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "address"},
     *                 @OA\Property(property="name", type="string", example="Abo Bashir"),
     *                 @OA\Property(property="address", type="string", example="Damascus"),
     *                 @OA\Property(property="image", type="string", format="binary", nullable=true, example="Rp6d5o65aGlNo95BedbGJdC9whgIEfp6U2zw0Ck1.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Store created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Store created successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=201),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Store",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=13),
     *                     @OA\Property(property="name", type="string", example="Abo Bashir"),
     *                     @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/Rp6d5o65aGlNo95BedbGJdC9whgIEfp6U2zw0Ck1.png"),
     *                     @OA\Property(property="address", type="string", example="Damascus"),
     *                     @OA\Property(property="user", type="string", example="Abood Sarhan")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="status_code", type="integer", example=400),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="The name field is required."),
     *                 @OA\Property(property="address", type="string", example="The address field is required.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *          @OA\Response(
     *          response=404,
     *          description="Store Not Found",
     *          @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Store Not Found"),
     *              @OA\Property(property="status_code", type="integer", example=404)
     *          )
     *      )
     * )
     */

    public function store(StoreCreateRequest $request): JsonResponse
    {
        return $this->storeService->createStore($request->validated(), $request);
    }
    /**
     * @OA\Get(
     *     path="/api/stores/{id}",
     *     tags={"Store"},
     *     summary="Retrieve a specific store by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the store to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer" , example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Store retrieved successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Store",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="Dietrich and Sons"),
     *                     @OA\Property(property="image", type="string", example="https://via.placeholder.com/640x480.png/0033ff?text=technics+sit"),
     *                     @OA\Property(property="address", type="string", example="21464 Ortiz Pines\nEast Ethelton, MO 97340"),
     *                     @OA\Property(property="user", type="string", example="Miguel Douglas")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */

    public function show(Store $store): JsonResponse
    {
        return $this->storeService->getStoreById($store);
    }
    /**
     * @OA\Put(
     *     path="/stores/{id}",
     *     tags={"Store"},
     *     summary="Update an existing store",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the store to be updated",
     *         required=true,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Osuama"),
     *                 @OA\Property(property="address", type="string", example="Midan"),
     *                 @OA\Property(property="image", type="string", format="binary", nullable=true, example="new_image.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Store updated successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Store",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Osuama"),
     *                     @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/qRERai5pFIvaN2SfoItlFqRUjlPseUwWqdnoiSsd.png"),
     *                     @OA\Property(property="address", type="string", example="Midan"),
     *                     @OA\Property(property="user", type="string", example="Abood Sarhan")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized to update this Store",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this Store."),
     *             @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Store Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function update(StoreUpdateRequest $request, Store $store): JsonResponse
    {
        return $this->storeService->updateStore($store, $request->validated());
    }
    /**
     * @OA\Delete(
     *     path="/stores/{id}",
     *     tags={"Store"},
     *     summary="Delete a store",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the store to be deleted",
     *         required=true,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Store deleted successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized to delete this Store",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this Store."),
     *             @OA\Property(property="status_code", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Store Not Found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */

    public function destroy(Store $store): JsonResponse
    {
        return $this->storeService->deleteStore($store);
    }
}
