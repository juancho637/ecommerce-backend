<?php

namespace App\Models;

use App\Transformers\PermissionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiPermission;

class Permission extends SpatiPermission
{
    use HasFactory;

    public $transformer = PermissionTransformer::class;

    // Types
    const COMPANY = 'company';
    const AGENCY = 'agency';

    // Permissions
    const PERMISSIONS_VIEW = 'permissions.view';

    const ROLES_VIEW = 'roles.view';
    const ROLES_SHOW = 'roles.show';
    const ROLES_CREATE = 'roles.create';
    const ROLES_EDIT = 'roles.edit';
    const ROLES_DELETE = 'roles.delete';
}
