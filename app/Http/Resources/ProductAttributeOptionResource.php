<?php

namespace App\Http\Resources;

use App\Traits\ValidIncludes;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeOptionResource extends JsonResource
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
            'option' => $this->option,
        ];

        if (in_array('status', $includes)) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (in_array('product_attribute', $includes)) {
            $resource['product_attribute'] = new ProductAttributeResource($this->productAttribute);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'product_attribute' => 'product_attribute_id',
            'name' => 'name',
            'option' => 'option',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
