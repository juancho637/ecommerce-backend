<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'status_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'options',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'category_id' => 'integer',
    ];

    public $transformer = ProductResource::class;

    const PRODUCT_PHOTO = 'product photo';
    const MAX_PHOTOS = 4;

    #[SearchUsingPrefix(['name', 'slug'])]
    #[SearchUsingFullText(['short_description', 'description', 'options'])]
    public function toSearchableArray()
    {
        return [
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

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function productAttributeOptions()
    {
        return $this->belongsToMany(ProductAttributeOption::class, 'prod_prod_attr_opt');
    }

    public function photos()
    {
        return $this->morphMany(Resource::class, 'obtainable')
            ->where('type_resource', self::PRODUCT_PHOTO);
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

    public function loadEagerLoadIncludes(array $includes)
    {
        $user = auth('sanctum')->user();

        if (in_array('status', $includes)) {
            $this->load(['status']);
        }

        if (in_array('photos', $includes)) {
            $this->load(['photos']);
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
        $data['category_id'] = $attributes['category_id'];
        $data['name'] = $attributes['name'];
        $data['slug'] = Str::slug($data['name'], '-');
        $data['short_description'] = $attributes['short_description'];
        $data['description'] = $attributes['description'];
        $data['status_id'] = Status::enabled()->value('id');
        $data['options'] = $attributes['options'];

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['name'] ?: $this->slug = Str::slug($attributes['name'], '-');
        !$attributes['category_id'] ?: $this->category_id = $attributes['category_id'];
        !$attributes['short_description'] ?: $this->short_description = $attributes['short_description'];
        !$attributes['description'] ?: $this->description = $attributes['description'];

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

    private function saveOnePhoto($resourceModule, $path, $file)
    {
        return $resourceModule->create(
            $resourceModule->saveResource(
                resource: $file,
                type: self::class,
                id: $this->id,
                typeResource: self::PRODUCT_PHOTO,
                path: $path,
                isImage: true
            )
        );
    }

    public function savePhotos($photos)
    {
        try {
            $resource = new Resource();
            $photosSaved = [];
            $moduleNamePath = strtolower(class_basename($this));

            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    $photosSaved[] = $this->saveOnePhoto($resource, $moduleNamePath, $photo);
                }
            } else {
                return $this->saveOnePhoto($resource, $moduleNamePath, $photos);
            }

            return $photosSaved;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
