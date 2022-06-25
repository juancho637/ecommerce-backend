<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Country\StoreCountryRequest;

class CountryStoreController extends ApiController
{
    private $country;

    public function __construct(Country $country)
    {
        $this->country = $country;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Country::class)->only('__invoke');
    }

    /**
     * Guardar país
     * 
     * Guarda una país en la aplicación.
     * 
     * @group Países
     * @authenticated
     * @apiResource App\Http\Resources\CountryResource
     * @apiResourceModel App\Models\Country with=status
     */
    public function __invoke(StoreCountryRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->country = $this->country->create(
                $this->country->setCreate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->country->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
