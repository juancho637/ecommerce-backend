<?php

namespace App\Http\Controllers\Api\City;

use App\Models\City;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\City\StoreRequest;
use App\Http\Requests\Api\City\UpdateRequest;

class CityController extends ApiController
{
    private $fractal;
    private $city;

    public function __construct(Manager $fractal, City $city)
    {
        $this->fractal = $fractal;
        $this->city = $city;

        $this->middleware('auth:sanctum')->only([
            'store',
            'update',
            'destroy'
        ]);

        $this->middleware('can:create,' . City::class)->only('store');
        $this->middleware('can:update,city')->only('update');
        $this->middleware('can:delete,city')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->fractal->parseIncludes($request->get('include', ''));

        $cities = $this->city->query()->byRole();
        $cities = $this->eagerLoadIncludes($cities)->get();

        return $this->showAll($cities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $storeRequest)
    {
        DB::beginTransaction();
        try {
            $this->city = $this->city->create(
                $this->city->setCreate($storeRequest)
            );
            DB::commit();

            return $this->showOne($this->city);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        if ($city->validByRole()) {
            return $this->showOne($city);
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $updateRequest, City $city)
    {
        DB::beginTransaction();
        try {
            $this->city = $city->setUpdate($updateRequest);
            $this->city->save();
            DB::commit();

            return $this->showOne($this->city);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        DB::beginTransaction();
        try {
            $this->city = $city->setDelete();
            $this->city->save();
            DB::commit();

            return $this->showOne($this->city);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    protected function eagerLoadIncludes(Builder $query)
    {
        $requestedIncludes = $this->fractal->getRequestedIncludes();

        if (in_array('status', $requestedIncludes)) {
            $query->with('status');
        }

        if (in_array('state', $requestedIncludes)) {
            $query->with('state');
        }

        return $query;
    }
}
