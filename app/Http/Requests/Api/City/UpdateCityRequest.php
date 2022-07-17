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
     * @OA\Property(type="string", description="name", property="name", nullable=true),
     * @OA\Property(type="number", description="state id assigned", property="state_id", nullable=true),
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'state_id' => 'exists:states,id',
        ];
    }
}
