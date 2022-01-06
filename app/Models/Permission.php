<?php

namespace App\Models;

use App\Transformers\PermissionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiPermission;

class Permission extends SpatiPermission
{
    use HasFactory;

    public $transformer = PermissionTransformer::class;
}
