<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Http\Resources\CityResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'status_id',
        'state_id',
        'name',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'state_id' => 'integer',
    ];

    public $transformer = CityResource::class;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scopeByRole(Builder $query)
    {
        $user = auth('sanctum')->user();

        if ($user && $user->hasRole(Role::ADMIN)) {
            return $query;
        }

        $query->whereHas('status', function ($query) {
            return $query->where('name', Status::ENABLED);
        });
    }

    public function scopeWithEagerLoading(?Builder $query, array $includes, string $type = 'with')
    {
        // $user = auth('sanctum')->user();
        $typeBuilder = $type === 'with' ? $query : $this;

        if (in_array('status', $includes)) {
            $typeBuilder->$type('status');
        }

        if (in_array('state', $includes)) {
            $typeBuilder->$type('state');
        }

        if (in_array('state.country', $includes)) {
            $typeBuilder->$type('state.country');
        }

        return $typeBuilder;
    }

    public function validByRole()
    {
        $user = auth('sanctum')->user();

        if ($this->status->name === Status::DISABLED) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function setCreate($attributes)
    {
        $data['name'] = $attributes['name'];
        $data['state_id'] = $attributes['state_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['state_id'] ?: $this->state_id = $attributes['state_id'];

        return $this;
    }

    public function setDelete()
    {
        if ($this->status_id === Status::disabled()->value('id')) {
            $this->status_id = Status::enabled()->value('id');
        } else if ($this->status_id === Status::enabled()->value('id')) {
            $this->status_id = Status::disabled()->value('id');
        }

        return $this;
    }
}
