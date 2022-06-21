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
            'photos' => ['required', 'array', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'photos.*.file' => 'required|image',
            'photos.*.location' => ['required', 'integer', 'min:1', 'max:' . Product::MAX_PHOTOS],
            'tags' => 'required|array|min:1',
            'tags.*' => 'integer|exists:tags,id',
            'product_attribute_options' => 'nullable|array',
            'product_attribute_options.*' => 'integer|exists:product_attribute_options,id',
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
