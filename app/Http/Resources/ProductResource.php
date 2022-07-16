<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "slug", "short_description", "description"},
 * )
 */
class ProductResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="slug", default="slug", description="slug", property="slug"),
     * @OA\Property(type="string", title="short_description", default="short_description", description="short_description", property="short_description"),
     * @OA\Property(type="string", title="description", default="description", description="description", property="description"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="category", ref="#/components/schemas/Category"),
     * 
     * @OA\Property(property="photos", type="array", @OA\Items(ref="#/components/schemas/Resource")),
     * @OA\Property(property="productSpecifications", type="array", @OA\Items(ref="#/components/schemas/ProductSpecification")),
     * @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/Tag")),
     * @OA\Property(property="productAttributeOptions", type="array", @OA\Items(ref="#/components/schemas/ProductAttributeOption")),
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

        if (!$this->whenLoaded('photos') instanceof MissingValue) {
            $resource['photos'] = ResourceResource::collection($this->photos);
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
