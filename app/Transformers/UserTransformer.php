<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'status',
        'roles',
        'agency',
        'socialNetworks',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
        ];
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

    public function includeStatus(User $user)
    {
        $status = $user->status;

        if ($status) {
            return $this->item($status, new StatusTransformer());
        }
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        if ($roles) {
            return $this->collection($roles, new RoleTransformer());
        }
    }

    public function includeSocialNetworks(User $user)
    {
        $socialNetworks = $user->socialNetworks;

        if ($socialNetworks) {
            return $this->collection($socialNetworks, new SocialNetworkTransformer());
        }
    }
}
