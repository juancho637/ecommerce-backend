<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class StateIndexController extends ApiController
{
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;

        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Listar departamentos/estados/provincias
     * 
     * Lista los departamentos/estados/provincias de la aplicación.
     * 
     * @group Departamentos/Estados/Provincias
     * @apiResourceCollection App\Http\Resources\StateResource
     * @apiResourceModel App\Models\State with=status,country
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $states = $this->state->query()->byRole();
        $states = $this->eagerLoadIncludes($states, $includes)->get();

        return $this->showAll($states);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('country', $includes)) {
            $query->with('country');
        }

        return $query;
    }
}
