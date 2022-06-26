<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class TagShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * Mostrar tag
     * 
     * Muestra la información de un tag por el id.
     * 
     * @group Tags
     * @apiResource App\Http\Resources\TagResource
     * @apiResourceModel App\Models\Tag with=status
     * 
     * @urlParam id int required Id del tag.
     */
    public function __invoke(Request $request, Tag $tag)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($tag->validByRole()) {
            return $this->showOne(
                $tag->loadEagerLoadIncludes($includes)
            );
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
