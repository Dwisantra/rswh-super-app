<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('clinic_name');
            $table->string('doctor_name');
            $table->dateTime('schedule_at');
            $table->string('complaint', 255)->nullable();
            $table->string('queue_number', 20)->nullable();
            $table->string('status', 30)->default('pending');
            $table->json('simrs_response')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'schedule_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_registrations');
    }
};
