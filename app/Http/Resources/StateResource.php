<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="State",
 *     required={"id", "name"},
 * )
 */
class StateResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(property="status", ref="#/components/schemas/Status")
     * @OA\Property(property="country", ref="#/components/schemas/Country")
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('country') instanceof MissingValue) {
            $resource['country'] = new CountryResource($this->country);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'country' => 'country_id',
            'name' => 'name',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
