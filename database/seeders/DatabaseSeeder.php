<?php

namespace Database\Seeders;

use Database\Seeders\CommuneSeeder;
use Database\Seeders\DistrictSeeder;
use Database\Seeders\HealthFacilitySeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VillageSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            DistrictSeeder::class,
            CommuneSeeder::class,
            VillageSeeder::class,
            RoleSeeder::class,
            HealthFacilitySeeder::class,
        ]);

    }
}
