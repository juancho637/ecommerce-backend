<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
