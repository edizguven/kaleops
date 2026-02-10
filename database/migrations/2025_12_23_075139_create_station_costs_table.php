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
        Schema::create('station_costs', function (Blueprint $table) {
            $table->id();
            $table->string('station_code')->unique(); // 'cam', 'cmm', 'tesviye', 'planning', 'logistics'
            $table->string('station_name'); // 'CAM İstasyonu', 'CMM Ölçüm', vb.
            $table->decimal('cost_per_minute', 10, 2)->nullable(); // Dakika bazlı istasyonlar için
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_costs');
    }
};
