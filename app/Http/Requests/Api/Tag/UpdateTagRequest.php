<?php

namespace App\Http\Requests\Api\Tag;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
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
     * @OA\Property(type="string", description="name", property="name", nullable=true),
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
}
