<?php

namespace App\Traits;

trait ValidIncludes
{
    protected function valid(string $searchword = '', array $includes = [])
    {
        return key(preg_grep("/\b$searchword\b/i", $includes)) !== null;
    }
}
