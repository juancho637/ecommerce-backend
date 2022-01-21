<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
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
            'type' => $this->type,
        ];

        if (in_array('status', $includes)) {
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
            'type' => 'type',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
