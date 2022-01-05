<?php

namespace App\Models;

use App\Transformers\RoleTransformer;
use Spatie\Permission\Models\Role as SpatiRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatiRole
{
    use HasFactory;

    public $transformer = RoleTransformer::class;

    const SUPER_ADMIN = 'super administrator';
    const COMPANY_ADMIN = 'company administrator';
    const AGENCY_ADMIN = 'agency administrator';

    public function scopeSuperAdmin($query)
    {
        return $query->where('name', self::SUPER_ADMIN);
    }

    public function scopeCompanyAdmin($query)
    {
        return $query->where('name', self::COMPANY_ADMIN);
    }

    public function scopeAgencyAdmin($query)
    {
        return $query->where('name', self::AGENCY_ADMIN);
    }
}
