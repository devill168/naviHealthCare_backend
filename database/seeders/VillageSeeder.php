<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks (villages are referenced by staff, addresses, etc.)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Village::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = database_path('data/village.json');

        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        $villages = json_decode(File::get($path), true);

        if (!is_array($villages)) {
            throw new \Exception("Invalid JSON in: {$path}");
        }

        foreach ($villages as $village) {
            Village::updateOrCreate(
                ['id' => $village['id']],
                [
                    'province_code' => $village['province_code'],
                    'district_code' => $village['district_code'],
                    'commune_code' => $village['commune_code'],
                    // 'od_code' => null,
                    // 'hc_code'       => null,
                    'village_name_en'    => $village['village_name_en'] ?? null,
                    'village_name_kh'    => $village['village_name_kh'] ?? null,
                    'village_code'  => $village['village_code'] ?? NULL,
                    'latitude'  => $village['latitude'] ?? NULL,
                    'longitude'  => $village['longitude'] ?? NULL,
                    'visit'  => $village['visit'] ?? 0,
                ]
            );
        }
    }
}
