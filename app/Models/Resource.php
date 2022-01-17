<?php

namespace App\Models;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Transformers\ResourceTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'path',
        'type_resource',
        'obtainable_type',
        'obtainable_id',
    ];

    public $timestamps = false;

    protected $casts = [
        'obtainable_id' => 'integer',
    ];

    public $transformer = ResourceTransformer::class;

    public function obtainable()
    {
        return $this->morphTo();
    }

    public function scopeByTypeResource($query, $type)
    {
        return $query->where('type_resource', $type);
    }

    public function saveResource($resource, $type, $id, $typeResource, $path, $isImage = false, $disk = 'public')
    {
        try {
            $data['path'] = $isImage
                ? $this->resizeImage($resource, $path, $disk)
                : $this->uploadFile($resource, $path, $disk);

            $data['url'] = Storage::disk($disk)->url($data['path']);
            $data['obtainable_type'] = $type;
            $data['obtainable_id'] = $id;
            $data['type_resource'] = $typeResource;

            return $data;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function updateResource($resource, $type, $id, $typeResource, $path, $isImage = false, $disk = 'public')
    {
        try {
            $oldPath = $this->path;
            $this->path = $isImage
                ? $this->resizeImage($resource, $path, $disk)
                : $this->uploadFile($resource, $path, $disk);

            $this->url = Storage::disk($disk)->url($this->path);
            $this->obtainable_type = $type;
            $this->obtainable_id = $id;
            $this->type_resource = $typeResource;

            $this->deleteFile($oldPath);

            return $this;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    protected function resizeImage($url, $path, $disk = 'public')
    {
        try {
            $fitImage = Image::make($url); //->fit(720);
            $extension = '.' . explode("/", $fitImage->mime())[1];
            $fileName = md5(random_int(1, 10000000) . microtime());
            $path = "image/$path/$fileName$extension";

            Storage::disk($disk)->put($path, $fitImage->encode());

            return $path;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function uploadFile($file, $path, $disk = 'public')
    {
        try {
            return Storage::disk($disk)->put("files/" . $path, $file);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function deleteFile($path, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($disk)) {
                Storage::disk($disk)->delete($path);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
