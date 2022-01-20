<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SocialNetworkResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'provider_id',
        'user_id',
        'avatar'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public $transformer = SocialNetworkResource::class;
}
