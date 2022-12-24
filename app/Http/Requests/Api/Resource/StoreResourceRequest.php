<?php

namespace App\Http\Requests\Api\Resource;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreResourceDTO",
 *     required={
 *         "file",
 *     },
 * )
 */
class StoreResourceRequest extends FormRequest
{
    /**
     * @OA\Property(
     *     property="file",
     *     type="file",
     *     format="binary",
     * ),
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
            'file' => 'required|mimes:jpg,jpeg,png,csv'
        ];
    }
}
