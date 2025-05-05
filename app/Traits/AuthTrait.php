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
        $hasProducts = Store::where('category_id', $model->id)->exists();

        $userHasStoreInCategory = Store::where('category_id', $model->id)
            ->where('user_id', auth()->id())
            ->exists();
        if ($action == 'delete' && $hasProducts) {
            $productNotOwnedByUser = Store::where('category_id', $model->id)->where('user_id', '!=', auth()->id())->exists();
            if ($productNotOwnedByUser) {
                throw new HttpResponseException(ResponseHelper::jsonResponse(
                    [],
                    "You are not authorized to {$action} this {$modelType}. This category have product from other users.",
                    403,
                    false
                ));
            }
        }
        if ($hasProducts && ! $userHasStoreInCategory && $action == 'update') {
            throw new HttpResponseException(ResponseHelper::jsonResponse(
                [],
                "You are not authorized to {$action} this {$modelType}. You must have products in this category.",
                403,
                false
            ));
        }
    }
}
