<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"_method"},
 * )
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
     * @OA\Property(type="string", description="method", property="_method", default="PUT", enum={"PUT"}),
     * @OA\Property(type="string", description="name", property="name"),
     * @OA\Property(type="string", format="binary", description="image", property="image"),
     * @OA\Property(type="number", description="category parent id assigned", property="parent_id"),
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
