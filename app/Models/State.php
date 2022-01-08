<?php

namespace App\Models;

use App\Transformers\StateTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'country_id',
        'name',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'country_id' => 'integer',
    ];

    public $transformer = StateTransformer::class;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
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

    public function validByRole()
    {
        $user = auth('sanctum')->user();

        if (
            $this->status->name === Status::DISABLED &&
            $user && $user->hasRole(Role::ADMIN)
        ) {
            return true;
        }

        return false;
    }

    public function setCreate($attributes)
    {
        $data['name'] = $attributes['name'];
        $data['country_id'] = $attributes['country_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['country_id'] ?: $this->country_id = $attributes['country_id'];

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
