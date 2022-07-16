<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "email", "username", "password", "password_confirmation"},
 * )
 */
class RegisterAuthRequest extends FormRequest
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
     * @OA\Property(type="string", description="email", property="email"),
     * @OA\Property(type="string", description="username", property="username"),
     * @OA\Property(type="string", description="password", property="password"),
     * @OA\Property(type="string", description="password confirmation", property="password_confirmation"),
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'username' => 'sometimes|required|string|unique:users|max:100',
            "password" => "required|string|confirmed|min:6",
            "password_confirmation" => "required|string",
        ];
    }
}
