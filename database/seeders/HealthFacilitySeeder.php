<?php

namespace Database\Seeders;

use App\Models\HealthFacility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HealthFacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        HealthFacility::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = database_path('data/health_facility.json');

        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        $healthFacilities = json_decode(File::get($path), true);

        if (!is_array($healthFacilities)) {
            throw new \Exception("Invalid JSON in: {$path}");
        }

        foreach ($healthFacilities as $item) {

            // Helper to convert empty values to null
            $null = fn($value) => ($value === "" || $value === "null") ? null : $value;

            HealthFacility::updateOrCreate(
            // ✅ UNIQUE condition (choose ONE stable field)
                [
                    'code' => $null($item['code'] ?? null),
                ],
                // ✅ DATA to update or insert
                [
                    'name_km'       => $null($item['name_km'] ?? null),
                    'name_en'       => $null($item['name_en'] ?? null),
                    'type'          => $null($item['type'] ?? null),
                    'parent_id'     => $null($item['parent_id'] ?? null),
                    'province_code' => $null($item['province_code'] ?? null),
                    'district_code' => $null($item['district_code'] ?? null),
                    'commune_code'  => $null($item['commune_code'] ?? null),
                    'village_code'  => $null($item['village_code'] ?? null),
                    'latitude'      => $null($item['latitude'] ?? null),
                    'longitude'     => $null($item['longitude'] ?? null),
                    'hf_image'      => $null($item['hf_image'] ?? null),
                    'is_active'     => $item['is_active'] ?? true,
                ]
            );
        }
    }
}
