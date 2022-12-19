<?php

namespace App\Actions\Resource;

use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class UpdateResource
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(
        Resource $resource,
        string $file,
        string $type,
        int $typeId,
        string $typeResource,
        string $path,
        bool $isImage = false,
        string $disk = 'public',
        string $options = null
    ) {
        try {
            $disk = config('filesystems.' . $disk);

            $oldPath = $resource->path;
            $resource->path = $isImage
                ? app(ResizeImage::class)(
                    file: $file,
                    path: $path,
                    disk: $disk
                )
                : app(UploadFile::class)(
                    file: $file,
                    path: $path,
                    disk: $disk
                );

            $resource->url = Storage::disk($disk)->url($resource->path);
            $resource->obtainable_type = $type;
            $resource->obtainable_id = $typeId;
            $resource->type_resource = $typeResource;
            $resource->$options = $options;
            $resource->save();

            app(DeleteFile::class)($oldPath);

            return $resource;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
