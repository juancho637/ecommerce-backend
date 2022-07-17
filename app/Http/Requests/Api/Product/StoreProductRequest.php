<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={
 *         "name",
 *         "category_id",
 *         "description",
 *         "photos",
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
     * @OA\Property(type="string", description="name", property="name"),
     * @OA\Property(type="number", description="category id assigned", property="category_id"),
     * @OA\Property(type="string", description="short description", property="short_description"),
     * @OA\Property(type="string", description="description", property="description"),
     * @OA\Property(
     *     type="array",
     *     minItems=1,
     *     description="photos",
     *     property="photos",
     *     @OA\Items(
     *         type="object",
     *         required={"file", "location"},
     *         @OA\Property(type="file", description="file", property="file"),
     *         @OA\Property(type="number", description="location", property="location"),
     *     ),
     * ),
     * @OA\Property(
     *     type="array",
     *     minItems=1,
     *     description="tags",
     *     property="tags",
     *     @OA\Items(
     *         type="number",
     *     ),
     * ),
     * @OA\Property(
     *     type="array",
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
            'name' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:600',
            'description' => 'nullable|string',
            'photos' => ['required', 'array', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'photos.*.file' => 'required|image',
            'photos.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'tags' => 'required|array|min:1',
            'tags.*' => 'integer|exists:tags,id',
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'integer|exists:product_attribute_options,id',
        ];
    }
}
