<?php

namespace App\Transformers;

use App\Models\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
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
        'state',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'state' => 'state_id',
            'name' => 'name',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public function includeStatus(City $city)
    {
        $status = $city->status;

        if ($status) {
            return $this->item($status, new StatusTransformer());
        }
    }

    public function includeState(City $city)
    {
        $state = $city->state;

        if ($state) {
            return $this->item($state, new StateTransformer());
        }
    }
}
