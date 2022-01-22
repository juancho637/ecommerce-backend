<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttribute\StoreProductAttributeRequest;
use App\Http\Requests\Api\ProductAttribute\UpdateProductAttributeRequest;

class ProductAttributeController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->authorizeResource(ProductAttribute::class, 'product_attribute');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productAttributes = $this->productAttribute->query();
        $productAttributes = $this->eagerLoadIncludes($productAttributes, $includes)->get();

        return $this->showAll($productAttributes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAttributeRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->productAttribute = $this->productAttribute->create(
                $this->productAttribute->setCreate($request)
            );
            DB::commit();

            return $this->showOne($this->productAttribute);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttribute $productAttribute)
    {
        return $this->showOne($productAttribute);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        DB::beginTransaction();
        try {
            $this->productAttribute = $productAttribute->setUpdate($request);
            $this->productAttribute->save();
            DB::commit();

            return $this->showOne($this->productAttribute);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAttribute $productAttribute)
    {
        DB::beginTransaction();
        try {
            $this->productAttribute = $productAttribute->setDelete();
            $this->productAttribute->save();
            DB::commit();

            return $this->showOne($this->productAttribute);
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

        return $query;
    }
}
