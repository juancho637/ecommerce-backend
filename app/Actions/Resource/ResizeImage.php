<?php

namespace App\Actions\Resource;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ResizeImage
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(string $file, string $path, string $disk)
    {
        try {
            $fitImage = Image::make($file); //->fit(720);
            $extension = explode('/', $fitImage->mime())[1];
            $fileName = md5(random_int(1, 10000000) . microtime());
            $path = 'image/' . $path . '/' . $fileName . '.' . $extension;

            app(UploadFile::class)(
                file: $fitImage->encode(),
                path: $path,
                disk: $disk
            );

            return $path;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
