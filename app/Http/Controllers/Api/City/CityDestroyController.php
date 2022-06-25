<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class CityDestroyController extends ApiController
{
    private $city;

    public function __construct(City $city)
    {
        $this->city = $city;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,city')->only('__invoke');
    }

    /**
     * Eliminar ciudad
     * 
     * Elimina una ciudad por el id.
     * 
     * @group Ciudades
     * @authenticated
     * @apiResource App\Http\Resources\CityResource
     * @apiResourceModel App\Models\City with=status,state
     * 
     * @urlParam id int required Id de la ciudad.
     */
    public function __invoke(Request $request, City $city)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->city = $city->setDelete();
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
