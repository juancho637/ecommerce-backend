<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductSpecificationResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'product_id',
        'name',
        'value',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'product_id' => 'integer',
    ];

    public $transformer = ProductSpecificationResource::class;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function setCreate($attributes)
    {
        $data['name'] = $attributes['name'];
        $data['value'] = $attributes['value'];
        $data['product_id'] = $attributes['product_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['value'] ?: $this->value = $attributes['value'];
        !$attributes['product_id'] ?: $this->product_id = $attributes['product_id'];

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
