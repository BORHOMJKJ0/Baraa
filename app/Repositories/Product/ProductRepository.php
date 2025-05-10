<?php

namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Traits\Lockable;

class ProductRepository
{
    use Lockable;

    public function getAll($items, $column, $direction)
    {
        return (new Product)->getAllProducts($items, $column, $direction);
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Product::create($data);
        });
    }

    public function update(Product $product, array $data)
    {
        return $this->lockForUpdate(Product::class, $product->id, function ($lockedProduct) use ($data) {
            $lockedProduct->update($data);

            return $lockedProduct;
        });
    }

    public function delete(Product $product)
    {
        return $this->lockForDelete(Product::class, $product->id, function ($lockedProduct) {
            return $lockedProduct->delete();
        });
    }
}
