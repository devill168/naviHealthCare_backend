<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'province_code',
        'district_name_en',
        'district_name_kh',
        'district_code',
        'latitude',
        'longitude',
        'visit',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class, 'district_code', 'district_code');
    }

    public function ods(): HasMany
    {
        return $this->hasMany(Od::class, 'district_code', 'district_code');
    }

    public function hcs(): HasMany
    {
        return $this->hasMany(Hc::class, 'district_code', 'district_code');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'district_code', 'district_code');
    }
}