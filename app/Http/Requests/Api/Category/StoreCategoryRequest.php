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
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="image", type="number"),
     * @OA\Property(property="parent_id", type="number"),
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'required|exists:resources,id,obtainable_id,NULL',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}
