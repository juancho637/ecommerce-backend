<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductStock",
 *     required={
 *         "id",
 *         "price",
 *         "sku",
 *     },
 * )
 */
class ProductStockResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="number"),
     * @OA\Property(property="price", type="string"),
     * @OA\Property(property="sku", type="string"),
     * @OA\Property(property="stock", type="number"),
     * @OA\Property(property="width", type="number"),
     * @OA\Property(property="height", type="number"),
     * @OA\Property(property="length", type="number"),
     * @OA\Property(property="weight", type="number"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="product", ref="#/components/schemas/Product"),
     * @OA\Property(
     *     property="productAttributeOptions",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ProductAttributeOption")
     * ),
     * @OA\Property(
     *     property="images",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/Resource")
     * ),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'price' => $this->price,
            'sku' => $this->sku,
        ];

        !$this->stock ?: $resource['stock'] = $this->stock;
        !$this->width ?: $resource['width'] = $this->width;
        !$this->height ?: $resource['height'] = $this->height;
        !$this->length ?: $resource['length'] = $this->length;
        !$this->weight ?: $resource['weight'] = $this->weight;

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('product') instanceof MissingValue) {
            $resource['product'] = new ProductResource($this->product);
        }

        if (
            !$this->whenLoaded('productAttributeOptions') instanceof MissingValue
            && count($this->productAttributeOptions)
        ) {
            $resource['product_attribute_options'] = ProductAttributeOptionResource::collection(
                $this->productAttributeOptions
            );
        }

        if (
            !$this->whenLoaded('images') instanceof MissingValue
            && count($this->images)
        ) {
            $resource['images'] = ResourceResource::collection($this->images);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'price' => 'price',
            'sku' => 'sku',
            'stock' => 'stock',
            'width' => 'width',
            'height' => 'height',
            'length' => 'length',
            'weight' => 'weight',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
