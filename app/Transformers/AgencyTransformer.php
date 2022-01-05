<?php

namespace App\Transformers;

use App\Models\Agency;
use League\Fractal\TransformerAbstract;

class AgencyTransformer extends TransformerAbstract
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
    public function transform(Agency $agency)
    {
        return [
            'id' => $agency->id,
            'name' => $agency->name,
            'address' => $agency->address,
        ];
    }
}
