<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
use App\Models\Store\Store;
use Illuminate\Http\Exceptions\HttpResponseException;

trait AuthTrait
{
    public function checkOwnership($model, $modelType, $action, $relation = null, $relationName = null)
    {
        if ($relation) {
            if ($model->$relation()->exists()) {
                $unauthorizedDebts = $model->$relation()->where('user_id', '!=', auth()->id())->exists();
                if ($unauthorizedDebts) {
                    throw new HttpResponseException(ResponseHelper::jsonResponse([],
                        "You are not authorized to {$action} this {$modelType}. It has associated {$relationName}.",
                        403, false));
                }
            }
        } elseif ($model->user_id !== auth()->id()) {
            throw new HttpResponseException(ResponseHelper::jsonResponse([],
                "You are not authorized to {$action} this {$modelType}.",
                403, false));
        }
    }

    public function checkProduct($model, $modelType, $action)
    {
        if ($model->store->user_id !== auth()->id()) {
            throw new HttpResponseException(ResponseHelper::jsonResponse(
                [],
                "You are not authorized to {$action} this {$modelType}.This Product not in your Store ",
                403,
                false
            ));
        }
    }

    public function checkStoreID($storeId, $modelType, $action)
    {
        $store = Store::where('id', $storeId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $store) {
            throw new HttpResponseException(ResponseHelper::jsonResponse(
                [],
                "You are not authorized to {$action} this {$modelType}. This store does not belong to you.",
                403,
                false
            ));
        }
    }
}
