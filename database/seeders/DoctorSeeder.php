<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstname = 'Almoro';
        $middlename = 'A';
        $lastname = 'Santiago';
        $email = 'doctor@gmail.com';
        $password = Hash::make('password');

        DB::table('doctors')->insert([
            'first_name' => $firstname,
            'middle_name' => $middlename,
            'last_name' => $lastname,
            'specialization' => 'Dentistry',
            'contact_no' => '0987654321',
            'email' => $email,
            'password' => $password,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create login for doctor
        DB::table('users')->insert([
            [
                'name' => $firstname . ' ' . $middlename . ' ' . $lastname,
                'email' => $email,
                'password' => $password,
                'role' => User::ROLE_DOCTOR,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
