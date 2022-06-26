<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class TagDestroyController extends ApiController
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,tag')->only('__invoke');
    }

    /**
     * Eliminar tag
     * 
     * Elimina un tag por el id.
     * 
     * @group Tags
     * @authenticated
     * @apiResource App\Http\Resources\TagResource
     * @apiResourceModel App\Models\Tag with=status
     * 
     * @urlParam id int required Id del tag.
     */
    public function __invoke(Request $request, Tag $tag)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->tag = $tag->setDelete();
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
