<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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

        if (in_array('status', $includes)) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (in_array('state', $includes)) {
            $resource['state'] = new StateResource($this->state);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'state' => 'state_id',
            'name' => 'name',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
