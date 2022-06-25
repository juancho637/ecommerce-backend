<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class CityIndexController extends ApiController
{
    private $city;

    public function __construct(City $city)
    {
        $this->city = $city;

        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Listar ciudades
     * 
     * Lista las ciudades de la aplicación.
     * 
     * @group Ciudades
     * @apiResourceCollection App\Http\Resources\CityResource
     * @apiResourceModel App\Models\City with=status,state
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $cities = $this->city->query()->byRole();
        $cities = $this->eagerLoadIncludes($cities, $includes)->get();

        return $this->showAll($cities);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('state', $includes)) {
            $query->with('state');
        }

        return $query;
    }
}
