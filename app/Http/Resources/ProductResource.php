<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Product",
 *     required={
 *         "id",
 *         "type",
 *         "name",
 *         "slug",
 *         "sku",
 *         "price",
 *         "tax",
 *         "short_description",
 *         "description",
 *         "is_variable",
 *         "amount_viewed",
 *         "quantity_sold",
 *     },
 * )
 */
class ProductResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="number"),
     * @OA\Property(property="type", type="string"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="slug", type="string"),
     * @OA\Property(property="sku", type="string"),
     * @OA\Property(property="price", type="number"),
     * @OA\Property(property="tax", type="number"),
     * @OA\Property(property="short_description", type="string"),
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="is_variable", type="boolean"),
     * @OA\Property(property="amount_viewed", type="number"),
     * @OA\Property(property="quantity_sold", type="number"),
     * 
     * @OA\Property(property="stock", type="number"),
     * @OA\Property(property="width", type="number"),
     * @OA\Property(property="height", type="number"),
     * @OA\Property(property="length", type="number"),
     * @OA\Property(property="weight", type="number"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * @OA\Property(property="category", ref="#/components/schemas/Category"),
     * @OA\Property(
     *     property="images",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/Resource")
     * ),
     * @OA\Property(
     *     property="stock_images",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/Resource")
     * ),
     * @OA\Property(
     *     property="tags",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/Tag")
     * ),
     * @OA\Property(
     *     property="product_attribute_options",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/ProductAttributeOption")
     * ),
     * @OA\Property(
     *     property="product_stocks",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/ProductStock")
     * ),
     * @OA\Property(
     *     property="specifications",
     *     type="array", 
     *     @OA\Items(ref="#/components/schemas/ProductSpecification")
     * ),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->price,
            'tax' => $this->tax,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'is_variable' => $this->is_variable,
            'amount_viewed' => $this->amount_viewed,
            'quantity_sold' => $this->quantity_sold,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('category') instanceof MissingValue) {
            $resource['category'] = new CategoryResource($this->category);
        }

        if (!$this->whenLoaded('images') instanceof MissingValue) {
            $resource['images'] = ResourceResource::collection($this->images);
        }

        if (
            !$this->whenLoaded('stockImages') instanceof MissingValue
            && count($this->stockImages)
        ) {
            $resource['stock_images'] = ResourceResource::collection($this->stockImages);
        }

        if (!$this->whenLoaded('productSpecifications') instanceof MissingValue) {
            $resource['specifications'] =
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

        if (!$this->whenLoaded('productStocks') instanceof MissingValue) {
            $resource['product_stocks'] = ProductStockResource::collection($this->productStocks);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'category' => 'category_id',
            'type' => 'type',
            'name' => 'name',
            'price' => 'price',
            'tax' => 'tax',
            'slug' => 'slug',
            'description' => 'description',
            'is_variable' => 'is_variable',
            'amount_viewed' => 'amount_viewed',
            'quantity_sold' => 'quantity_sold',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
