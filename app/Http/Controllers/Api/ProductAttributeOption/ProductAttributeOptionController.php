<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttributeOption\StoreProductAttributeOptionRequest;
use App\Http\Requests\Api\ProductAttributeOption\UpdateProductAttributeOptionRequest;

class ProductAttributeOptionController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->authorizeResource(ProductAttributeOption::class, 'product_attribute_option');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productAttributeOptions = $this->productAttributeOption->query();
        $productAttributeOptions = $this->eagerLoadIncludes($productAttributeOptions, $includes)->get();

        return $this->showAll($productAttributeOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAttributeOptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->productAttributeOption = $this->productAttributeOption->create(
                $this->productAttributeOption->setCreate($request)
            );
            DB::commit();

            return $this->showOne($this->productAttributeOption);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductAttributeOption  $productAttributeOption
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttributeOption $productAttributeOption)
    {
        return $this->showOne($productAttributeOption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductAttributeOption  $productAttributeOption
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateProductAttributeOptionRequest $request,
        ProductAttributeOption $productAttributeOption
    ) {
        DB::beginTransaction();
        try {
            $this->productAttributeOption = $productAttributeOption->setUpdate($request);
            $this->productAttributeOption->save();
            DB::commit();

            return $this->showOne($this->productAttributeOption);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductAttributeOption  $productAttributeOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAttributeOption $productAttributeOption)
    {
        DB::beginTransaction();
        try {
            $this->productAttributeOption = $productAttributeOption->setDelete();
            $this->productAttributeOption->save();
            DB::commit();

            return $this->showOne($this->productAttributeOption);
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

        if (in_array('product_attribute', $includes)) {
            $query->with('productAttribute');
        }

        return $query;
    }
}
