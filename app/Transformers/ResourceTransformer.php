<?php

namespace App\Transformers;

use App\Models\Resource;
use League\Fractal\TransformerAbstract;

class ResourceTransformer extends TransformerAbstract
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
    public function transform(Resource $resource)
    {
        return [
            'id' => $resource->id,
            'url' => $resource->url,
            'type_resource' => $resource->type_resource,
        ];
    }
}
