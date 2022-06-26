<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class StateDestroyController extends ApiController
{
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,state')->only('__invoke');
    }

    /**
     * Eliminar departamento/estado/provincia
     * 
     * Elimina un departamento/estado/provincia por el id.
     * 
     * @group Departamentos/Estados/Provincias
     * @authenticated
     * @apiResource App\Http\Resources\StateResource
     * @apiResourceModel App\Models\State with=status,country
     * 
     * @urlParam id int required Id del departamento/estado/provincia.
     */
    public function __invoke(Request $request, State $state)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->state = $state->setDelete();
            $this->state->save();
            DB::commit();

            return $this->showOne(
                $this->state->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
