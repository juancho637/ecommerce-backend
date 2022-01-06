<?php

namespace App\Models;

use App\Transformers\RoleTransformer;
use Spatie\Permission\Models\Role as SpatiRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatiRole
{
    use HasFactory;

    public $transformer = RoleTransformer::class;

    const ADMIN = 'administrator';
    const USER = 'user';

    public function scopeAdmin($query)
    {
        return $query->where('name', self::ADMIN);
    }

    public function scopeUser($query)
    {
        return $query->where('name', self::USER);
    }
}
