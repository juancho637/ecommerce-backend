<?php

namespace App\Http\Requests\Api\Product\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductStock\ProductAttributeOpcionsOfStockRule;

/**
 * @OA\Schema(
 *     required={
 *         "price",
 *         "product_attribute_options",
 *     },
 * )
 */
class StoreProductProductStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(
     *     property="product_attribute_options",
     *     type="array",
     *     @OA\Items(type="number"),
     * ),
     * @OA\Property(property="price", type="number"),
     * @OA\Property(property="sku", type="string"),
     * @OA\Property(property="stock", type="number"),
     * @OA\Property(property="width", type="number"),
     * @OA\Property(property="height", type="number"),
     * @OA\Property(property="length", type="number"),
     * @OA\Property(property="weight", type="number"),
     * @OA\Property(
     *     property="images",
     *     type="object",
     *     @OA\Property(
     *         property="attach",
     *         type="array",
     *         @OA\Items(
     *             type="number",
     *         ),
     *     ),
     * ),
     */
    public function rules()
    {
        return [
            'price' => [
                'required',
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'sku' => 'string|max:255',
            'stock' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'integer',
                'min:1',
            ],
            'width' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'height' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'length' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'weight' => [
                Rule::requiredIf($this->product->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'product_attribute_options' => [
                'required',
                'array',
                'min:1',
                new ProductAttributeOpcionsOfStockRule($this->product)
            ],
            'product_attribute_options.*' => [
                'required',
                'exists:product_attribute_options,id'
            ],

            'images' => ['array:attach', 'nullable'],
            'images.attach' => ['array', 'max:' . ProductStock::MAX_IMAGES, 'nullable'],
            'images.attach.*' => [
                'required',
                Rule::exists('resources', 'id')->where(function ($query) {
                    $query->where('obtainable_id', null);
                }),
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
            'price.regex' => __('The :attribute format must be between :first and :second', [
                'attribute' => 'price',
                'first' => '0.00',
                'second' => '9999999999.99',
            ]),
            'width.regex' => __('The :attribute format must be between :first and :second', [
                'attribute' => 'width',
                'first' => '0.00',
                'second' => '9999999999.99',
            ]),
            'height.regex' => __('The :attribute format must be between :first and :second', [
                'attribute' => 'height',
                'first' => '0.00',
                'second' => '9999999999.99',
            ]),
            'length.regex' => __('The :attribute format must be between :first and :second', [
                'attribute' => 'length',
                'first' => '0.00',
                'second' => '9999999999.99',
            ]),
            'weight.regex' => __('The :attribute format must be between :first and :second', [
                'attribute' => 'weight',
                'first' => '0.00',
                'second' => '9999999999.99',
            ]),
        ];
    }
}
