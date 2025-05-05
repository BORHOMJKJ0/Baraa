<?php

namespace App\Repositories\Category;

use App\Models\Category\Category;
use App\Traits\Lockable;

class CategoryRepository
{
    use Lockable;

    public function getAll($items, $column, $direction)
    {
        return (new Category)->getAllCategories($items, $column, $direction);
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Category::create($data);
        });
    }

    public function update(Category $category, array $data)
    {
        return $this->lockForUpdate(Category::class, $category->id, function ($lockedCategory) use ($data) {
            $lockedCategory->update($data);

            return $lockedCategory;
        });
    }

    public function delete(Category $category)
    {
        return $this->lockForDelete(Category::class, $category->id, function ($lockedCategory) {
            return $lockedCategory->delete();
        });
    }
}
