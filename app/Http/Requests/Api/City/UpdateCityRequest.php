<?php

namespace App\Http\Requests\Api\City;

use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'state_id' => 'exists:states,id',
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre de la ciudad',
            ],
            'state_id' => [
                'description' => 'Id de la estado/departamento/provincia asignado a la ciudad',
            ],
        ];
    }
}
