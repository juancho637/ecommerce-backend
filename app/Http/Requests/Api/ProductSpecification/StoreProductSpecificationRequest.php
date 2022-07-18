<?php

namespace App\Http\Requests\Api\ProductSpecification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "product_id", "value"},
 * )
 */
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
     * @OA\Property(type="string", description="name", property="name"),
     * @OA\Property(type="number", description="product id assigned", property="product_id"),
     * @OA\Property(type="string", description="value", property="value"),
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }
}
