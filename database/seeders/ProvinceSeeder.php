<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['name' => 'Damascus', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aleppo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Homs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Latakia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hama', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('provinces')->insert($provinces);

        $this->command->info(' Provinces seeded!');
    }
}
