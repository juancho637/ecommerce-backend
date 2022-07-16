<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SocialNetwork",
 *     required={"id", "provider", "provider_id", "avatar"},
 * )
 */
class SocialNetworkResource extends JsonResource
{
    /**
     * @OA\Property(type="number", title="id", default=1, description="id", property="id"),
     * @OA\Property(type="string", title="provider", default="provider", description="provider", property="provider"),
     * @OA\Property(type="string", title="provider_id", default="provider_id", description="provider_id", property="provider_id"),
     * @OA\Property(type="string", title="avatar", default="avatar", description="avatar", property="avatar"),
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,
            'avatar' => $this->avatar,
        ];
    }
}
