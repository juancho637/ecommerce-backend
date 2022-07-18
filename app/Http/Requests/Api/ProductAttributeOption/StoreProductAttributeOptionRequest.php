<?php

namespace App\Http\Requests\Api\ProductAttributeOption;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "product_attribute_id", "option"},
 * )
 */
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
     * @OA\Property(type="string", description="name", property="name"),
     * @OA\Property(type="number", description="product attribute id assigned", property="product_attribute_id"),
     * @OA\Property(type="string", description="option (required if attribute type is color)", property="option"),
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
}
