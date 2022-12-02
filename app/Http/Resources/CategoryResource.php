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
     * @OA\Property(property="id", type="number"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="slug", type="string"),
     * @OA\Property(property="parent_id", type="number"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="image", ref="#/components/schemas/Resource"),
     * @OA\Property(
     *     property="children", 
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/Category")
     * ),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
        ];

        if (!$this->whenLoaded('image') instanceof MissingValue) {
            $resource['image'] = new ResourceResource($this->image);
        }

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (
            !$this->whenLoaded('children') instanceof MissingValue
            && count($this->children)
        ) {
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
