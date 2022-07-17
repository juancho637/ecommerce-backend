<?php

namespace App\Http\Requests\Api\Country;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "short_name", "phone_code"},
 * )
 */
class StoreCountryRequest extends FormRequest
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
     * @OA\Property(type="string", description="short name", property="short_name"),
     * @OA\Property(type="string", description="phone code", property="phone_code"),
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:5',
            'phone_code' => 'required|string|max:5',
        ];
    }
}
