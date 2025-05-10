<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category\Category;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

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
     *     path="/categories",
     *     summary="Get list of categories",
     *     description="Retrieve a paginated list of categories with optional sorting and pagination.",
     *     security={{"bearerAuth":{}}},
     *     tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="items",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         required=false,
     *         description="Sorting direction (asc or desc)",
     *
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *
     *     @OA\Parameter(
     *         name="column",
     *         in="query",
     *         required=false,
     *         description="Column to sort by (name, created_at, updated_at)",
     *
     *         @OA\Schema(type="string", enum={"name"}, example="name")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Categories retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                @OA\Property(property="Categories", type="array",
     *
     *                   @OA\Items(
     *                       type="object",
     *
     *                        @OA\Property(property="id", type="integer", example=3),
     *                       @OA\Property(property="name", type="string", example="blanditiis"),
     *                          @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/qRERai5pFIvaN2SfoItlFqRUjlPseUwWqdnoiSsd.png"),
     *                    )
     *                ),
     *                @OA\Property(property="total_pages", type="integer", example=4),
     *               @OA\Property(property="current_page", type="integer", example=1),
     *                @OA\Property(property="hasMorePages", type="boolean", example=true)
     *            ),
     *            @OA\Property(property="status_code", type="integer", example=200)
     *       )
     *    ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid sort column or direction. Allowed columns: name. Allowed directions: asc, desc.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid sort column or direction. Allowed columns: name, created_at, updated_at. Allowed directions: asc, desc."),
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
        return $this->categoryService->getAllCategories($request);
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get category and its stores",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a category with a paginated list of stores for that category.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         required=false,
     *         description="Column to sort by (name, address, created_at, updated_at)",
     *
     *         @OA\Schema(type="string", enum={"name", "address", "created_at", "updated_at"}, example="name")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_direction",
     *         in="query",
     *         required=false,
     *         description="Sorting direction (asc or desc)",
     *
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of stores per page",
     *
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Category and stores retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category retrieved successfully!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="ut"),
     *                          @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/qRERai5pFIvaN2SfoItlFqRUjlPseUwWqdnoiSsd.png"),
     *                     @OA\Property(property="stores", type="object",
     *                         @OA\Property(property="data", type="array",
     *
     *                             @OA\Items(
     *
     *                                 @OA\Property(property="id", type="integer", example=11),
     *                                 @OA\Property(property="name", type="string", example="Abo Bashir"),
     *                                 @OA\Property(property="image", type="string", nullable=true, example="http://127.0.0.1:8000/storage/images/E5eNfLzqAhGhTaUwTgDPhHJGRKTVxSX5iEU1weyd.png"),
     *                                 @OA\Property(property="address", type="string", example="Damascus")
     *                             )
     *                         ),
     *                         @OA\Property(property="pagination", type="object",
     *                             @OA\Property(property="total_pages", type="integer", example=1),
     *                             @OA\Property(property="current_page", type="integer", example=1),
     *                             @OA\Property(property="hasMorePages", type="boolean", example=false)
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found"),
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
     *     ),
     * )
     */
    public function show(Category $category): JsonResponse
    {
        return $this->categoryService->getCategoryById($category);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     security={{"bearerAuth":{}}},
     *     description="Creates a new category with an optional image upload.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name"},
     *
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Abo Bashir"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     nullable=true,
     *                     description="Image file for the category"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created successfully!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Category", type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="Abo Bashir"),
     *                          @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/qRERai5pFIvaN2SfoItlFqRUjlPseUwWqdnoiSsd.png"),
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="The name field is required.")
     *             ),
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
    public function store(CategoryCreateRequest $request): JsonResponse
    {
        return $this->categoryService->createCategory($request->validated(), $request);
    }

    /**
     * @OA\Post(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     security={{"bearerAuth":{}}},
     *     description="Update a category's name and optional image. Requires authentication.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *
     *         @OA\Schema(type="integer", example=12)
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
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Vegetables"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     nullable=true,
     *                     description="Optional image file for the category"
     *                 )
     *             )
     *         )
     *     ),

     *
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated successfully!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Category", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Vegetables"),
     *                          @OA\Property(property="image", type="string", example="http://127.0.0.1:8000/storage/images/qRERai5pFIvaN2SfoItlFqRUjlPseUwWqdnoiSsd.png"),
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized to update - category has associated stores",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="successful", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="You are not authorized to update this Category. It has associated Stores."),
     *              @OA\Property(property="status_code", type="integer", example=403)
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found"),
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
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        return $this->categoryService->updateCategory($category, $request->validated());
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     security={{"bearerAuth":{}}},
     *     description="Delete a category by its ID. Requires authentication.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category deleted successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     ),
     *
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized to delete - category has associated stores",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="successful", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="You are not authorized to delete this Category. It has associated Stores."),
     *              @OA\Property(property="status_code", type="integer", example=403)
     *          )
     *      ),
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
    public function destroy(Category $category): JsonResponse
    {
        return $this->categoryService->deleteCategory($category);
    }
}
