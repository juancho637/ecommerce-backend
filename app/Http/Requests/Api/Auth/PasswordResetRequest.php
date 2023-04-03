<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={
 *         "email",
 *         "token",
 *         "password",
 *         "password_confirmation",
 *     },
 * )
 */
class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !($user = auth()->user()) || !($user instanceof User);
    }

    /**
     * @OA\Property(property="email", type="string"),
     * @OA\Property(property="token", type="string"),
     * @OA\Property(property="password", type="string"),
     * @OA\Property(property="password_confirmation", type="string"),
     */
    public function rules()
    {
        return [
            "email" => "required|email",
            "token" => "required|string",
            "password" => "required|string|confirmed|min:6",
            "password_confirmation" => "required|string",
        ];
    }
}
