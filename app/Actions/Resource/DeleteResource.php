<?php

namespace App\Actions\Resource;

use App\Models\Resource;

class DeleteResource
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Resource $resource, string $disk = null)
    {
        try {
            $resource->delete();
            app(DeleteFile::class)($resource->path, $disk);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
