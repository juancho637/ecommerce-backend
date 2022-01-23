<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $includes = explode(',', $request->get('include'));

        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
        ];

        if (in_array('status', $includes)) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (in_array('category', $includes)) {
            $resource['category'] = new CategoryResource($this->category);
        }

        if (in_array('tags', $includes)) {
            $resource['tags'] = TagResource::collection($this->tags);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'category' => 'category_id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
