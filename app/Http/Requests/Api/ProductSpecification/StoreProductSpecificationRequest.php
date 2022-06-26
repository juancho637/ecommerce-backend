<?php

namespace App\Http\Requests\Api\ProductSpecification;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductSpecificationRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }

    public function bodyParameters()
    {
        return [
            'product_id' => [
                'description' => 'Id del producto asignado a la especificación del producto',
            ],
            'name' => [
                'description' => 'Nombre de la especificación del producto',
            ],
            'value' => [
                'description' => 'Valor de la especificación del producto',
            ],
        ];
    }
}
