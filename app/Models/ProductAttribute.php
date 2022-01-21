<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\ProductAttributeResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'name',
        'type',
    ];

    protected $casts = [
        'status_id' => 'integer',
    ];

    public $transformer = ProductAttributeResource::class;

    const BUTTON_TYPE = 'button';
    const SELECT_TYPE = 'select';
    const COLOR_TYPE = 'color';

    const TYPES = [
        self::BUTTON_TYPE,
        self::SELECT_TYPE,
        self::COLOR_TYPE,
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
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
        $data['type'] = $attributes['type'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['type'] ?: $this->type = $attributes['type'];

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
