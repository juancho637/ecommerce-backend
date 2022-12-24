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
    public function __invoke(array $fields)
    {
        try {
            $file = $fields['file'];
            $fileName = md5(random_int(1, 10000000) . microtime());
            $disk = config('filesystems.public');
            $mimeType = explode('/', $file->getMimeType());

            if (strpos($mimeType[0], 'image') !== false) {
                $data['path'] = app(ResizeImage::class)(
                    file: $file,
                    name: $fileName,
                    mimeType: $mimeType[1],
                    disk: $disk
                );

                foreach ($data['path'] as $imageKey => $imagePath) {
                    $data['url'][$imageKey] = Storage::disk($disk)->url($imagePath);
                }
            } else {
                $data['path'] = $fileName . '.' . $mimeType[1];

                app(UploadFile::class)(
                    file: $file,
                    path: $data['path'],
                    disk: $disk
                );

                $data['url']['original'] = Storage::disk($disk)->url($data['path']);
            }

            return $this->resource->create($data);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
