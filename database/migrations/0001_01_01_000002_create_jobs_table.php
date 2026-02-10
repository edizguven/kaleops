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
    Schema::dropIfExists('jobs'); // Temiz başlangıç

    Schema::create('jobs', function (Blueprint $table) {
        $table->id();
        $table->string('job_no')->unique(); // İş No
        $table->string('title');            // Başlık
        $table->string('image_path');      // Resim
        $table->string('pdf_path');        // Dosya
        
        // --- İŞ AKIŞ DURUMU ---
        // İş şu an hangi aşamada? (Örn: 'cam', 'cmm', 'tesviye', 'completed')
        // Varsayılan olarak ilk durak 'cam' olsun mu? Yoksa admin mi başlatsın?
        // Senin senaryonda Admin girince süreç başlıyor, ilk durak CAM diyelim.
        $table->string('current_stage')->default('cam'); 

        // --- BİRİM VERİLERİ ---
        
        // 1. CAM İstasyonu
        $table->integer('cam_minutes')->nullable(); // Dakika cinsinden süre
        
        // 2. CMM İstasyonu
        $table->integer('cmm_minutes')->nullable();
        
        // 3. Tesviye İstasyonu
        $table->integer('tesviye_minutes')->nullable();
        
        // 4. Üretim Planlama
        $table->date('planning_date')->nullable(); // Termin Tarihi
        
        // 5. Paketleme
        // Seçenekler: 'small', 'medium', 'large' (Settings tablosundan çekilecek ama burada string tutabiliriz)
        $table->string('packaging_type')->nullable(); 
        
        // 6. İrsaliye
        $table->string('delivery_note_path')->nullable(); // Dosya yolu
        
        // 7. Muhasebe
        $table->string('invoice_path')->nullable(); // Fatura Dosyası
        $table->decimal('total_amount', 10, 2)->nullable(); // Toplam Tutar
        $table->decimal('paid_amount', 10, 2)->nullable();  // Ödenen Tutar
        $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid'); // Tahsilat Durumu

        // Genel Onay (Admin kilitlediğinde true olacak)
        $table->boolean('is_completed')->default(false); 

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};