<?php

namespace App\Http\Requests\Api\ProductAttribute;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class UpdateProductAttributeRequest extends FormRequest
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
     * @OA\Property(type="string", description="type", property="type", nullable=true),
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'type' => [Rule::in(ProductAttribute::TYPES)],
        ];
    }
}
