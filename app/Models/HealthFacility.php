<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthFacility extends Model
{
    public const TYPE_PHD = 'PHD';
    public const TYPE_NH  = 'NH';
    public const TYPE_PH  = 'PH';
    public const TYPE_OD  = 'OD';
    public const TYPE_RH  = 'RH';
    public const TYPE_HC  = 'HC';
    public const TYPE_HP  = 'HP';

    public const TYPES = [
        self::TYPE_PHD,
        self::TYPE_NH,
        self::TYPE_PH,
        self::TYPE_OD,
        self::TYPE_RH,
        self::TYPE_HC,
        self::TYPE_HP,
    ];

    protected $fillable = [
        'code',
        'name_km',
        'name_en',
        'type',
        'parent_id',
        'province_code',
        'district_code',
        'commune_code',
        'village_code',
        'latitude',
        'longitude',
        'hf_image',
        'director_name',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public static function types(): array
    {
        return self::TYPES;
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_PHD => 'Provincial Health Department',
            self::TYPE_PH  => 'Provincial Hospital',
            self::TYPE_OD  => 'Operational District',
            self::TYPE_RH  => 'Referral Hospital',
            self::TYPE_HC  => 'Health Center',
            self::TYPE_HP  => 'Health Post',
        ];
    }

    public static function parentTypeMap(): array
    {
        return [
            self::TYPE_PHD => null,
            self::TYPE_NH  => self::TYPE_PHD,
            self::TYPE_PH  => self::TYPE_PHD,
            self::TYPE_OD  => self::TYPE_PHD,
            self::TYPE_RH  => self::TYPE_OD,
            self::TYPE_HC  => self::TYPE_OD,
            self::TYPE_HP  => self::TYPE_HC,
        ];
    }

    public static function allowedParentType(?string $type): ?string
    {
        return self::parentTypeMap()[$type] ?? null;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

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

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_code', 'village_code');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        return $query->when($type, fn (Builder $q) => $q->where('type', $type));
    }

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where(function (Builder $sub) use ($search) {
                    $sub->where('code', 'like', "%{$search}%")
                        ->orWhere('name_km', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, function (Builder $q, $type) {
                $q->where('type', $type);
            })
            ->when(
                array_key_exists('parent_id', $filters) && $filters['parent_id'] !== '',
                function (Builder $q) use ($filters) {
                    $q->where('parent_id', $filters['parent_id']);
                }
            )
            ->when($filters['province_code'] ?? null, function (Builder $q, $value) {
                $q->where('province_code', $value);
            })
            ->when($filters['district_code'] ?? null, function (Builder $q, $value) {
                $q->where('district_code', $value);
            })
            ->when($filters['commune_code'] ?? null, function (Builder $q, $value) {
                $q->where('commune_code', $value);
            })
            ->when($filters['village_code'] ?? null, function (Builder $q, $value) {
                $q->where('village_code', $value);
            })
            ->when(
                array_key_exists('is_active', $filters) && $filters['is_active'] !== '',
                function (Builder $q) use ($filters) {
                    $q->where(
                        'is_active',
                        filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)
                    );
                }
            );
    }

    public function visitors()
    {
        return $this->hasMany(HealthFacilityVisitor::class, 'health_facility_id');
    }
}
