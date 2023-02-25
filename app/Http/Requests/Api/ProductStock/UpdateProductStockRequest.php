<?php

namespace App\Http\Requests\Api\ProductStock;

use App\Models\ProductStock;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductStock\ProductAttributeOpcionsOfStockRule;

/**
 * @OA\Schema()
 */
class UpdateProductStockRequest extends FormRequest
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
     * @OA\Property(property="product_id", type="number"),
     * @OA\Property(property="stock", type="number"),
     * @OA\Property(property="price", type="number"),
     * @OA\Property(
     *     property="images",
     *     type="array",
     *     @OA\Items(type="number"),
     * ),
     * @OA\Property(
     *     property="product_attribute_options",
     *     type="array",
     *     @OA\Items(
     *         type="number",
     *     ),
     * ),
     */
    public function rules()
    {
        return [
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|between:0.00,9999999999.99|regex:/^\d+(\.\d{1,2})?$/',
            'product_attribute_options' => [
                'nullable',
                'array',
                new ProductAttributeOpcionsOfStockRule($this->product_id)
            ],
            'product_attribute_options.*' => 'required|exists:product_attribute_options,id',
            'images' => 'nullable|array|max:' . ProductStock::MAX_IMAGES,
            'images.*' => 'required|exists:resources,id,obtainable_id,NULL',
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
            'price.regex' => 'The price format must be between 0.00 and 9999999999.99',
        ];
    }
}
