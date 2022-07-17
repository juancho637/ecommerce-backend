<?php

namespace App\Http\Requests\Api\Country;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateCountryRequest extends FormRequest
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
     * @OA\Property(type="string", description="short name", property="short_name", nullable=true),
     * @OA\Property(type="string", description="phone code", property="phone_code", nullable=true),
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'short_name' => 'string|max:5',
            'phone_code' => 'string|max:5',
        ];
    }
}
