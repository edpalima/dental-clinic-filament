<?php

namespace Database\Seeders;

use App\Models\Time;
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

        $times = [
            '08:00 AM' => '09:00 AM',
            '09:00 AM' => '10:00 AM',
            '10:00 AM' => '11:00 AM',
            '11:00 AM' => '12:00 PM',
            '01:00 PM' => '02:00 PM',
            '02:00 PM' => '03:00 PM',
            '03:00 PM' => '04:00 PM',
            '04:00 PM' => '05:00 PM',
        ];

        $sort = 1;
        foreach ($times as $start => $end) {
            Time::create([
                'name' => "{$start} to {$end}",
                'time_start' => date('H:i', strtotime($start)),
                'time_end' => date('H:i', strtotime($end)),
                'sort' => $sort,
                'is_active' => true,
            ]);

            $sort++;
        }
    }
}
