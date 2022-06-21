<?php

namespace App\Http\Requests\Api\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
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
            'photos' => 'nullable|array|min:1|max:' . Product::MAX_PHOTOS,
            'photos.*.file' => 'required|image',
            'photos.*.location' => 'required|integer|min:1|max:' . Product::MAX_PHOTOS,
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'exists:product_attribute_options,id',
        ];
    }
}
