<?php
/**
 * KaleOps – Gelişmiş Migration Çalıştırıcı
 * Bu dosya, veritabanındaki yeni değişiklikleri (quantity, job_files vb.) 
 * canlı sunucuya yansıtmak için kullanılır.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // 1. Laravel Yollarını Belirle (public_html/public içinde olduğunuzu varsayar)
    $autoload = __DIR__ . '/../vendor/autoload.php';
    $appPath = __DIR__ . '/../bootstrap/app.php';

    if (!file_exists($autoload)) {
        die("❌ Hata: vendor/autoload.php bulunamadı. Lütfen dosyaların yüklendiğinden emin olun.");
    }

    // 2. Laravel'i Başlat
    require $autoload;
    $app = require_once $appPath;

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->handle(Illuminate\Http\Request::capture());

    echo "<h2>🚀 KaleOps Veritabanı Güncelleme Paneli</h2>";
    echo "Bağlantı kuruldu, işlemler başlatılıyor...<br><hr>";

    // 3. Migration'ları Çalıştır (--force canlı modda zorunludur)
    echo "<b>1. Adım: Migrationlar çalıştırılıyor...</b><br>";
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";

    // 4. (Opsiyonel) StationCostSeeder - Eğer yeni istasyon verileri eklenecekse
    // echo "<b>2. Adım: İstasyon maliyetleri güncelleniyor...</b><br>";
    // \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'StationCostSeeder', '--force' => true]);
    // echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";

    // 5. Cache Temizliği (Yeni tabloların model bazında tanınması için)
    echo "<b>Son Adım: Önbellek temizleniyor...</b><br>";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    
    echo "<br>✅ <b>Tüm işlemler başarıyla tamamlandı!</b>";
    echo "<br>👉 <a href='/admin/jobs'>İş Emirlerine Git ve Kontrol Et</a>";

} catch (\Exception $e) {
    echo "<div style='color:red; padding:20px; border:1px solid red;'>";
    echo "<h3>❌ Bir Hata Oluştu!</h3>";
    echo "Hata Mesajı: " . $e->getMessage();
    echo "</div>";
}