<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductStock",
 *     required={"id", "name", "slug", "short_description", "description"},
 * )
 */
class ProductStockResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="stock", default="stock", description="stock", property="stock"),
     * @OA\Property(type="string", title="min_stock", default="min_stock", description="min_stock", property="min_stock"),
     * @OA\Property(type="string", title="price", default="price", description="price", property="price"),
     * @OA\Property(type="string", title="tax", default="tax", description="tax", property="tax"),
     * @OA\Property(type="string", title="sku", default="sku", description="sku", property="sku"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="product", ref="#/components/schemas/Product"),
     * 
     * @OA\Property(property="productAttributeOptions", type="array", @OA\Items(ref="#/components/schemas/ProductAttributeOption")),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'price' => $this->price,
            'tax' => $this->tax,
            'sku' => $this->sku,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('product') instanceof MissingValue) {
            $resource['product'] = new StatusResource($this->product);
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
}
