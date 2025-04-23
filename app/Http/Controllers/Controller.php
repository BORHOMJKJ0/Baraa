<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Baraa API",
 *      description="API documentation for the Baraa",
 *
 *      @OA\Contact(
 *          email="support@yourapi.com"
 *      ),
 *
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
