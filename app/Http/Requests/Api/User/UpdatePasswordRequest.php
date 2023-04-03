<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={
 *         "password",
 *         "new_password",
 *         "new_password_confirmation",
 *     },
 * )
 */
class UpdatePasswordRequest extends FormRequest
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
     * @OA\Property(property="password", type="string"),
     * @OA\Property(property="new_password", type="string"),
     * @OA\Property(property="new_password_confirmation", type="string"),
     */
    public function rules()
    {
        return [
            "password" => "required|string",
            "new_password" => "required|string|confirmed|min:6",
            "new_password_confirmation" => "required|string",
        ];
    }
}
