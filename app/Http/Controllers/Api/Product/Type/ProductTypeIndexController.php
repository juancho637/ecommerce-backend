<?php

namespace App\Http\Controllers\Api\Product\Type;

use App\Models\Product;
use App\Http\Controllers\Api\ApiController;

class ProductTypeIndexController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        // $this->middleware('can:view-any,' . Product::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/types",
     *     summary="List of product types",
     *     description="<strong>Method:</strong> getAllProductTypes",
     *     operationId="getAllProductTypes",
     *     tags={"Products"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="lang",
     *         description="Code of language",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke()
    {
        $types = [];

        foreach (Product::TYPES as $type) {
            $types[] = [
                "name" => $type,
            ];
        }

        return $this->jsonResponse([
            'data' => $types
        ], 200);
    }
}
