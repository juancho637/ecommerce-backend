<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class CountryShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Mostrar país
     * 
     * Muestra la información de un país indicado por el id.
     * 
     * @group Países
     * @apiResource App\Http\Resources\CountryResource
     * @apiResourceModel App\Models\Country with=status
     * 
     * @urlParam id int required Id del país.
     */
    public function __invoke(Request $request, Country $country)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($country->validByRole()) {
            return $this->showOne(
                $country->loadEagerLoadIncludes($includes)
            );
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
