<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\CategoryResource;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'name',
        'slug',
        'parent_id',
    ];

    protected $casts = [
        'status_id' => 'integer',
        'parent_id' => 'integer',
    ];

    public $transformer = CategoryResource::class;

    const CATEGORY_IMAGE = 'category image';

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function childrenCategories()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    public function image()
    {
        return $this->morphOne(Resource::class, 'obtainable')
            ->where('type_resource', self::CATEGORY_IMAGE);
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
        $data['slug'] = Str::slug($data['name'], '-');
        $data['parent_id'] = $attributes['parent_id'];
        $data['status_id'] = Status::enabled()->value('id');

        return $data;
    }

    public function setUpdate($attributes)
    {
        !$attributes['name'] ?: $this->name = $attributes['name'];
        !$attributes['name'] ?: $this->slug = Str::slug($attributes['name'], '-');
        !$attributes['parent_id'] ?: $this->parent_id = $attributes['parent_id'];

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

    public static function formatTree($categories, $allCategories)
    {
        foreach ($categories as $category) {
            $category->children = $allCategories->where('parent_id', $category->id)->values();

            if ($category->children->isNotEmpty()) {
                self::formatTree($category->children, $allCategories);
            }
        }
    }

    public function saveImage($file)
    {
        if (!$file) return;

        try {
            $resource = new Resource();
            $oldFile = $this->image()->first();
            $moduleNamePath = strtolower(class_basename($this));

            if ($oldFile) {
                $resource->deleteFile($oldFile->path);

                return $oldFile->update($resource->saveResource(
                    $file,
                    self::class,
                    $this->id,
                    self::CATEGORY_IMAGE,
                    $moduleNamePath,
                    true
                ));
            } else {
                return $resource->create($resource->saveResource(
                    $file,
                    self::class,
                    $this->id,
                    self::CATEGORY_IMAGE,
                    $moduleNamePath,
                    true
                ));
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), 400);
        }
    }
}
