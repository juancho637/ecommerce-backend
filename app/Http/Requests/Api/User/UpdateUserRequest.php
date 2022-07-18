<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema()
 */
class UpdateUserRequest extends FormRequest
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
     * @OA\Property(type="string", description="username", property="username"),
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'username' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($this->tag),
            ],
        ];
    }
}
