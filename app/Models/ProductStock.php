<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductStockResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'product_id',
        'price',
        'sku',
        'stock',
        'width',
        'height',
        'length',
        'weight',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public $transformer = ProductStockResource::class;

    const DISK_PRODUCT_STOCK_IMAGE = 'public';
    const PRODUCT_STOCK_IMAGE = 'product stock image';
    const MAX_IMAGES = 1;

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

    public function images()
    {
        return $this->morphMany(Resource::class, 'obtainable')
            ->where('type_resource', self::PRODUCT_STOCK_IMAGE);
    }

    public function setCreate($attributes, $productType)
    {
        $data['status_id'] = Status::enabled()->value('id');
        $data['price'] = $attributes['price'];
        $data['product_attribute_options'] = $attributes['product_attribute_options'];
        $attributes['sku']
            ? $data['sku'] = $attributes['sku']
            : $data['sku'] = Str::random(10);
        !$attributes['images'] ?: $data['images'] = $attributes['images'];

        if ($productType === Product::PRODUCT_TYPE) {
            $data['stock'] = $attributes['stock'];
            $data['width'] = $attributes['width'];
            $data['height'] = $attributes['height'];
            $data['length'] = $attributes['length'];
            $data['weight'] = $attributes['weight'];
        }

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['stock'] ?: $this->stock = $attributes['stock'];
        !$attributes['price'] ?: $this->price = $attributes['price'];
        !$attributes['sku'] ?: $this->sku = $attributes['sku'];

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

        if (in_array('images', $includes)) {
            $this->load(['images']);
        }

        if (in_array('product_attribute_options', $includes)) {
            $this->load(['productAttributeOptions.productAttribute']);
        }

        return $this;
    }
}
