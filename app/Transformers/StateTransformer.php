<?php

namespace App\Transformers;

use App\Models\State;
use League\Fractal\TransformerAbstract;

class StateTransformer extends TransformerAbstract
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
        'country',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(State $state)
    {
        return [
            'id' => $state->id,
            'name' => $state->name,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'country' => 'country_id',
            'name' => 'name',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public function includeStatus(State $state)
    {
        $status = $state->status;

        if ($status) {
            return $this->item($status, new StatusTransformer());
        }
    }

    public function includeCountry(State $state)
    {
        $country = $state->country;

        if ($country) {
            return $this->item($country, new CountryTransformer());
        }
    }
}
