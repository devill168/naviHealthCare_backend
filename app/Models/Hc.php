<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hc extends Model
{
    protected $table = 'hcs';

    protected $fillable = [
        'province_code',
        'district_code',
        'commune_code',
        'od_code',
        'hc_name_en',
        'hc_name_kh',
        'hc_code',
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

    public function od(): BelongsTo
    {
        return $this->belongsTo(Od::class, 'od_code', 'od_code');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'hc_code', 'hc_code');
    }
}
