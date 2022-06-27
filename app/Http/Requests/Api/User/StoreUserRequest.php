<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "username" => "sometimes|required|string|unique:users|max:100",
            "password" => "required|string|confirmed|min:6",
            "password_confirmation" => "required|string",
            "role" => "required|integer|exists:roles,id",
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre completo del usuario',
            ],
            'email' => [
                'description' => 'Correo eléctonico del usuario',
            ],
            'username' => [
                'description' => 'Nickname del usuario',
            ],
            'password' => [
                'description' => 'Contraseña del usuario',
            ],
            'password_confirmation' => [
                'description' => 'Confirmación de la contraseña del usuario',
            ],
            'role' => [
                'description' => 'Rol del usuario',
            ],
        ];
    }
}
