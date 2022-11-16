<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductStockResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'product_id',
        'stock',
        'min_stock',
        'price',
        'tax',
        'sku',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'product_id' => 'integer',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    public $transformer = ProductStockResource::class;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttributeOptions()
    {
        return $this->belongsToMany(ProductAttributeOption::class, 'prod_attr_opt_prod_stk');
    }

    public function setCreate($attributes)
    {
        $data['stock'] = $attributes['stock'];
        $data['min_stock'] = $attributes['min_stock'];
        $data['price'] = $attributes['price'];
        $data['tax'] = $attributes['tax'];
        $data['sku'] = sha1(time());
        $data['product_id'] = $attributes['product_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['stock'] ?: $this->stock = $attributes['stock'];
        !$attributes['min_stock'] ?: $this->min_stock = $attributes['min_stock'];
        !$attributes['price'] ?: $this->price = $attributes['price'];
        !$attributes['tax'] ?: $this->tax = $attributes['tax'];
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

    public function loadEagerLoadIncludes(array $includes)
    {
        if (in_array('status', $includes)) {
            $this->load(['status']);
        }

        if (in_array('product', $includes)) {
            $this->load(['product']);
        }

        if (in_array('product_attribute_options', $includes)) {
            $this->load(['productAttributeOptions.productAttribute']);
        }

        return $this;
    }
}
