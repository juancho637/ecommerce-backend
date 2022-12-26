<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ResourceResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'path',
        'type_resource',
        'obtainable_type',
        'obtainable_id',
        'options',
    ];

    public $timestamps = false;

    protected $casts = [
        'obtainable_id' => 'integer',
    ];

    public $transformer = ResourceResource::class;

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function obtainable()
    {
        return $this->morphTo();
    }

    public function scopeByTypeResource($query, $type)
    {
        return $query->where('type_resource', $type);
    }

    public function setCreate($attributes)
    {
        $data['file'] = $attributes['file'];

        return $data;
    }
}
