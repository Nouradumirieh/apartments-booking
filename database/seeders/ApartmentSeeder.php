<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $apartments = [
            [
                'owner_id' => 2, // مستخدم موجود في FullProjectSeeder
                'province_id' => 1,
                'city_id' => 1,
                'title' => 'Luxury Apartment Damascus',
                'description' => 'Beautiful apartment in Damascus.',
                'price' => 1200.50,
                'images' => json_encode(['img1.jpg','img2.jpg']),
                'number_of_rooms' => 3,
                'number_of_bathrooms' => 2,
                'has_elevator' => true,
                'has_balcony' => true,
                'address_details' => '123 Main Street',
                'area' => 85.50,
                'status' => 'available',
                'admin_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => 3,
                'province_id' => 2,
                'city_id' => 2,
                'title' => 'Cozy Apartment Aleppo',
                'description' => 'Nice apartment in Aleppo city center.',
                'price' => 900,
                'images' => json_encode(['img3.jpg','img4.jpg']),
                'number_of_rooms' => 2,
                'number_of_bathrooms' => 1,
                'has_elevator' => false,
                'has_balcony' => true,
                'address_details' => '456 Aleppo Street',
                'area' => 60,
                'status' => 'available',
                'admin_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('apartments')->insert($apartments);

        $this->command->info('Apartments seeded!');
    }
}
