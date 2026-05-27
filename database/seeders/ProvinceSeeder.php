<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        // Safe truncate when there are foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Use absolute path
        $path = database_path('data/province.json');

        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        $json = File::get($path);

        // Decode as array
        $provinces = json_decode($json, true);

        if (!is_array($provinces)) {
            throw new \Exception("Invalid JSON in: {$path}");
        }

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['id' => $province['id']], // match by id
                [
                    'province_name_en' => $province['province_name_en'] ?? null,
                    'province_name_kh' => $province['province_name_kh'] ?? null,
                    'province_code' => $province['province_code'] ?? null,
                    'latitude' => $province['latitude'] ?? 1,
                    'longitude' => $province['longitude'] ?? 1,
                ]
            );
        }
    }
}
