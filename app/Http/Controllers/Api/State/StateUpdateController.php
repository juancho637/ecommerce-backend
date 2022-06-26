<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\State\UpdateStateRequest;

class StateUpdateController extends ApiController
{
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,state')->only('__invoke');
    }

    /**
     * Actualizar departamento/estado/provincia
     * 
     * Actualiza el departamento/estado/provincia indicada por el id.
     * 
     * @group Departamentos/Estados/Provincias
     * @authenticated
     * @apiResource App\Http\Resources\StateResource
     * @apiResourceModel App\Models\State with=status,country
     * 
     * @urlParam id int required Id del departamento/estado/provincia.
     */
    public function __invoke(UpdateStateRequest $request, State $state)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->state = $state->setUpdate($request);
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
