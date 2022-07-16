<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Resource",
 *     required={"id", "url", "type_resource"},
 * )
 */
class ResourceResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="url", default="url", description="url", property="url"),
     * @OA\Property(type="string", title="type_resource", default="type_resource", description="type_resource", property="type_resource"),
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'type_resource' => $this->type_resource,
        ];
    }
}
