<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patients')->insert([
            [
                'first_name' => 'Brooke',
                'middle_name' => 'Carla Vaughn',
                'last_name' => 'Rasmussen',
                'nickname' => 'Holly Bell',
                'religion' => 'Ipsam et culpa fuga',
                'nationality' => 'Voluptates velit od',
                'birth_date' => null,
                'gender' => 'male',
                'address' => 'Reprehenderit solut',
                'contact_no' => '409',
                'occupation' => 'Maiores suscipit mod',
                'dental_insurance' => 'Itaque dolore et ali',
                'insurance_effective_date' => '1972-05-03',
                'email' => 'patient@gmail.com',
                'password' => Hash::make('password'),
                'guardian_name' => 'Driscoll Cotton',
                'guardian_occupation' => 'Et quam aut aut repr',
                'referrer' => 'Incidunt minima mol',
                'reason' => 'Quia obcaecati ipsum',
                'previous_dentist' => 'Et aliqua Vel eius duis aut dolore praesentium ipsam quia ullam qui nisi aliquam adipisicing consectetur dignissimos',
                'last_dental_visit' => '2012-12-02',
                'physician_name' => 'Alexa Blankenship',
                'physician_specialty' => 'Reiciendis id vero rerum enim ea odit amet rerum eum facilis vel accusantium commodo',
                'physician_office' => 'Eum ut pariatur Pariatur Modi qui ea est non ducimus qui in quibusdam dolor occaecat animi fugit ullam in',
                'physician_number' => '810',
                'is_good_health' => false,
                'is_medical_treatment' => true,
                'is_medical_treatment_name' => null,
                'is_illness_operated' => true,
                'is_illness_operated_name' => 'David Rocha',
                'is_hospitalized' => false,
                'is_hospitalized_name' => 'Yasir Downs',
                'is_has_medication' => false,
                'is_has_medication_name' => 'Teagan Wise',
                'is_using_tobacco' => true,
                'is_has_vice' => true,
                'check_allergies' => '["local_anesthetic","aspirin","others"]',
                'bleeding_time' => 'Et voluptatem cumque',
                'is_pregnant' => true,
                'is_nursing' => false,
                'is_taking_pills' => false,
                'blood_type' => 'A-',
                'blood_pressure' => 'Pariatur Qui totam',
                'check_illness' => '["epilepsy_convulsions","aids_hiv","fainting_seizure","rapid_weight_loss","joint_replacement_implant","heart_surgery","heart_attack","heart_murmur","hepatitis_liver_disease","rheumatic_fever","hay_fever_allergies","hepatitis_jaundice","tuberculosis","stroke","cancer_tumors","anemia","asthma","blood_diseases","head_injuries"]',
                'check_consent' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Additional patients can be added here
        ]);
    }
}
