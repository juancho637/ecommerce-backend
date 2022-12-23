<?php

namespace App\Actions\Resource;

use Intervention\Image\Facades\Image;

class ResizeImage
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(string $file, string $name, string $path, string $disk)
    {
        try {
            $originalImage = Image::make($file);
            $width = $originalImage->width();
            $height = $originalImage->height();

            if ($width > 1200 || $height > 1200) {
                if ($width > $height) {
                    $originalImage->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $originalImage->resize(null, 1200, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }

            $thumbImage = Image::make($file)->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas(150, 150);
            $smallImage = Image::make($file)->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas(300, 300);
            $mediumImage = Image::make($file)->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas(600, 600);

            $extension = explode('/', $originalImage->mime())[1];

            $originalImagePath = 'images/' . $path . '/' . $name . '-original.' . $extension;
            $thumbImagePath = 'images/' . $path . '/' . $name . '-thumb.' . $extension;
            $smallImagePath = 'images/' . $path . '/' . $name . '-small.' . $extension;
            $mediumImagePath = 'images/' . $path . '/' . $name . '-medium.' . $extension;

            app(UploadFile::class)(
                file: $originalImage->encode(),
                path: $originalImagePath,
                disk: $disk
            );

            app(UploadFile::class)(
                file: $thumbImage->encode(),
                path: $thumbImagePath,
                disk: $disk
            );

            app(UploadFile::class)(
                file: $smallImage->encode(),
                path: $smallImagePath,
                disk: $disk
            );

            app(UploadFile::class)(
                file: $mediumImage->encode(),
                path: $mediumImagePath,
                disk: $disk
            );

            return [
                'original' => $originalImagePath,
                'thumb' => $thumbImagePath,
                'small' => $smallImagePath,
                'medium' => $mediumImagePath,
            ];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
