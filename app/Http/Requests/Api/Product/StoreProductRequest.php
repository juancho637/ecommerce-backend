<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:600',
            'description' => 'nullable|string',
            'photos' => 'required|array|min:1|max:' . Product::MAX_PHOTOS,
            'photos.*.file' => 'required|image',
            'photos.*.location' => 'required|integer|min:1|max:' . Product::MAX_PHOTOS,
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:tags,id',
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'exists:product_attribute_options,id',
        ];
    }
}
