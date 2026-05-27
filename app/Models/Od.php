<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Od extends Model
{
    protected $table = 'ods';

    protected $fillable = [
        'province_code',
        'district_code',
        'commune_code',
        'od_name_en',
        'od_name_kh',
        'od_code',
        'name_director',
        'phone',
        'image',
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

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_code', 'commune_code');
    }

    public function hcs(): HasMany
    {
        return $this->hasMany(Hc::class, 'od_code', 'od_code');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'od_code', 'od_code');
    }
}