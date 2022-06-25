<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class CategoryDestroyController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,category')->only('__invoke');
    }

    /**
     * Eliminar categoría
     * 
     * Elimina una categoría por el id.
     * 
     * @group Categorías
     * @authenticated
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category with=image,status
     * 
     * @urlParam id int required Id de la categoría.
     */
    public function __invoke(Request $request, Category $category)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->category = $category->setDelete();
            $this->category->save();
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
