<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "image"},
 * )
 */
class StoreCategoryRequest extends FormRequest
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
     * @OA\Property(type="file", description="image", property="image"),
     * @OA\Property(type="number", description="category parent id assigned", property="parent_id", required=false),
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'required|image',
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
