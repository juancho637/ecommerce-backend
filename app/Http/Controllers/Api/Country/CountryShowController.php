<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CountryShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/countries/{country}",
     *     summary="Show country by id",
     *     operationId="getCountryById",
     *     tags={"Countries"},
     *     @OA\Parameter(
     *         name="country",
     *         description="Id of country",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Country",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ModelNotFoundException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request, Country $country)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($country->validByRole()) {
            return $this->showOne(
                $country->loadEagerLoadIncludes($includes)
            );
        }

        throw new ModelNotFoundException;
    }
}
