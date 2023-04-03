<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"email"},
 * )
 */
class ForgotPasswordRequest extends FormRequest
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
     */
    public function rules()
    {
        return [
            "email" => "required|email",
        ];
    }
}
