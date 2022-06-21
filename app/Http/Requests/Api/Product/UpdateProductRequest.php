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
            'photos' => ['nullable', 'array', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'photos.*.file' => 'required|image',
            'photos.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'exists:product_attribute_options,id',
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre del producto',
            ],
            'category_id' => [
                'description' => 'Id de la categoría asignada al producto',
            ],
            'short_description' => [
                'description' => 'Descripción corta del producto',
            ],
            'description' => [
                'description' => 'Descripción completa del producto.',
            ],
            'photos' => [
                'description' => 'Fotos del producto',
            ],
            'photos.*.file' => [
                'description' => 'Foto del producto',
            ],
            'photos.*.location' => [
                'description' => 'Localización de la foto del producto',
            ],
            'tags' => [
                'description' => 'Tags asociados al producto',
            ],
            'tags.*' => [
                'description' => 'Ids de los tags asociados al producto',
            ],
            'product_attribute_options' => [
                'description' => 'Opciones de atributos asociados al producto',
            ],
            'product_attribute_options.*' => [
                'description' => 'Ids de las opciones de atributos asociados al producto',
            ],
        ];
    }
}
