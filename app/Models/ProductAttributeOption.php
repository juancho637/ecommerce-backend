<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Resources\ProductAttributeOptionResource;

class ProductAttributeOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'product_attribute_id',
        'name',
        'option',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'product_attribute_id' => 'integer',
    ];

    public $transformer = ProductAttributeOptionResource::class;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'prod_prod_attr_opt');
    }

    public function productStocks()
    {
        return $this->belongsToMany(ProductStock::class, 'prod_attr_opt_prod_stk');
    }

    public function scopeWithEagerLoading(?Builder $query, array $includes, string $type = 'with')
    {
        // $user = auth('sanctum')->user();
        $typeBuilder = $type === 'with' ? $query : $this;

        if (in_array('status', $includes)) {
            $typeBuilder->$type(['status']);
        }

        if (in_array('product_attribute', $includes)) {
            $typeBuilder->$type(['productAttribute']);
        }

        return $typeBuilder;
    }

    public function setCreate($attributes)
    {
        $data['name'] = $attributes['name'];
        $data['option'] = $attributes['option'];
        $data['product_attribute_id'] = $attributes['product_attribute_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['option'] ?: $this->option = $attributes['option'];
        !$attributes['product_attribute_id'] ?: $this->product_attribute_id = $attributes['product_attribute_id'];

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
