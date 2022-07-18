<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={
 *         "name",
 *         "email",
 *         "username",
 *         "password",
 *         "password_confirmation",
 *         "role",
 *     },
 * )
 */
class StoreUserRequest extends FormRequest
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
     * @OA\Property(type="number", description="role assigned", property="role"),
     */
    public function rules()
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "username" => "sometimes|required|string|unique:users|max:100",
            "password" => "required|string|confirmed|min:6",
            "password_confirmation" => "required|string",
            "role" => "required|integer|exists:roles,id",
        ];
    }
}
