<?php

namespace App\Http\Requests\Api\Tag;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
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
                Rule::unique('tags', 'name')->ignore($this->tag),
            ],
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre del tag',
            ],
        ];
    }
}
