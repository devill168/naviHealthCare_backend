<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    protected $fillable = [
        'province_code',
        'district_code',
        'commune_name_en',
        'commune_name_kh',
        'commune_code',
        'latitude',
        'longitude',
        'visit',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

    public function ods(): HasMany
    {
        return $this->hasMany(Od::class, 'commune_code', 'commune_code');
    }

    public function hcs(): HasMany
    {
        return $this->hasMany(Hc::class, 'commune_code', 'commune_code');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'commune_code', 'commune_code');
    }
}