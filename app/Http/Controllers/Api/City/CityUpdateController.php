<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\City\UpdateCityRequest;

class CityUpdateController extends ApiController
{
    private $city;

    public function __construct(City $city)
    {
        $this->city = $city;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,city')->only('__invoke');
    }

    /**
     * Actualizar ciudad
     * 
     * Actualiza la ciudad indicada por el id.
     * 
     * @group Ciudades
     * @authenticated
     * @apiResource App\Http\Resources\CityResource
     * @apiResourceModel App\Models\City with=status,state
     * 
     * @urlParam id int required Id de la ciudad.
     */
    public function __invoke(UpdateCityRequest $request, City $city)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->city = $city->setUpdate($request);
            $this->city->save();
            DB::commit();

            return $this->showOne(
                $this->city->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
