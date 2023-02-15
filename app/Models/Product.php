<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Product extends Model
{
    use HasFactory, Searchable, HasRelationships;

    protected $fillable = [
        'status_id',
        'category_id',
        'type',
        'name',
        'slug',
        'price',
        'tax',
        'sku',
        'is_variable',
        'short_description',
        'description',
        'options',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'category_id' => 'integer',
        'is_variable' => 'boolean',
        'price' => 'float',
        'tax' => 'float',
    ];

    public $transformer = ProductResource::class;

    const CLASS_NAME = 'product';

    const DISK_PRODUCT_IMAGE = 'public';
    const PRODUCT_IMAGE = 'product image';
    const MAX_IMAGES = 4;

    // statuses
    const PENDING = 'pending';

    // types
    const PRODUCT_TYPE = 'product';
    const SERVICE_TYPE = 'service';
    const TYPES = [
        self::PRODUCT_TYPE,
        self::SERVICE_TYPE,
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'options' => $this->options,
        ];
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productSpecifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function productAttributeOptions()
    {
        return $this->belongsToMany(ProductAttributeOption::class, 'prod_prod_attr_opt');
    }

    public function images()
    {
        return $this->morphMany(Resource::class, 'obtainable')
            ->where('type_resource', self::PRODUCT_IMAGE);
    }

    public function stockImages()
    {
        return $this->hasManyDeep(
            Resource::class,
            [ProductStock::class],
            [null, ['obtainable_type', 'obtainable_id']]
        );
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

    public function scopeWithEagerLoading(Builder $query, array $includes)
    {
        $user = auth('sanctum')->user();

        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('images', $includes)) {
            $query->with('images');
        }

        if (in_array('stock_images', $includes)) {
            $this->with(['stockImages']);
        }

        if (in_array('category', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['category']);
            } else {
                $query->with(['category' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('tags', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['tags']);
            } else {
                $query->with(['tags' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('product_attribute_options', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['productAttributeOptions.productAttribute']);
            } else {
                $query->with([
                    'productAttributeOptions.productAttribute' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        if (in_array('product_stocks', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['productStocks']);
            } else {
                $query->with([
                    'productStocks' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        return $query;
    }

    public function loadEagerLoadIncludes(array $includes)
    {
        $user = auth('sanctum')->user();

        if (in_array('status', $includes)) {
            $this->load(['status']);
        }

        if (in_array('images', $includes)) {
            $this->load(['images']);
        }

        if (in_array('stock_images', $includes)) {
            $this->load(['stockImages']);
        }

        if (in_array('specifications', $includes)) {
            $this->load(['productSpecifications']);
        }

        if (in_array('category', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $this->load(['category']);
            } else {
                $this->load(['category' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('tags', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $this->load(['tags']);
            } else {
                $this->load(['tags' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('product_attribute_options', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $this->load(['productAttributeOptions.productAttribute']);
            } else {
                $this->load([
                    'productAttributeOptions.productAttribute' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        if (in_array('product_stocks', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $this->load(['productStocks.productAttributeOptions']);
            } else {
                $this->load([
                    'productStocks.productAttributeOptions' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        return $this;
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
        $isVariableFiltered = filter_var($attributes['is_variable'], FILTER_VALIDATE_BOOLEAN);

        $data['status_id'] = Status::productPending()->value('id');
        $data['category_id'] = $attributes['category_id'];
        $data['type'] = $attributes['type'];
        $data['name'] = $attributes['name'];
        $data['slug'] = Str::slug($data['name'], '-');
        $data['price'] = $attributes['price'];
        $data['tax'] = $attributes['tax'];
        $attributes['sku']
            ? $data['sku'] = $attributes['sku']
            : $data['sku'] = Str::random(10);
        $data['short_description'] = $attributes['short_description'];
        $data['description'] = $attributes['description'];
        $data['is_variable'] = $isVariableFiltered;
        $data['images']['attach'] = $attributes['images']['attach'];
        $data['tags']['attach'] = $attributes['tags']['attach'];

        if ($isVariableFiltered) {
            $data['product_attribute_options']['attach'] = $attributes['product_attribute_options']['attach'];
        } else {
            $data['stock']['price'] = $attributes['price'];
            $data['stock']['status_id'] = Status::enabled()->value('id');
            $data['stock']['sku'] = $data['sku'];

            if ($attributes['type'] === self::PRODUCT_TYPE) {
                $data['stock']['stock'] = $attributes['stock'];
                $data['stock']['width'] = $attributes['width'];
                $data['stock']['height'] = $attributes['height'];
                $data['stock']['length'] = $attributes['length'];
                $data['stock']['weight'] = $attributes['weight'];
            }
        }

        return $data;
    }

    public function setFinish($attributes)
    {
        $data['specifications'] = $attributes['specifications'];

        return $data;
    }

    public function setUpdate($attributes)
    {
        $isVariableFiltered = filter_var($attributes['is_variable'], FILTER_VALIDATE_BOOLEAN);

        $productIsVariable = isset($attributes['is_variable'])
            ? ($isVariableFiltered || ($isVariableFiltered && $this->is_variable))
            : $this->is_variable;

        !isset($attributes['name']) ?: $data['name'] = $attributes['name'];
        !isset($attributes['name']) ?: $data['slug'] = Str::slug($attributes['name'], '-');
        !isset($attributes['price']) ?: $data['price'] = $attributes['price'];
        !isset($attributes['tax']) ?: $data['tax'] = $attributes['tax'];
        !isset($attributes['sku']) ?: $data['sku'] = $attributes['sku'];
        !isset($attributes['category_id']) ?: $data['category_id'] = $attributes['category_id'];
        !isset($attributes['short_description']) ?: $data['short_description'] = $attributes['short_description'];
        !isset($attributes['description']) ?: $data['description'] = $attributes['description'];
        !isset($attributes['is_variable']) ?: $data['is_variable'] = $attributes['is_variable'];

        !isset($attributes['images']) ?: $data['images'] = $attributes['images'];

        if ($attributes['tags']) {
            !isset($attributes['tags']['attach']) ?: $data['tags']['attach'] = $attributes['tags']['attach'];
            !isset($attributes['tags']['detach']) ?: $data['tags']['detach'] = $attributes['tags']['detach'];
        }

        if ($productIsVariable) {
            if ($attributes['product_attribute_options']) {
                !isset($attributes['product_attribute_options']['attach']) ?:
                    $data['product_attribute_options']['attach'] = $attributes['product_attribute_options']['attach'];

                !isset($attributes['product_attribute_options']['detach']) ?:
                    $data['product_attribute_options']['detach'] = $attributes['product_attribute_options']['detach'];
            }
        } else {
            !isset($attributes['price']) ?: $data['stock']['price'] = $attributes['price'];
            !isset($attributes['sku']) ?: $data['stock']['sku'] = $data['sku'];

            if ($attributes['type'] === self::PRODUCT_TYPE || $this->type === self::PRODUCT_TYPE) {
                !isset($attributes['stock']) ?: $data['stock']['stock'] = $attributes['stock'];
                !isset($attributes['width']) ?: $data['stock']['width'] = $attributes['width'];
                !isset($attributes['height']) ?: $data['stock']['height'] = $attributes['height'];
                !isset($attributes['length']) ?: $data['stock']['length'] = $attributes['length'];
                !isset($attributes['weight']) ?: $data['stock']['weight'] = $attributes['weight'];
            }
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
