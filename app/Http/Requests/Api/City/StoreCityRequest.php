<?php

namespace App\Http\Requests\Api\City;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "state_id"},
 * )
 */
class StoreCityRequest extends FormRequest
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
     * @OA\Property(type="string", description="city name", property="name"),
     * @OA\Property(type="number", description="state id assigned to the city", property="state_id"),
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ];
    }
}
