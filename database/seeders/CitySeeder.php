<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['province_id' => 1, 'name' => 'Damascus City', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'name' => 'Aleppo City', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 3, 'name' => 'Homs City', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 4, 'name' => 'Latakia City', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 5, 'name' => 'Hama City', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('cities')->insert($cities);

        $this->command->info(' Cities seeded!');
    }
}
