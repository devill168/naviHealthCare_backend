<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'province_name_en',
        'province_name_kh',
        'province_code',
        'latitude',
        'longitude',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'province_code', 'province_code');
    }

    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class, 'province_code', 'province_code');
    }

    public function ods(): HasMany
    {
        return $this->hasMany(Od::class, 'province_code', 'province_code');
    }

    public function hcs(): HasMany
    {
        return $this->hasMany(Hc::class, 'province_code', 'province_code');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'province_code', 'province_code');
    }
}