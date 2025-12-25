<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    
    public function run(): void
    {
      
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'phone' => '0000000000',
                'password' => Hash::make('admin123'), 
                'role' => 'admin',
                'status' => 'approved',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'dob' => '2000-01-01',
                'id_image' => 'admin.png', 
            ]);

            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
