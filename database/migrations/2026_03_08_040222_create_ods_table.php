<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ods', function (Blueprint $table) {
            $table->id();
            $table->string('province_code', 20);
            $table->string('district_code', 20);
            $table->string('commune_code', 20);
            $table->string('od_name_en');
            $table->string('od_name_kh');
            $table->string('od_code', 20)->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedBigInteger('visit')->default(0);
            $table->timestamps();

            $table->foreign('province_code')
                ->references('province_code')
                ->on('provinces')
                ->onDelete('cascade');

            $table->foreign('district_code')
                ->references('district_code')
                ->on('districts')
                ->onDelete('cascade');

            $table->foreign('commune_code')
                ->references('commune_code')
                ->on('communes')
                ->onDelete('cascade');

            $table->index(['province_code', 'district_code', 'commune_code', 'od_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ods');
    }
};