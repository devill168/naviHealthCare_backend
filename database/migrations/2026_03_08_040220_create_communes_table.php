<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('communes')) {
            Schema::create('communes', function (Blueprint $table) {
                $table->id();
                $table->string('province_code', 20);
                $table->string('district_code', 20);
                $table->string('commune_name_en');
                $table->string('commune_name_kh');
                $table->string('commune_code', 20)->unique();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->unsignedBigInteger('visit')->default(0);
                $table->timestamps();

                $table->index(['province_code', 'district_code', 'commune_code']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};