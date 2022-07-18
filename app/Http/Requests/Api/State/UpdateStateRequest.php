<?php

namespace App\Http\Requests\Api\State;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateStateRequest extends FormRequest
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
     * @OA\Property(type="number", description="country id assigned", property="country_id", nullable=true),
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'country_id' => 'exists:countries,id',
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre del departamento/estado/provincia',
            ],
            'country_id' => [
                'description' => 'Id del país asignado al departamento/estado/provincia',
            ],
        ];
    }
}
