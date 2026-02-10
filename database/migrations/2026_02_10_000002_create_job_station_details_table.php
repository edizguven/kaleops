<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_station_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->string('station', 20); // cam, lazer, cmm, tesviye, torna
            $table->string('parca_no')->nullable();
            $table->string('en')->nullable();
            $table->string('boy')->nullable();
            $table->string('yukseklik')->nullable(); // Torna'da boş
            $table->unsignedInteger('adet')->nullable();
            $table->string('cinsi')->nullable();
            $table->timestamps();
            $table->unique(['job_id', 'station']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_station_details');
    }
};
