<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Country",
 *     required={"id", "name", "short_name", "phone_code"},
 * )
 */
class CountryResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", description="id", property="id", default=1),
     * @OA\Property(type="string", title="name", description="name", property="name", default="name"),
     * @OA\Property(type="string", title="short_name", description="short_name", property="short_name", default="short_name"),
     * @OA\Property(type="string", title="phone_code", description="phone_code", property="phone_code", default="phone_code"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status")
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'phone_code' => $this->phone_code,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'name' => 'name',
            'short_name' => 'short_name',
            'phone_code' => 'phone_code',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
