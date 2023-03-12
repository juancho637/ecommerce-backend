<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'price' => 'float',
        'stock' => 'integer',
        'width' => 'float',
        'height' => 'float',
        'length' => 'float',
        'weight' => 'float',
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

    public function scopeWithEagerLoading(?Builder $query, array $includes, string $type = 'with')
    {
        // $user = auth('sanctum')->user();
        $typeBuilder = $type === 'with' ? $query : $this;

        if (in_array('status', $includes)) {
            $typeBuilder->$type(['status']);
        }

        if (in_array('product', $includes)) {
            $typeBuilder->$type(['product']);
        }

        if (in_array('images', $includes)) {
            $typeBuilder->$type(['images']);
        }

        if (in_array('product_attribute_options', $includes)) {
            $typeBuilder->$type(['productAttributeOptions']);
        }

        if (in_array('product_attribute_options.product_attribute', $includes)) {
            $typeBuilder->$type(['productAttributeOptions.productAttribute']);
        }

        return $typeBuilder;
    }

    public function setCreate($attributes, $productId, $productType)
    {
        $data['status_id'] = Status::enabled()->value('id');
        $data['product_id'] = $productId;
        $data['price'] = $attributes['price'];
        $data['product_attribute_options'] = $attributes['product_attribute_options'];
        isset($attributes['sku'])
            ? $data['sku'] = $attributes['sku']
            : $data['sku'] = Str::random(10);
        !isset($attributes['images']) ?: $data['images'] = $attributes['images'];

        if ($productType === Product::PRODUCT_TYPE) {
            $data['stock'] = $attributes['stock'];
            $data['width'] = $attributes['width'];
            $data['height'] = $attributes['height'];
            $data['length'] = $attributes['length'];
            $data['weight'] = $attributes['weight'];
        }

        return $data;
    }

    public function setUpdate($attributes, $productType)
    {
        if ($productType === Product::PRODUCT_TYPE) {
            !isset($attributes['stock']) ?: $data['stock'] = $attributes['stock'];
            !isset($attributes['width']) ?: $data['width'] = $attributes['width'];
            !isset($attributes['height']) ?: $data['height'] = $attributes['height'];
            !isset($attributes['length']) ?: $data['length'] = $attributes['length'];
            !isset($attributes['weight']) ?: $data['weight'] = $attributes['weight'];
        }

        !isset($attributes['price']) ?: $data['price'] = $attributes['price'];
        !isset($attributes['sku']) ?: $data['sku'] = $attributes['sku'];

        if ($attributes['images']) {
            !isset($attributes['images']['attach']) ?: $data['images']['attach'] = $attributes['images']['attach'];
            !isset($attributes['images']['detach']) ?: $data['images']['detach'] = $attributes['images']['detach'];
        }

        return $data;
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
