<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
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
     * @OA\Property(type="string", description="name", property="name", nullable=true),
     * @OA\Property(type="file", description="image", property="image", nullable=true),
     * @OA\Property(type="number", description="category parent id assigned", property="parent_id", nullable=true),
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
}
