<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 32)->unique()->after('name');
            $table->string('norm', 32)->nullable()->unique()->after('nik');
            $table->string('family_code', 32)->nullable()->index()->after('norm');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'norm', 'family_code']);
        });
    }
};
