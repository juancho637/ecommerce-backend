<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;

class CategoryUpdateController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,category')->only('__invoke');
    }

    /**
     * Actualizar categoría
     * 
     * Actualiza la categoría indicada por el id.
     * 
     * @group Categorías
     * @authenticated
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category with=image,status
     * 
     * @urlParam id int required Id de la categoría.
     */
    public function __invoke(UpdateCategoryRequest $request, Category $category)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->category = $category->setUpdate($request);
            $this->category->save();

            $this->category->saveImage($request->image);
            DB::commit();

            return $this->showOne(
                $this->category->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
