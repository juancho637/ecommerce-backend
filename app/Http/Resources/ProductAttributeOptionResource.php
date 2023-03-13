<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductAttributeOption",
 *     required={"id", "name"},
 * )
 */
class ProductAttributeOptionResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="number"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="option", type="string"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="product_attribute", ref="#/components/schemas/ProductAttribute"),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,            
        ];

        if ($this->option) {
            $resource['option'] = $this->option;
        }

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
