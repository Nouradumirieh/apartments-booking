<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Tenant Users
            [
                'phone' => '0100000001',
                'password' => Hash::make('user1234'),
                'role' => 'tenant',
                'status' => 'pending',
                'first_name' => 'Tenant1',
                'last_name' => 'User',
                'dob' => '2005-01-01',
                'id_image' => 'id1.png',
            ],
            [
                'phone' => '0100000002',
                'password' => Hash::make('user1234'),
                'role' => 'tenant',
                'status' => 'approved',
                'first_name' => 'Tenant2',
                'last_name' => 'User',
                'dob' => '2004-05-12',
                'id_image' => 'id2.png',
            ],
            [
                'phone' => '0100000003',
                'password' => Hash::make('user1234'),
                'role' => 'tenant',
                'status' => 'rejected',
                'first_name' => 'Tenant3',
                'last_name' => 'User',
                'dob' => '2003-09-20',
                'id_image' => 'id3.png',
            ],

            // Owner Users
            [
                'phone' => '0110000001',
                'password' => Hash::make('user1234'),
                'role' => 'owner',
                'status' => 'pending',
                'first_name' => 'Owner1',
                'last_name' => 'User',
                'dob' => '1995-03-15',
                'id_image' => 'id4.png',
            ],
            [
                'phone' => '0110000002',
                'password' => Hash::make('user1234'),
                'role' => 'owner',
                'status' => 'approved',
                'first_name' => 'Owner2',
                'last_name' => 'User',
                'dob' => '1990-07-08',
                'id_image' => 'id5.png',
            ],
            [
                'phone' => '0110000003',
                'password' => Hash::make('user1234'),
                'role' => 'owner',
                'status' => 'rejected',
                'first_name' => 'Owner3',
                'last_name' => 'User',
                'dob' => '1988-11-30',
                'id_image' => 'id6.png',
            ],
        ];

        foreach ($users as $u) {
            if (!User::where('phone', $u['phone'])->exists()) {
                User::create($u);
            }
        }

        $this->command->info('6 test users created successfully!');
    }
}
