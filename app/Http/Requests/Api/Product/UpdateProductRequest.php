<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
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
     * @OA\Property(type="string", description="name", property="name", nullable=true),
     * @OA\Property(type="number", description="category id assigned", property="category_id", nullable=true),
     * @OA\Property(type="string", description="short description", property="short_description", nullable=true),
     * @OA\Property(type="string", description="description", property="description", nullable=true),
     * @OA\Property(
     *     type="array",
     *     description="photos",
     *     property="photos",
     *     nullable=true,
     *     @OA\Items(
     *         type="object",
     *         required={"file", "location"},
     *         @OA\Property(type="file", description="file", property="file"),
     *         @OA\Property(type="number", description="location", property="location"),
     *     ),
     * ),
     * @OA\Property(
     *     type="array",
     *     description="tags",
     *     property="tags",
     *     nullable=true,
     *     @OA\Items(
     *         type="number",
     *     ),
     * ),
     * @OA\Property(
     *     type="array",
     *     nullable=true,
     *     description="product attribute options",
     *     property="product_attribute_options",
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
            'short_description' => 'nullable|string|max:600',
            'description' => 'nullable|string',
            'tags' => 'nullable|array|min:1',
            'tags.*' => 'exists:tags,id',
            'photos' => ['nullable', 'array', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'photos.*.file' => 'required|image',
            'photos.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'exists:product_attribute_options,id',
        ];
    }
}
