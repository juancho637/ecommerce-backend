<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductAttribute",
 *     required={"id", "name", "option"},
 * )
 */
class ProductAttributeResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="number"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="type", type="string"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(
     *     property="product_attribute_options",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/ProductAttributeOption")
     * ),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
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
            'name' => 'name',
            'type' => 'type',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
