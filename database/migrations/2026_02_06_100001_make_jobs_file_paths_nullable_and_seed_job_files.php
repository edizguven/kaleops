<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Eski image_path ve pdf_path'i job_files'a taşı (varsa)
        $jobs = DB::table('jobs')->get();
        foreach ($jobs as $job) {
            if (!empty($job->image_path)) {
                DB::table('job_files')->insert([
                    'job_id' => $job->id,
                    'file_path' => $job->image_path,
                    'original_name' => 'Teknik Dosya 1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if (!empty($job->pdf_path)) {
                DB::table('job_files')->insert([
                    'job_id' => $job->id,
                    'file_path' => $job->pdf_path,
                    'original_name' => 'Teknik Dosya 2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
            $table->string('pdf_path')->nullable()->change();
        });

        // Kopyaladıktan sonra eski sütunları temizle (çift görünmesin)
        DB::table('jobs')->update(['image_path' => null, 'pdf_path' => null]);
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('image_path')->nullable(false)->change();
            $table->string('pdf_path')->nullable(false)->change();
        });
    }
};
