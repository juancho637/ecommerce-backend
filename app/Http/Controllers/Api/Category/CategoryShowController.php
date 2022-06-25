<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class CategoryShowController extends ApiController
{
    public function __construct()
    {
    }

    /**
     * Mostrar categoría
     * 
     * Muestra la información de una categoría por el id.
     * 
     * @group Categorías
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category with=image,status
     * 
     * @urlParam id int required Id de la categoría.
     */
    public function __invoke(Request $request, Category $category)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($category->validByRole()) {
            return $this->showOne(
                $category->loadEagerLoadIncludes($includes)
            );
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
