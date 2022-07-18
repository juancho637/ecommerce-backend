<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagShowController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{tag}",
     *     summary="Show tag by id",
     *     operationId="getTagById",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="tag",
     *         description="Id of tag",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Tag",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ModelNotFoundException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request, Tag $tag)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($tag->validByRole()) {
            return $this->showOne(
                $tag->loadEagerLoadIncludes($includes)
            );
        }

        throw new ModelNotFoundException;
    }
}
