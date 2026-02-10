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
        Schema::create('packaging_costs', function (Blueprint $table) {
            $table->id();
            $table->string('package_type')->unique(); // 'Kucuk', 'Orta', 'Buyuk'
            $table->string('package_name'); // 'Küçük Paket', 'Orta Paket', 'Büyük Paket'
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_costs');
    }
};
