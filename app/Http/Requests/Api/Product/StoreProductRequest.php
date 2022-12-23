<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreProductDTO",
 *     required={
 *         "name",
 *         "category_id",
 *         "price",
 *         "tax",
 *         "description",
 *         "is_variable",
 *         "images",
 *         "tags",
 *     },
 * )
 */
class StoreProductRequest extends FormRequest
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
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="category_id", type="number"),
     * @OA\Property(property="price", type="number"),
     * @OA\Property(property="tax", type="number"),
     * @OA\Property(property="short_description", type="string"),
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="is_variable", type="boolean"),
     * @OA\Property(property="stock", type="number"),
     * @OA\Property(property="width", type="number"),
     * @OA\Property(property="height", type="number"),
     * @OA\Property(property="length", type="number"),
     * @OA\Property(property="weight", type="number"),
     * @OA\Property(
     *     property="images",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         required={"file", "location"},
     *         @OA\Property(property="file", type="string", format="binary"),
     *         @OA\Property(property="location", type="number"),
     *     ),
     * ),
     * @OA\Property(
     *     property="tags",
     *     type="array",
     *     @OA\Items(
     *         type="number",
     *     ),
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
        filter_var($this->is_variable, FILTER_VALIDATE_BOOLEAN)
            ? $isVariable = true
            : $isVariable = false;

        return [
            'type' => 'required|string|in:' . implode(',', Product::TYPES),
            'name' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|between:0.00,9999999999.99|regex:/^\d+(\.\d{1,2})?$/',
            'tax' => 'required|numeric|between:0.00,99.99|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => [
                Rule::requiredIf(!$isVariable && $this->type === Product::PRODUCT_TYPE),
                'integer',
                'min:1',
            ],
            'width' => [
                Rule::requiredIf(!$isVariable && $this->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'height' => [
                Rule::requiredIf(!$isVariable && $this->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'length' => [
                Rule::requiredIf(!$isVariable && $this->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'weight' => [
                Rule::requiredIf(!$isVariable && $this->type === Product::PRODUCT_TYPE),
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'short_description' => 'nullable|string|max:600',
            'description' => 'nullable|string',
            'is_variable' => 'required|boolean',
            'images' => ['required', 'array', 'min:1', 'max:' . Product::MAX_IMAGES],
            'images.*.file' => 'required|image',
            'images.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_IMAGES],
            'tags' => 'required|array|min:1',
            'tags.*' => 'integer|exists:tags,id',
            'product_attribute_options' => [
                Rule::requiredIf($isVariable),
                'array',
            ],
            'product_attribute_options.*' => 'integer|exists:product_attribute_options,id',
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
            'tax.regex' => 'The tax format must be between 0.00 and 99.99',
            'width.regex' => 'The width format must be between 0.00 and 9999999999.99',
            'height.regex' => 'The height format must be between 0.00 and 9999999999.99',
            'length.regex' => 'The length format must be between 0.00 and 9999999999.99',
            'weight.regex' => 'The weight format must be between 0.00 and 9999999999.99',
        ];
    }
}
