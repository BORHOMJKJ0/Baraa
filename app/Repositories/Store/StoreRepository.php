<?php

namespace App\Repositories\Store;

use App\Models\Store\Store;
use App\Traits\Lockable;

class StoreRepository
{
    use Lockable;

    public function getAll($items, $column, $direction)
    {
        return Store::getAllStores($items, $column, $direction);
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Store::create($data);
        });
    }

    public function update(Store $store, array $data)
    {
        return $this->lockForUpdate(Store::class, $store->id, function ($lockedStore) use ($data) {
            $lockedStore->update($data);

            return $lockedStore;
        });
    }

    public function delete(Store $store)
    {
        return $this->lockForDelete(Store::class, $store->id, function ($lockedStore) {
            return $lockedStore->delete();
        });
    }
}
