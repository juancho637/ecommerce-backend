<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductAttributeOption",
 *     required={"id", "name", "option"},
 * )
 */
class ProductAttributeOptionResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="option", default="option", description="option", property="option"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="productAttribute", ref="#/components/schemas/ProductAttribute"),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'option' => $this->option,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('productAttribute') instanceof MissingValue) {
            $resource['product_attribute'] = new ProductAttributeResource($this->productAttribute);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'product_attribute' => 'product_attribute_id',
            'name' => 'name',
            'option' => 'option',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
