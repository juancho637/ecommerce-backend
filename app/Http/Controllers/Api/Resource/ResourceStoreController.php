<?php

namespace App\Http\Controllers\Api\Resource;

use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use App\Actions\Resource\StoreResource;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Resource\StoreResourceRequest;

class ResourceStoreController extends ApiController
{
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Resource::class)->only('__invoke');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/resources",
     *     summary="Save resource",
     *     description="<strong>Method:</strong> saveResource",
     *     operationId="saveResource",
     *     tags={"Resources"},
     *     security={ {"sanctum": {}} },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreResourceRequest",
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
     *                 ref="#/components/schemas/Resource",
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
    public function __invoke(StoreResourceRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->resource = app(StoreResource::class)(
                $this->resource->setCreate($request)
            );
            DB::commit();

            return $this->showOne($this->resource);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
