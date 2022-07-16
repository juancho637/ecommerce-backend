<?php

namespace App\Http\Requests\Api\City;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateCityRequest extends FormRequest
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
     * @OA\Property(type="string", default="city name", description="city name", property="name"),
     * @OA\Property(type="number", default=1, description="state id assigned to the city", property="state_id"),
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'state_id' => 'exists:states,id',
        ];
    }
}
