<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Category\StoreCategoryRequest;

class CategoryStoreController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Category::class)->only('__invoke');
    }

    /**
     * Guardar categoría
     * 
     * Guarda una categoría en la aplicación.
     * 
     * @group Categorías
     * @authenticated
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category with=image,status
     */
    public function __invoke(StoreCategoryRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->category = $this->category->create(
                $this->category->setCreate($request)
            );
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
