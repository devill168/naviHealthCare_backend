<?php

namespace Database\Seeders;

use App\Models\Commune;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        // Safe truncate (communes usually referenced by villages)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Commune::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = database_path('data/commune.json');

        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        $communes = json_decode(File::get($path), true);

        if (!is_array($communes)) {
            throw new \Exception("Invalid JSON in: {$path}");
        }

        foreach ($communes as $commune) {
            Commune::updateOrCreate(
                ['id' => $commune['id']],
                [
                    'province_code' => $commune['province_code'],
                    'district_code' => $commune['district_code'],
                    'commune_name_en'        => $commune['commune_name_en'] ?? null,
                    'commune_name_kh'        => $commune['commune_name_kh'] ?? null,
                    'commune_code'     => $commune['commune_code'] ?? null,
                    'latitude'   => $commune['latitude'] ?? 1,
                    'longitude'   => $commune['longitude'] ?? 1,
                    'visit'   => $commune['visit'] ?? 1,
                ]
            );
        }
    }
}
