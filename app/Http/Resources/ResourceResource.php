<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Resource",
 *     required={
 *         "id",
 *         "owner_id",
 *         "type_resource",
 *     },
 * ),
 * @OA\Schema(
 *     schema="ResourceUrls",
 *     required={"original"},
 *     @OA\Property(property="original", type="string"),
 *     @OA\Property(property="thumb", type="string"),
 *     @OA\Property(property="small", type="string"),
 *     @OA\Property(property="medium", type="string"),
 * ),
 */
class ResourceResource extends JsonResource
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="number",
     * ),
     * @OA\Property(
     *     property="owner_id",
     *     type="number",
     * ),
     * @OA\Property(
     *     property="type_resource",
     *     type="string",
     * ),
     * @OA\Property(
     *     property="urls",
     *     ref="#/components/schemas/ResourceUrls",
     * ),
     * @OA\Property(
     *     property="options",
     *     type="object",
     * ),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'owner_id' => $this->obtainable_id,
            'type_resource' => $this->type_resource,
        ];

        !$this->url ?: $resource['urls'] = $this->url;
        !$this->options ?: $resource['options'] = $this->options;

        return $resource;
    }
}
