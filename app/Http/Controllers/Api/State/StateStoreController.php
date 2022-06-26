<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\State\StoreStateRequest;

class StateStoreController extends ApiController
{
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . State::class)->only('__invoke');
    }

    /**
     * Guardar departamento/estado/provincia
     * 
     * Guarda un departamento/estado/provincia en la aplicación.
     * 
     * @group Departamentos/Estados/Provincias
     * @authenticated
     * @apiResource App\Http\Resources\StateResource
     * @apiResourceModel App\Models\State with=status,country
     */
    public function __invoke(StoreStateRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->state = $this->state->create(
                $this->state->setCreate($request)
            );
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
