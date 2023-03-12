<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

        return $typeBuilder;
    }

    public function setCreate($attributes, $productId)
    {
        $data['status_id'] = Status::enabled()->value('id');
        $data['product_id'] = $productId;
        $data['name'] = $attributes['name'];
        $data['value'] = $attributes['value'];

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['value'] ?: $this->value = $attributes['value'];

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
