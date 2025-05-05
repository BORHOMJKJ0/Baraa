<?php

namespace App\Services\Category;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category\Category;
use App\Repositories\Category\CategoryRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    use AuthTrait;

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(Request $request)
    {
        $items = $request->query('items', 20);
        $column = $request->query('column', 'name');
        $direction = $request->query('direction', 'asc');
        $validColumns = ['name', 'created_at', 'updated_at'];
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
        $categories = $this->categoryRepository->getAll($items, $column, $direction);

        $data = [
            'Categories' => CategoryResource::collection($categories),
            'total_pages' => $categories->lastPage(),
            'current_page' => $categories->currentPage(),
            'hasMorePages' => $categories->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'Categories retrieved successfully');
    }

    public function getCategoryById(Category $category)
    {
        $data = ['category' => CategoryResource::make($category)];

        return ResponseHelper::jsonResponse($data, 'Category retrieved successfully!');
    }

    public function createCategory(array $data, CategoryCreateRequest $request)
    {
        $path = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $data['image'] = $path;
        $category = $this->categoryRepository->create($data);
        $data = [
            'Category' => CategoryResource::make($category),
        ];

        return ResponseHelper::jsonResponse($data, 'Category created successfully!', 201);
    }

    public function updateCategory(Category $category, array $data)
    {
        try {
            if (isset($data['image'])) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $path = $data['image']->store('images', 'public');
                $data['image'] = $path;
            }
            $category = $this->categoryRepository->update($category, $data);
            $data = [
                'Category' => CategoryResource::make($category),
            ];

            $response = ResponseHelper::jsonResponse($data, 'Category updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteCategory(Category $category)
    {
        try {
            $this->checkOwnership($category, 'Category', 'delete', 'stores', 'Stores');
            $this->categoryRepository->delete($category);
            $response = ResponseHelper::jsonResponse([], 'Category deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}
