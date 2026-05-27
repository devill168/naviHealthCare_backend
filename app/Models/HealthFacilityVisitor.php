<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthFacilityVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'health_facility_id',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(Register::class, 'user_id');
    }

    public function healthFacility()
    {
        return $this->belongsTo(HealthFacility::class, 'health_facility_id');
    }
}
