<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class StateShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Mostrar departamento/estado/provincia
     * 
     * Muestra la información de un departamento/estado/provincia por el id.
     * 
     * @group Departamentos/Estados/Provincias
     * @apiResource App\Http\Resources\StateResource
     * @apiResourceModel App\Models\State with=status,country
     * 
     * @urlParam id int required Id del departamento/estado/provincia.
     */
    public function __invoke(Request $request, State $state)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($state->validByRole()) {
            return $this->showOne(
                $state->loadEagerLoadIncludes($includes)
            );
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
