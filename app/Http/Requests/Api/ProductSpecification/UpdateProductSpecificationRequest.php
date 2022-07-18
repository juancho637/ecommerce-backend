<?php

namespace App\Http\Requests\Api\ProductSpecification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateProductSpecificationRequest extends FormRequest
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
     * @OA\Property(type="number", description="product id assigned", property="product_id", nullable=true),
     * @OA\Property(type="string", description="value", property="value", nullable=true),
     */
    public function rules()
    {
        return [
            'product_id' => 'exists:products,id',
            'name' => 'string|max:255',
            'value' => 'string|max:255',
        ];
    }
}
