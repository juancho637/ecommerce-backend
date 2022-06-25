<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
                'sometimes',
                'string',
                Rule::unique('categories', 'name')->ignore($this->category),
            ],
            'image' => 'nullable|image',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre de la categoria',
            ],
            'image' => [
                'description' => 'Imagen asociada a la categoría',
            ],
            'parent_id' => [
                'description' => 'Id de la categoría padre asignada a la categoría en cuestión (opcional)',
            ],
        ];
    }
}
