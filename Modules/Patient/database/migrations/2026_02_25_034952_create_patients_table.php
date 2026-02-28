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
            $table->foreignId('user_id');
            $table->string('norm')->index();
            $table->string('nik')->nullable();
            $table->string('kap', 32)->nullable();
            $table->string('name');
            $table->string('panggilan')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('relation');
            $table->boolean('is_default')->default(false);
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
