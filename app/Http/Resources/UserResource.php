<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"id", "name", "slug"},
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="name", default="name", description="name", property="name"),
     * @OA\Property(type="string", title="email", default="email", description="email", property="email"),
     * @OA\Property(type="string", title="username", default="username", description="username", property="username"),
     * 
     * @OA\Property(property="status", ref="#/components/schemas/Status"),
     * 
     * @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/Role")),
     * @OA\Property(property="social_networks", type="array", @OA\Items(ref="#/components/schemas/SocialNetwork")),
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
        ];

        if (!$this->whenLoaded('status') instanceof MissingValue) {
            $resource['status'] = new StatusResource($this->status);
        }

        if (!$this->whenLoaded('roles') instanceof MissingValue) {
            $resource['roles'] = RoleResource::collection($this->roles);
        }

        if (!$this->whenLoaded('socialNetworks') instanceof MissingValue) {
            $resource['social_networks'] = SocialNetworkResource::collection($this->socialNetworks);
        }

        return $resource;
    }

    /**
     * @OA\Parameter(
     *     parameter="user--id",
     *     name="id",
     *     in="query",
     *     @OA\Schema(
     *         type="number"
     *     )
     * ),
     * @OA\Parameter(
     *     parameter="user--status",
     *     name="status",
     *     in="query",
     *     @OA\Schema(
     *         type="number"
     *     )
     * ),
     * @OA\Parameter(
     *     parameter="user--name",
     *     name="name",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     * @OA\Parameter(
     *     parameter="user--email",
     *     name="email",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     * @OA\Parameter(
     *     parameter="user--username",
     *     name="username",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     */
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
