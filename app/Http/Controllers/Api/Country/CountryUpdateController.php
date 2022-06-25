<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Country\UpdateCountryRequest;

class CountryUpdateController extends ApiController
{
    private $country;

    public function __construct(Country $country)
    {
        $this->country = $country;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,country')->only('__invoke');
    }

    /**
     * Actualizar país
     * 
     * Actualiza el país indicado por el id.
     * 
     * @group Países
     * @authenticated
     * @apiResource App\Http\Resources\CountryResource
     * @apiResourceModel App\Models\Country with=status
     * 
     * @urlParam id int required Id del país.
     */
    public function __invoke(UpdateCountryRequest $request, Country $country)
    {
        DB::beginTransaction();
        try {
            $this->country = $country->setUpdate($request);
            $this->country->save();
            DB::commit();

            return $this->showOne($this->country);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
