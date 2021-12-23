<?php

namespace App\Transformers;

use App\Models\Status;
use League\Fractal\TransformerAbstract;

class StatusTransformer extends TransformerAbstract
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
    public function transform(Status $status)
    {
        return [
            'id' => $status->id,
            'name' => $status->name,
            'type' => $status->type,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'type' => 'type',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
