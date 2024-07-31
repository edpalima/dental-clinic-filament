<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('procedures')->insert([
            [
                'name' => 'Teeth Cleaning',
                'description' => 'A professional cleaning procedure to remove plaque and tartar from teeth, ensuring oral hygiene and preventing gum disease.',
                'cost' => 1500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tooth Extraction',
                'description' => 'The removal of a tooth from its socket in the bone due to decay, infection, or overcrowding.',
                'cost' => 3000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cavity Filling',
                'description' => 'A treatment to restore the function and integrity of a tooth by filling cavities caused by decay.',
                'cost' => 2500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Root Canal',
                'description' => 'A procedure to treat infected tooth pulp, involving the removal of the pulp and sealing of the tooth.',
                'cost' => 10000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dental Implants',
                'description' => 'A surgical procedure to replace missing teeth with artificial roots and crowns, offering a durable and natural-looking solution.',
                'cost' => 75000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
