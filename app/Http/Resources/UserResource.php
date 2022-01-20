<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'username' => $this->username,
        ];

        if (in_array('status', $includes)) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (in_array('roles', $includes)) {
            $resource['roles'] = new RoleResource($this->roles);
        }

        if (in_array('socialNetworks', $includes)) {
            $resource['socialNetworks'] = new SocialNetworkResource($this->socialNetworks);
        }

        return $resource;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
