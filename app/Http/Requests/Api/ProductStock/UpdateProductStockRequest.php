<?php

namespace App\Http\Requests\Api\ProductStock;

use App\Models\ProductStock;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'price' => [
                'numeric',
                'between:0.00,9999999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
                'nullable',
            ],
            'sku' => 'string|max:255|nullable',
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
