<?php

namespace App\Http\Requests\Api\ProductAttribute;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "type"},
 * )
 */
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
     * @OA\Property(type="string", property="name"),
     * @OA\Property(
     *     type="string",
     *     property="type",
     *     enum={"selector", "button", "color"}
     * ),
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
}
