<?php

namespace App\Models;

use App\Transformers\AgencyTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'status_id',
    ];

    public $transformer = AgencyTransformer::class;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
