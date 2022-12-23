<?php

namespace App\Actions\Resource;

use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class StoreResource
{
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(
        string $file,
        string $type,
        int $typeId,
        string $typeResource,
        string $path,
        bool $isImage = false,
        string $disk = 'public',
        array $options = null
    ) {
        try {
            $disk = config('filesystems.' . $disk);
            $fileName = md5(random_int(1, 10000000) . microtime());

            if ($isImage) {
                $data['path'] = app(ResizeImage::class)(
                    file: $file,
                    name: $fileName,
                    path: $path,
                    disk: $disk
                );

                foreach ($data['path'] as $imageKey => $imagePath) {
                    $data['url'][$imageKey] = Storage::disk($disk)->url($imagePath);
                }
            } else {
                $data['path'] = app(UploadFile::class)(
                    file: $file,
                    path: $path,
                    disk: $disk
                );

                $data['url']['original'] = Storage::disk($disk)->url($data['path']);
            }

            $data['obtainable_type'] = $type;
            $data['obtainable_id'] = $typeId;
            $data['type_resource'] = $typeResource;
            $data['options'] = $options;

            return $this->resource->create($data);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
