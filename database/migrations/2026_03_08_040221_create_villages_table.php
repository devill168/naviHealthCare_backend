<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('province_code', 20);
            $table->string('district_code', 20);
            $table->string('commune_code', 20);
            // $table->string('od_code', 20);
            // $table->string('hc_code', 20);
            $table->string('village_name_en');
            $table->string('village_name_kh');
            $table->string('village_code', 20)->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedBigInteger('visit')->default(0);
            $table->timestamps();

            $table->foreign('province_code')
                ->references('province_code')
                ->on('provinces')
                ->cascadeOnDelete();

            $table->foreign('district_code')
                ->references('district_code')
                ->on('districts')
                ->cascadeOnDelete();

            $table->foreign('commune_code')
                ->references('commune_code')
                ->on('communes')
                ->cascadeOnDelete();

            // $table->foreign('od_code')
            //     ->references('od_code')
            //     ->on('ods')
            //     ->cascadeOnDelete();

            // $table->foreign('hc_code')
            //     ->references('hc_code')
            //     ->on('hcs')
            //     ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
