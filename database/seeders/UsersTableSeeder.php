<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'ADMIN',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('procedures')->insert([
            'name' => 'Teeth Cleaning',
            'description' => 'Professional teeth cleaning to remove plaque and tartar.',
            'cost' => 50.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('doctors')->insert([
            'first_name' => 'Almoro',
            'middle_name' => 'A',
            'last_name' => 'Santiago',
            'specialization' => 'Dentistry',
            'contact_no' => '0987654321',
            'email' => 'almorosantiago@example.com',
            'password' => Hash::make('password'), // Or use bcrypt('password')
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
