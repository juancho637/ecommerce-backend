<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class CategoryIndexController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $categories = $this->category->query()->byRole();
        $categories = $this->eagerLoadIncludes($categories, $includes)
            ->with('image')
            ->get();

        $rootCategories = $categories->whereNull('parent_id');

        Category::formatTree($rootCategories, $categories);

        return $this->showAll($categories);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        return $query;
    }
}
