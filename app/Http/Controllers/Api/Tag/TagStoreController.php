<?php

namespace App\Http\Controllers\Api\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Tag\StoreTagRequest;

class TagStoreController extends ApiController
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Tag::class)->only('__invoke');
    }

    /**
     * Guardar tag
     * 
     * Guarda un tag en la aplicación.
     * 
     * @group Tags
     * @authenticated
     * @apiResource App\Http\Resources\TagResource
     * @apiResourceModel App\Models\Tag with=status
     */
    public function __invoke(StoreTagRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->tag = $this->tag->create(
                $this->tag->setCreate($request)
            );
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
