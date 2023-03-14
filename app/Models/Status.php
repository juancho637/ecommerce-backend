<?php

namespace App\Models;

use App\Http\Resources\StatusResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public $timestamps = false;

    public $transformer = StatusResource::class;

    // Statuses
    const ENABLED = 'enabled';
    const DISABLED = 'disabled';

    // Modules
    const GENERAL = 'general';

    const STATUSES = [
        ['type' => self::GENERAL, 'name' => self::ENABLED],
        ['type' => self::GENERAL, 'name' => self::DISABLED],

        ['type' => Product::CLASS_NAME, 'name' => Product::GENERAL_STEP],
        ['type' => Product::CLASS_NAME, 'name' => Product::STOCKS_STEP],
        ['type' => Product::CLASS_NAME, 'name' => Product::SPECIFICATIONS_STEP],
    ];

    public function scopeEnabled($query)
    {
        return $query->where('name', self::ENABLED);
    }

    public function scopeDisabled($query)
    {
        return $query->where('name', self::DISABLED);
    }

    public function scopeProductGeneralStep($query)
    {
        return $query->where('type', Product::CLASS_NAME)
            ->where('name', Product::GENERAL_STEP);
    }

    public function scopeProductStocksStep($query)
    {
        return $query->where('type', Product::CLASS_NAME)
            ->where('name', Product::STOCKS_STEP);
    }

    public function scopeProductSpecificationsStep($query)
    {
        return $query->where('type', Product::CLASS_NAME)
            ->where('name', Product::SPECIFICATIONS_STEP);
    }
}
