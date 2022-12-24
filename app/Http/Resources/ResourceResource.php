<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Resource",
 *     required={
 *         "id",
 *         "urls",
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
            'urls' => $this->url,
        ];

        !$this->obtainable_id ?: $resource['owner_id'] = $this->obtainable_id;
        !$this->type_resource ?: $resource['type_resource'] = $this->type_resource;
        !$this->options ?: $resource['options'] = $this->options;

        return $resource;
    }
}
