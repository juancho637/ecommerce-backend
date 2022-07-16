<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Status",
 *     required={"id", "name", "type"},
 * )
 */
class StatusResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="type", default="type", description="type", property="type"),
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'type' => 'type',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
