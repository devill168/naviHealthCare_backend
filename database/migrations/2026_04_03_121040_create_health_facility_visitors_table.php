<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_facility_visitors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('registers')
                ->cascadeOnDelete();

            $table->foreignId('health_facility_id')
                ->constrained('health_facilities')
                ->cascadeOnDelete();

            $table->date('visit_date')->nullable();

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['health_facility_id']);
            $table->index(['visit_date']);
            $table->index(['user_id', 'health_facility_id', 'visit_date'], 'hfv_user_facility_visit_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_facility_visitors');
    }
};
