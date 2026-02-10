<?php
// Hataları göster
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Depolama Bağlantısı ve İzin Onarıcı</h2>";

// Hedef klasör (Dosyaların asıl durduğu yer)
// public/index.php'nin bir üstündeki storage/app/public klasörü
$targetFolder = __DIR__ . '/../storage/app/public';

// Linkin oluşacağı yer (Tarayıcının baktığı yer)
$linkFolder = __DIR__ . '/storage';

echo "<b>Hedef (Asıl Yer):</b> " . $targetFolder . "<br>";
echo "<b>Link (Kısayol):</b> " . $linkFolder . "<br><hr>";

// 1. Hedef klasör var mı kontrol et
if (!file_exists($targetFolder)) {
    echo "❌ HATA: Asıl storage klasörü bulunamadı! Yol yanlış olabilir.<br>";
    echo "Beklenen yol: " . realpath(__DIR__ . '/../') . "/storage/app/public<br>";
    exit;
} else {
    echo "✅ Hedef klasör mevcut.<br>";
}

// 2. Eski link varsa sil (Bazen yanlış yere bakar)
if (file_exists($linkFolder)) {
    echo "⚠️ Eski bir 'storage' klasörü/linki bulundu. Siliniyor...<br>";
    // Sembolik link ise unlink, gerçek klasör ise rmdir gerekir ama genelde linktir.
    if(is_link($linkFolder)) {
        unlink($linkFolder);
        echo "✅ Eski link silindi.<br>";
    } else {
        echo "❌ DİKKAT: 'public/storage' isminde GERÇEK bir klasör var. Bunu manuel silmelisin!<br>";
        exit;
    }
}

// 3. Sembolik Linki Oluştur
if (symlink($targetFolder, $linkFolder)) {
    echo "✅ <b>BAŞARILI: Sembolik link (Kısayol) oluşturuldu!</b><br>";
} else {
    echo "❌ HATA: Link oluşturulamadı. Sunucu izin vermiyor olabilir.<br>";
}

// 4. İzinleri Ayarla (403 Hatasının İlacı)
echo "<hr><h3>İzin Kontrolü (Chmod)</h3>";

try {
    // Asıl klasörün izinlerini 755 yap (Okunabilir)
    chmod($targetFolder, 0755);
    echo "✅ Ana klasör izinleri 0755 yapıldı.<br>";
    
    // Altındaki tüm dosyaları düzeltmeye çalış (Recursive)
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($targetFolder));
    foreach ($iterator as $item) {
        if ($item->isDir()) {
            chmod($item, 0755);
        } else {
            chmod($item, 0644);
        }
    }
    echo "✅ Alt dosya ve klasör izinleri düzeltildi (Dir: 755, File: 644).<br>";
    
} catch (Exception $e) {
    echo "⚠️ İzinler otomatik düzeltilemedi (Manuel yapman gerekebilir): " . $e->getMessage() . "<br>";
}

echo "<br><br>👉 <a href='/admin/jobs'>Panele Dön ve Resmi Dene</a>";
?>