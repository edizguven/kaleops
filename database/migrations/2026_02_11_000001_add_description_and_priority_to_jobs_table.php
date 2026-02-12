<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Açıklama (opsiyonel, en az 150 karakter doldurulabilir) ve Öncelik (Düşük/Orta/Yüksek/Acil/Çok Acil).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('priority', 20)->nullable()->after('description'); // dusuk, orta, yuksek, acil, cok_acil
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['description', 'priority']);
        });
    }
};
