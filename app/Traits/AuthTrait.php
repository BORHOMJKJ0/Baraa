<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
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
}
