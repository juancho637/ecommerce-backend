<?php

namespace App\Transformers;

use App\Models\SocialNetwork;
use League\Fractal\TransformerAbstract;

class SocialNetworkTransformer extends TransformerAbstract
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
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(SocialNetwork $socialNetwork)
    {
        return [
            'provider' => $socialNetwork->provider,
            'provider_id' => $socialNetwork->provider_id,
            'avatar' => $socialNetwork->avatar,
        ];
    }
}
