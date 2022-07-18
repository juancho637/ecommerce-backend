<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Tag\UpdateTagRequest;

class TagUpdateController extends ApiController
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,tag')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{tag}",
     *     summary="Update tag",
     *     operationId="updateTag",
     *     tags={"Tags"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="tag",
     *         description="Id of tag",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UpdateTagRequest",
     *             )
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
     *         response="400",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/BadRequestException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthenticationException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthorizationException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ValidationException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(UpdateTagRequest $request, Tag $tag)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->tag = $tag->setUpdate($request);
            $this->tag->save();
            DB::commit();

            return $this->showOne(
                $this->tag->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
