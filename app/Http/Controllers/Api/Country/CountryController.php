<?php

namespace App\Http\Controllers\Api\Country;

use App\Models\Country;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Country\StoreRequest;
use App\Http\Requests\Api\Country\UpdateRequest;

class CountryController extends ApiController
{
    private $country;

    public function __construct(Country $country)
    {
        $this->country = $country;

        $this->middleware('auth:sanctum')->only([
            'store',
            'update',
            'destroy'
        ]);

        $this->middleware('can:create,' . Country::class)->only('store');
        $this->middleware('can:update,country')->only('update');
        $this->middleware('can:delete,country')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $countries = $this->country->query()->byRole();
        $countries = $this->eagerLoadIncludes($countries, $includes)->get();

        return $this->showAll($countries);
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
            $this->country = $this->country->create(
                $this->country->setCreate($storeRequest)
            );
            DB::commit();

            return $this->showOne($this->country);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        if ($country->validByRole()) {
            return $this->showOne($country);
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $updateRequest, Country $country)
    {
        DB::beginTransaction();
        try {
            $this->country = $country->setUpdate($updateRequest);
            $this->country->save();
            DB::commit();

            return $this->showOne($this->country);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        DB::beginTransaction();
        try {
            $this->country = $country->setDelete();
            $this->country->save();
            DB::commit();

            return $this->showOne($this->country);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        return $query;
    }
}
