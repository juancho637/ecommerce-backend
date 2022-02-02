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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
