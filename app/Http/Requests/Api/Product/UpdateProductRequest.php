<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateProductDTO",
 * )
 */
class UpdateProductRequest extends FormRequest
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
     * @OA\Property(property="short_description", type="string"),
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="is_variable", type="boolean"),
     * @OA\Property(
     *     property="images",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         required={"file", "location"},
     *         @OA\Property(property="file", type="file"),
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
        return [
            'name' => [
                'nullable',
                'string',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric|between:0.00,9999999999.99|regex:/^\d+(\.\d{1,2})?$/',
            'tax' => 'nullable|numeric|between:0.00,99.99|regex:/^\d+(\.\d{1,2})?$/',
            'short_description' => 'nullable|string|max:600',
            'description' => 'nullable|string',
            'is_bool' => 'nullable|boolean',
            'tags' => 'nullable|array|min:1',
            'tags.*' => 'exists:tags,id',
            'images' => ['nullable', 'array', 'min:1', 'max:' . Product::MAX_IMAGES],
            'images.*.file' => 'required|image',
            'images.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_IMAGES],
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'exists:product_attribute_options,id',
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
        ];
    }
}
