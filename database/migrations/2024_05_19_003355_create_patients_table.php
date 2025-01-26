<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('nickname')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('address')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('occupation')->nullable();
            $table->string('dental_insurance')->nullable();
            $table->date('insurance_effective_date')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('referrer')->nullable();
            $table->string('reason')->nullable();
            $table->string('previous_dentist')->nullable();
            $table->date('last_dental_visit')->nullable();

            // Physician
            $table->string('physician_name')->nullable();
            $table->string('physician_specialty')->nullable();
            $table->string('physician_office')->nullable();
            $table->string('physician_number')->nullable();

            // Medical History
            $table->boolean('is_good_health')->default(false);
            $table->boolean('is_medical_treatment')->default(false);
            $table->string('is_medical_treatment_name')->nullable();
            $table->boolean('is_illness_operated')->default(false);
            $table->string('is_illness_operated_name')->nullable();
            $table->boolean('is_hospitalized')->default(false);
            $table->string('is_hospitalized_name')->nullable();
            $table->boolean('is_has_medication')->default(false);
            $table->string('is_has_medication_name')->nullable();
            $table->boolean('is_using_tobacco')->default(false);
            $table->boolean('is_has_vice')->default(false);
            $table->text('check_allergies')->nullable();
            $table->string('bleeding_time')->nullable();
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('is_nursing')->default(false);
            $table->boolean('is_taking_pills')->default(false);
            $table->string('blood_type')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->text('check_illness')->nullable();
            $table->boolean('check_consent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
