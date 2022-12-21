<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="FinishProductDTO",
 *     required={
 *         "specifications",
 *     },
 * )
 */
class FinishProductRequest extends FormRequest
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
     * @OA\Property(
     *     property="specifications",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         required={"name", "value"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="value", type="string"),
     *     ),
     * ),
     */
    public function rules()
    {
        return [
            'specifications' => 'required|array',
            'specifications.*.name' => 'required|string',
            'specifications.*.value' => 'required|string',
        ];
    }
}
