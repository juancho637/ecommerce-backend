<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"token"},
 * )
 */
class ProviderAuthRequest extends FormRequest
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
     * @OA\Property(type="string", property="token", description="token of provider"),
     */
    public function rules()
    {
        return [
            'token' => 'required|string'
        ];
    }
}
