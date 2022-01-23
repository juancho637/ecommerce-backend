<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductSpecification\StoreProductSpecificationRequest;
use App\Http\Requests\Api\ProductSpecification\UpdateProductSpecificationRequest;

class ProductSpecificationController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->authorizeResource(ProductSpecification::class, 'product_specification');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productSpecifications = $this->productSpecification->query();
        $productSpecifications = $this->eagerLoadIncludes($productSpecifications, $includes)->get();

        return $this->showAll($productSpecifications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductSpecificationRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->productSpecification = $this->productSpecification->create(
                $this->productSpecification->setCreate($request)
            );
            DB::commit();

            return $this->showOne($this->productSpecification);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductSpecification  $productSpecification
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSpecification $productSpecification)
    {
        return $this->showOne($productSpecification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductSpecification  $productSpecification
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateProductSpecificationRequest $request,
        ProductSpecification $productSpecification
    ) {
        DB::beginTransaction();
        try {
            $this->productSpecification = $productSpecification->setUpdate($request);
            $this->productSpecification->save();
            DB::commit();

            return $this->showOne($this->productSpecification);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductSpecification  $productSpecification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSpecification $productSpecification)
    {
        DB::beginTransaction();
        try {
            $this->productSpecification = $productSpecification->setDelete();
            $this->productSpecification->save();
            DB::commit();

            return $this->showOne($this->productSpecification);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('product', $includes)) {
            $query->with('product');
        }

        return $query;
    }
}
