<?php

namespace App\Actions\Resource;

use Illuminate\Support\Facades\Storage;

class UploadFile
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(string $file, string $path, string $disk)
    {
        try {
            return Storage::disk($disk)->put($path, $file);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
