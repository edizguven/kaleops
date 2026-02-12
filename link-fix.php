<?php
/**
 * KaleOps – Sembolik Bağlantı (Symlink) Oluşturucu
 * Bu dosya, build ve img klasörlerini ana dizine bağlar.
 */

header('Content-Type: text/html; charset=utf-8');
echo "<h2>🚀 Sembolik Bağlantı Onarıcı</h2>";

// Tanımlar (Bulunulan klasöre göre ayarlanmıştır)
$links = [
    'build' => __DIR__ . '/public/build',
    'img'   => __DIR__ . '/public/img'
];

foreach ($links as $linkName => $targetPath) {
    $linkPath = __DIR__ . '/' . $linkName;

    echo "<b>İşlem:</b> $linkName -> $targetPath <br>";

    // 1. Hedef klasör var mı kontrol et
    if (!file_exists($targetPath)) {
        echo "❌ HATA: Hedef klasör ($targetPath) bulunamadı! Lütfen önce dosyaları yükleyin.<br>";
        continue;
    }

    // 2. Eski link veya dosya varsa sil (Çakışmayı önlemek için)
    if (file_exists($linkPath) || is_link($linkPath)) {
        echo "⚠️ '$linkName' zaten mevcut. Eski olan siliniyor...<br>";
        if (is_link($linkPath)) {
            unlink($linkPath);
        } else {
            // Eğer gerçek bir klasörse içini boşaltıp silmek gerekebilir, 
            // risk almamak için sadece uyarı veriyoruz.
            echo "❌ DİKKAT: '$linkName' isminde gerçek bir klasör var. Lütfen onu manuel silin veya adını değiştirin.<br>";
            continue;
        }
    }

    // 3. Sembolik Linki Oluştur
    if (symlink($targetPath, $linkPath)) {
        echo "✅ <b>BAŞARILI:</b> $linkName bağlantısı oluşturuldu.<br>";
    } else {
        echo "❌ <b>HATA:</b> Bağlantı oluşturulamadı. Sunucu izinlerini kontrol edin.<br>";
    }
    echo "<hr>";
}

echo "İşlem bitti. Eğer her şey yeşilse Admin panelindeki butonların ve resimlerin düzelmiş olması gerekir.";
?>