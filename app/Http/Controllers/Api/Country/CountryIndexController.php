<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class CountryIndexController extends ApiController
{
    private $country;

    public function __construct(Country $country)
    {
        $this->country = $country;

        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/countries",
     *     summary="List of countries",
     *     operationId="getAllCountries",
     *     tags={"Countries"},
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/Country")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $countries = $this->country->query()->byRole();
        $countries = $this->eagerLoadIncludes($countries, $includes)->get();

        return $this->showAll($countries);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        return $query;
    }
}
