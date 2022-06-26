<?php

namespace App\Http\Requests\Api\State;

use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
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
