<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class TagIndexController extends ApiController
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;

        // $this->middleware('auth:sanctum');

        // $this->middleware('can:')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     summary="List of tags",
     *     operationId="getAllTags",
     *     tags={"Tags"},
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/Tag")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $tags = $this->tag->query()->byRole();
        $tags = $this->eagerLoadIncludes($tags, $includes)->get();

        return $this->showAll($tags);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        return $query;
    }
}
