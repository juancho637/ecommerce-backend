<?php

namespace App\Http\Requests\Api\ProductAttributeOption;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateProductAttributeOptionRequest extends FormRequest
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
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="product_attribute_id", type="number"),
     * @OA\Property(property="option", type="string"),
     */
    public function rules()
    {
        return [
            'product_attribute_id' => 'integer|exists:product_attributes,id',
            'name' => 'string|max:255',
            'option' => [
                'nullable',
                Rule::requiredIf(function () {
                    if (!$this->product_attribute_id) {
                        return false;
                    }

                    $productAttribute = ProductAttribute::find($this->product_attribute_id);

                    return $productAttribute->type === ProductAttribute::COLOR_TYPE;
                }),
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
        ];
    }
}
