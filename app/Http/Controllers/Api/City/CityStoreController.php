<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\City\StoreCityRequest;

class CityStoreController extends ApiController
{
    private $city;

    public function __construct(City $city)
    {
        $this->city = $city;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . City::class)->only('__invoke');
    }

    /**
     * Guardar ciudad
     * 
     * Guarda una ciudad en la aplicación.
     * 
     * @group Ciudades
     * @authenticated
     * @apiResource App\Http\Resources\CityResource
     * @apiResourceModel App\Models\City with=status,state
     */
    public function __invoke(StoreCityRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->city = $this->city->create(
                $this->city->setCreate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->city->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
