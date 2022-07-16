<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CityShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Mostrar ciudad
     * 
     * Muestra la información de una ciudad por el id.
     * 
     * @group Ciudades
     * @apiResource App\Http\Resources\CityResource
     * @apiResourceModel App\Models\City with=status,state
     * 
     * @urlParam id int required Id de la ciudad.
     */
    public function __invoke(Request $request, City $city)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($city->validByRole()) {
            return $this->showOne(
                $city->loadEagerLoadIncludes($includes)
            );
        }

        throw new ModelNotFoundException;
    }
}
