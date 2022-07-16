<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Category",
 *     required={"id", "name", "slug"},
 * )
 */
class CategoryResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="slug", default="slug", description="slug", property="slug"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="image", ref="#/components/schemas/Resource"),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];

        if (!$this->whenLoaded('image') instanceof MissingValue) {
            $resource['image'] = new ResourceResource($this->image);
        }

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if ($this->children && count($this->children)) {
            $resource['children'] = CategoryResource::collection($this->children);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'name' => 'name',
            'slug' => 'slug',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
