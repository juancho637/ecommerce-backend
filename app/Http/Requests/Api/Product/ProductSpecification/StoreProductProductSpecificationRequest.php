<?php

namespace App\Http\Requests\Api\Product\ProductSpecification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={
 *         "name",
 *         "value",
 *     },
 * )
 */
class StoreProductProductSpecificationRequest extends FormRequest
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
     * @OA\Property(property="value", type="string"),
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'value' => 'required|string',
        ];
    }
}
