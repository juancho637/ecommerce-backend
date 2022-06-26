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
     * Actualizar tag
     * 
     * Actualiza el tag indicada por el id.
     * 
     * @group Tags
     * @authenticated
     * @apiResource App\Http\Resources\TagResource
     * @apiResourceModel App\Models\Tag with=status
     * 
     * @urlParam id int required Id del tag.
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
            return $this->errorResponse($exception->getMessage());
        }
    }
}
