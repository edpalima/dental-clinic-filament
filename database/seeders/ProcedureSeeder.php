<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert procedures and get their IDs
        $procedures = [
            ['name' => 'Teeth Cleaning', 'short_description' => 'Naglilinis ng ngipin.', 'description' => 'Isang propesyonal na paglilinis ng ngipin upang tanggalin ang plake at tartar, pinapanatili ang kalinisan ng bibig at iniiwasan ang sakit sa gilagid.', 'cost' => 1500.00],
            ['name' => 'Tooth Extraction', 'short_description' => 'Nag-aalis ng sirang ngipin.', 'description' => 'Ang pagtanggal ng ngipin mula sa buto dahil sa pagkabulok, impeksyon, o sobrang siksikan.', 'cost' => 3000.00],
            ['name' => 'Cavity Filling', 'short_description' => 'Nagpupuno ng butas sa ngipin.', 'description' => 'Isang paggamot upang maibalik ang anyo at tibay ng ngipin sa pamamagitan ng pagpuno sa mga butas na dulot ng pagkabulok.', 'cost' => 2500.00],
            ['name' => 'Root Canal', 'short_description' => 'Gumagamot ng impeksyon sa ugat ng ngipin.', 'description' => 'Isang proseso upang gamutin ang impeksyon sa ugat ng ngipin sa pamamagitan ng pagtanggal ng pulp at pagselyo sa ngipin.', 'cost' => 10000.00],
            ['name' => 'Dental Implants', 'short_description' => 'Pamalit sa nawawalang ngipin.', 'description' => 'Isang operasyon upang palitan ang nawawalang ngipin gamit ang artipisyal na ugat at korona, nagbibigay ng matibay at natural na hitsura.', 'cost' => 75000.00],
            ['name' => 'Braces', 'short_description' => 'Nagpapatuwid ng ngipin.', 'description' => 'Isang aparato sa ngipin na ginagamit upang itama ang pagkakakurba ng ngipin at mapabuti ang kagat.', 'cost' => 50000.00],
            ['name' => 'Braces Adjustment', 'short_description' => 'Inaayos ang braces.', 'description' => 'Isang pamamaraan upang higpitan o ayusin ang braces upang ipagpatuloy ang pag-aayos ng mga ngipin.', 'cost' => 5000.00],
        ];

        // Insert procedures and store their IDs
        $procedureIds = [];
        foreach ($procedures as $procedure) {
            $id = DB::table('procedures')->insertGetId(array_merge($procedure, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $procedureIds[$procedure['name']] = $id;
        }

        // Update cant_combine with procedure IDs
        $cantCombineMap = [
            'Teeth Cleaning' => [],
            'Tooth Extraction' => [$procedureIds['Braces Adjustment'], $procedureIds['Cavity Filling']],
            'Cavity Filling' => [$procedureIds['Tooth Extraction']],
            'Root Canal' => [],
            'Dental Implants' => [$procedureIds['Braces']],
            'Braces' => [$procedureIds['Dental Implants']],
            'Braces Adjustment' => [$procedureIds['Tooth Extraction']],
        ];

        // Update procedures with cant_combine
        foreach ($cantCombineMap as $name => $cantCombine) {
            DB::table('procedures')
                ->where('name', $name)
                ->update(['cant_combine' => json_encode($cantCombine)]);
        }
    }
}
