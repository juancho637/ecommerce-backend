<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductSpecification",
 *     required={"id", "name", "value"},
 * )
 */
class ProductSpecificationResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="value", default="value", description="value", property="value"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="product", ref="#/components/schemas/Product"),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('product') instanceof MissingValue) {
            $resource['product'] = new ProductResource($this->product);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'product' => 'product_id',
            'name' => 'name',
            'product' => 'product',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
