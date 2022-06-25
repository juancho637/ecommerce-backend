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
     * Listar países
     * 
     * Lista los países de la aplicación.
     * 
     * @group Países
     * @apiResourceCollection App\Http\Resources\CountryResource
     * @apiResourceModel App\Models\Country with=status
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
