<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    protected $table = 'villages';

    protected $fillable = [
        'province_code',
        'district_code',
        'commune_code',
        // 'od_code',
        // 'hc_code',
        'village_name_en',
        'village_name_kh',
        'village_code',
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

    public function hc(): BelongsTo
    {
        return $this->belongsTo(Hc::class, 'hc_code', 'hc_code');
    }
}
