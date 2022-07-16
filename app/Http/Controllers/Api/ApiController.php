<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="E-commerce API Documentation",
     * ),
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST
     * )
     */
    public function __construct()
    {
        //
    }
}
