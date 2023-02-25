<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateProductGeneralRequest",
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
     * @OA\Property(property="type", type="string", enum={"product", "service"}),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="category_id", type="number"),
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
     *     type="object",
     *     @OA\Property(
     *         property="attach",
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             required={"id", "location"},
     *             @OA\Property(property="id", type="number"),
     *             @OA\Property(property="location", type="number"),
     *         ),
     *     ),
     *     @OA\Property(
     *         property="detach",
     *         type="array",
     *         @OA\Items(
     *             type="number",
     *         ),
     *     ),
     * ),
     * @OA\Property(
     *     property="tags",
     *     type="object",
     *     @OA\Property(
     *         property="attach",
     *         type="array",
     *         @OA\Items(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Property(
     *         property="detach",
     *         type="array",
     *         @OA\Items(
     *             type="number",
     *         ),
     *     ),
     * ),
     * @OA\Property(
     *     property="product_attribute_options",
     *     type="object",
     *     @OA\Property(
     *         property="attach",
     *         type="array",
     *         @OA\Items(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Property(
     *         property="detach",
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
            'name' => [
                'string',
                Rule::unique('products', 'name')->ignore($this->product),
                'nullable',
            ],
            'category_id' => 'exists:categories,id|nullable',
            'price' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'tax' => [
                'numeric',
                'between:0.00,99.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'short_description' => 'string|max:600|nullable',
            'description' => 'string|nullable',
            'is_variable' => 'boolean|nullable',

            'stock' => [
                'integer',
                'min:1',
                'nullable',
            ],
            'width' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'height' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'length' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'weight' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],

            'images' => ['array:detach,attach', 'nullable'],
            'images.detach' => 'array|nullable',
            'images.detach.*' => 'exists:resources,id',
            'images.attach' => ['array', 'max:' . Product::MAX_IMAGES, 'nullable'],
            'images.attach.*.id' => 'required|exists:resources,id,obtainable_id,NULL',
            'images.attach.*.location' => [
                'required',
                'integer',
                'min:1',
                'max:' . Product::MAX_IMAGES
            ],

            'tags' => 'array:detach,attach|nullable',
            'tags.detach' => 'array|nullable',
            'tags.detach.*' => 'exists:tags,id',
            'tags.attach' => 'array|nullable',
            'tags.attach.*' => 'exists:tags,id',

            'product_attribute_options' => 'array:detach,attach|nullable',
            'product_attribute_options.detach' => 'array|nullable',
            'product_attribute_options.detach.*' => 'exists:product_attribute_options,id',
            'product_attribute_options.attach' => 'array|nullable',
            'product_attribute_options.attach.*' => 'exists:product_attribute_options,id',
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
