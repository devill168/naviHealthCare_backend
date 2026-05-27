<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->string('director_name')->nullable()->after('hf_image');
            $table->string('contact_phone', 30)->nullable()->after('director_name');
        });
    }

    public function down(): void
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->dropColumn(['director_name', 'contact_phone']);
        });
    }
};