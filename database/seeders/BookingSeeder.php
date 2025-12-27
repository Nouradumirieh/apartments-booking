<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = [
            [
                'user_id' => 1, // مستخدم موجود في FullProjectSeeder
                'apartment_id' => 1,
                'start_date' => now()->addDays(1)->format('Y-m-d'),
                'end_date' => now()->addDays(7)->format('Y-m-d'),
                'requested_start_date' => null,
                'requested_end_date' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'apartment_id' => 2,
                'start_date' => now()->addDays(3)->format('Y-m-d'),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'requested_start_date' => null,
                'requested_end_date' => null,
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('bookings')->insert($bookings);

        $this->command->info(' Bookings seeded!');
    }
}
