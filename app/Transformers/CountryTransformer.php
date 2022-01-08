<?php

namespace App\Transformers;

use App\Models\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
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
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id' => $country->id,
            'name' => $country->name,
            'short_name' => $country->short_name,
            'phone_code' => $country->phone_code,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'status' => 'status_id',
            'name' => 'name',
            'short_name' => 'short_name',
            'phone_code' => 'phone_code',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public function includeStatus(Country $country)
    {
        $status = $country->status;

        if ($status) {
            return $this->item($status, new StatusTransformer());
        }
    }
}
