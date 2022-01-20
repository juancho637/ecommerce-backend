<?php

namespace App\Http\Requests\Api\Category;

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
            'name' => 'sometimes|string|unique:categories,name,' . $this->category->id,
            'image' => 'nullable|image',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}
