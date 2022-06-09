<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $includes = explode(',', $request->get('include'));

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
