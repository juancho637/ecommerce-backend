<?php

namespace App\Models;

use App\Http\Resources\PermissionResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiPermission;

class Permission extends SpatiPermission
{
    use HasFactory;

    public $transformer = PermissionResource::class;
}
