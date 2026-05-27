<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hcs', function (Blueprint $table) {
            $table->string('name_director')->nullable()->after('hc_code');
            $table->string('phone')->nullable()->after('name_director');
            $table->string('image')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('hcs', function (Blueprint $table) {
            $table->dropColumn(['name_director', 'phone', 'image']);
        });
    }
};