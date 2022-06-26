<?php

namespace App\Http\Requests\Api\ProductAttributeOption;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductAttributeOptionRequest extends FormRequest
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
            'product_attribute_id' => 'required|integer|exists:product_attributes,id',
            'name' => 'required|string|max:255',
            'option' => [
                'nullable',
                Rule::requiredIf(function () {
                    $productAttribute = ProductAttribute::find($this->product_attribute_id);

                    if (!is_null($productAttribute) && $productAttribute->type) {
                        return $productAttribute->type === ProductAttribute::COLOR_TYPE;
                    }

                    return false;
                }),
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
        ];
    }

    public function bodyParameters()
    {
        return [
            'product_attribute_id' => [
                'description' => 'Id del atributo de producto asignado a la opción de atributo',
            ],
            'name' => [
                'description' => 'Nombre de la opción del atributo',
            ],
            'option' => [
                'description' => 'Opción de la opción del atributo (requerido si el atributo es de tipo color)',
            ],
        ];
    }
}
