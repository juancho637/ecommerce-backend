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
     * @OA\Property(property="_method", type="string", default="PUT", enum={"PUT"}),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="image", type="number"),
     * @OA\Property(property="parent_id", type="number"),
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                Rule::unique('categories', 'name')->ignore($this->category),
                'nullable',
            ],
            'image' => 'exists:resources,id,obtainable_id,NULL|nullable',
            'parent_id' => 'exists:categories,id|nullable',
        ];
    }
}
