<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductStock\ProductAttributeOpcionsOfStockRule;

/**
 * @OA\Schema(
 *     schema="StoreProductStockDTO",
 *     required={
 *         "stocks",
 *     },
 * )
 */
class StoreProductStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return count($this->product->productAttributeOptions) > 0;
    }

    /**
     * @OA\Property(
     *     property="stocks",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         required={
     *             "price",
     *             "product_attribute_options",
     *         }
     *         @OA\Property(property="price", type="number"),
     *         @OA\Property(
     *             property="product_attribute_options",
     *             type="array",
     *             @OA\Items(type="number"),
     *         ),
     *         @OA\Property(property="stock", type="number"),
     *         @OA\Property(property="width", type="number"),
     *         @OA\Property(property="height", type="number"),
     *         @OA\Property(property="length", type="number"),
     *         @OA\Property(property="weight", type="number"),
     *         @OA\Property(
     *             property="images",
     *             type="array",
     *             @OA\Items(type="number"),
     *         ),
     *     ),
     * ),
     */
    public function rules()
    {
        return [
            'stocks' => [
                'required',
                'array',
                'min:1',
            ],
            'stocks.*.price' => [
                'required',
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'stocks.*.product_attribute_options' => [
                'required',
                'array',
                'min:1',
                new ProductAttributeOpcionsOfStockRule($this->product)
            ],
            'stocks.*.product_attribute_options.*' => [
                'required',
                'exists:product_attribute_options,id'
            ],
            'stocks.*.images' => [
                'array',
                'max:' . ProductStock::MAX_IMAGES,
                'nullable',
            ],
            'stocks.*.images.*' => [
                'required',
                Rule::exists('resources', 'id')->where(function ($query) {
                    $query->where('obtainable_id', null);
                }),
            ],
            'stocks.*.stock' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'integer',
                'min:1',
            ],
            'stocks.*.width' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'stocks.*.height' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'stocks.*.length' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'stocks.*.weight' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'stocks.*.price.regex' => 'The price format must be between 0.00 and 9999999999.99',
            'stocks.*.width.regex' => 'The width format must be between 0.00 and 9999999999.99',
            'stocks.*.height.regex' => 'The height format must be between 0.00 and 9999999999.99',
            'stocks.*.length.regex' => 'The length format must be between 0.00 and 9999999999.99',
            'stocks.*.weight.regex' => 'The weight format must be between 0.00 and 9999999999.99',
        ];
    }
}
