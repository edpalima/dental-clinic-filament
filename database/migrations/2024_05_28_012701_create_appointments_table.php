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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->text('procedures')->nullable();
            $table->json('procedures_data')->nullable();
            $table->foreignId('time_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->string('payment_options')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('account_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('status')->nullable()->default('PENDING');
            $table->string('approved_by')->nullable();
            $table->boolean('no_show')->default(false);
            $table->boolean('archived')->default(false);
            $table->boolean('agreement_accepted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
