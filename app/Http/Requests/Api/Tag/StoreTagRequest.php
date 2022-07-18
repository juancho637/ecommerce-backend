<?php

namespace App\Http\Requests\Api\Tag;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name"},
 * )
 */
class StoreTagRequest extends FormRequest
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
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:tags',
        ];
    }
}
