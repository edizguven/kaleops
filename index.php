<?php
/**
 * Laravel giriş noktası – Document root public_html olduğunda bu dosya çalışır.
 * public/index.php dosyasını yükler.
 */
$path = __DIR__ . '/public/index.php';
if (file_exists($path)) {
    require $path;
} else {
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Hata: public/index.php bulunamadı.';
    echo "\n\nDocument root olarak public_html/public klasörü ayarlanmalı veya bu dosya public klasörüyle aynı yerde olmalı.";
}
