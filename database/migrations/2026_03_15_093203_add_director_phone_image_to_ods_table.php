<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ods', function (Blueprint $table) {
            $table->string('name_director')->nullable()->after('od_code');
            $table->string('phone', 30)->nullable()->after('name_director');
            $table->string('image')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('ods', function (Blueprint $table) {
            $table->dropColumn(['name_director', 'phone', 'image']);
        });
    }
};