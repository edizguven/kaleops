<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin iş emri oluştururken "bu işe hangi istasyonlar süre girecek" seçimi.
     * Sadece atanmış istasyonlardaki operatörler bu işi görür.
     * Varsayılan true = mevcut davranış korunur (tüm istasyonlar görür).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->boolean('assign_cam')->default(true)->after('current_stage');
            $table->boolean('assign_lazer')->default(true)->after('assign_cam');
            $table->boolean('assign_cmm')->default(true)->after('assign_lazer');
            $table->boolean('assign_tesviye')->default(true)->after('assign_cmm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['assign_cam', 'assign_lazer', 'assign_cmm', 'assign_tesviye']);
        });
    }
};
