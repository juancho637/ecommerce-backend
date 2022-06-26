<?php

namespace App\Http\Requests\Api\ProductAttribute;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductAttributeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => [
                'required',
                Rule::in(ProductAttribute::TYPES)
            ],
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Nombre del atributo de producto',
            ],
            'type' => [
                'description' => 'Tipo del atributo de producto',
            ],
        ];
    }
}
