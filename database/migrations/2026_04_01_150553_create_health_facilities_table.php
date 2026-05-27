<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_facilities', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique();
            $table->string('name_km');
            $table->string('name_en')->nullable();

            $table->enum('type', ['PHD', 'NH', 'PH', 'OD', 'RH', 'HC', 'HP']);

            $table->foreignId('parent_id')
                ->nullable();

            $table->string('province_code', 20)->nullable();
            $table->string('district_code', 20)->nullable();
            $table->string('commune_code', 20)->nullable();
            $table->string('village_code', 20)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->text('hf_image')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['type', 'parent_id'], 'hf_type_parent_idx');
            $table->index(
                ['province_code', 'district_code', 'commune_code', 'village_code'],
                'hf_loc_idx'
            );
            $table->index('is_active', 'hf_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->dropForeign('health_facilities_parent_id_foreign');
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['province_code']);
            $table->dropForeign(['district_code']);
            $table->dropForeign(['commune_code']);
            $table->dropForeign(['village_code']);
        });

        Schema::dropIfExists('health_facilities');
    }
};
