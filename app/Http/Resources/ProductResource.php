<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
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
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('category') instanceof MissingValue) {
            $resource['category'] = new CategoryResource($this->category);
        }

        if (!$this->whenLoaded('productSpecifications') instanceof MissingValue) {
            $resource['product_specifications'] =
                ProductSpecificationResource::collection($this->productSpecifications);
        }

        if (!$this->whenLoaded('tags') instanceof MissingValue) {
            $resource['tags'] = TagResource::collection($this->tags);
        }

        if (
            !$this->whenLoaded('productAttributeOptions') instanceof MissingValue
            && count($this->productAttributeOptions)
        ) {
            $resource['product_attribute_options'] = ProductAttributeOptionResource::collection(
                $this->productAttributeOptions
            );
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
