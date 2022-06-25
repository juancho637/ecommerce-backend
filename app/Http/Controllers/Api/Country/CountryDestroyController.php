<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;

class CountryDestroyController extends ApiController
{
    private $country;

    public function __construct(Country $country)
    {
        $this->country = $country;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,country')->only('__invoke');
    }

    /**
     * Eliminar país
     * 
     * Elimina un país indicado por el id.
     * 
     * @group Países
     * @authenticated
     * @apiResource App\Http\Resources\CountryResource
     * @apiResourceModel App\Models\Country with=status
     * 
     * @urlParam id int required Id del país.
     */
    public function __invoke(Request $request, Country $country)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->country = $country->setDelete();
            $this->country->save();
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
