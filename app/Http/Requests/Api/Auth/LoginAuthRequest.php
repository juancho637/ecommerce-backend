<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"username", "password"},
 * )
 */
class LoginAuthRequest extends FormRequest
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
     * @OA\Property(type="string", property="username", description="email or username"),
     * @OA\Property(type="string", property="password", description="password"),
     */
    public function rules()
    {
        return [
            "username" => "required|string",
            "password" => "required|string",
        ];
    }
}
