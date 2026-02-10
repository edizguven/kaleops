<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aynı iş + aynı istasyon için birden fazla parça satırına izin ver (unique kaldır).
     */
    public function up(): void
    {
        Schema::table('job_station_details', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
        Schema::table('job_station_details', function (Blueprint $table) {
            $table->dropUnique(['job_id', 'station']);
        });
        Schema::table('job_station_details', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('job_station_details', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
        Schema::table('job_station_details', function (Blueprint $table) {
            $table->unique(['job_id', 'station']);
            $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
        });
    }
};
