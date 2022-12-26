<?php

namespace App\Actions\Resource;

use Illuminate\Support\Facades\Storage;

class DeleteFile
{
    /**
     * Handle the incoming action.
     */
    public function __invoke($paths, $disk = 'public')
    {
        try {
            $disk = config('filesystems.' . $disk);

            foreach ($paths as $path) {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
