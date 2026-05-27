<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // If districts have FK relationships (e.g., communes), truncate safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        District::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = database_path('data/district.json');

        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        $districts = json_decode(File::get($path), true);

        if (!is_array($districts)) {
            throw new \Exception("Invalid JSON in: {$path}");
        }

        foreach ($districts as $district) {
            District::updateOrCreate(
                ['id' => $district['id']],
                [
                    'province_code' => $district['province_code'],
                    'district_name_en'        => $district['district_name_en'] ?? null,
                    'district_name_kh'        => $district['district_name_kh'] ?? null,
                    'district_code'     => $district['district_code'] ?? null,
                    'latitude'   => $district['latitude'] ?? 1,
                    'longitude'   => $district['longitude'] ?? 1,
                    'visit'   => $district['visit'] ?? 1,
                ]
            );
        }
    }
}
