<?php
/**
 * Canlı sunucu kontrolü – Beyaz sayfa / hata ayıklama için.
 * Tarayıcıda açın: https://siteniz.com/deploy-check.php
 * İşiniz bitince SİLİN (güvenlik).
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = dirname(__DIR__);
$checks = [];

// 1. PHP sürümü
$checks['PHP Sürümü'] = PHP_VERSION . ' (Laravel 11 için 8.2+ gerekir)';

// 2. vendor var mı?
$vendorPath = $root . '/vendor/autoload.php';
$checks['vendor/autoload.php'] = file_exists($vendorPath) ? '✓ Var' : '✗ YOK – Sunucuda "composer install --no-dev" çalıştırın';

// 3. .env var mı?
$envPath = $root . '/.env';
$checks['.env dosyası'] = file_exists($envPath) ? '✓ Var' : '✗ YOK – Sunucuda .env oluşturun';

// 4. storage yazılabilir mi?
$storagePath = $root . '/storage';
$checks['storage yazılabilir'] = is_writable($storagePath) ? '✓ Evet' : '✗ Hayır – chmod 775 storage ve storage/logs';

// 5. bootstrap/cache yazılabilir mi?
$cachePath = $root . '/bootstrap/cache';
$checks['bootstrap/cache yazılabilir'] = is_writable($cachePath) ? '✓ Evet' : '✗ Hayır – chmod 775 bootstrap/cache';

// 6. Son log hatası (varsa)
$logPath = $root . '/storage/logs/laravel.log';
$lastError = '';
if (file_exists($logPath) && is_readable($logPath)) {
    $lines = file($logPath);
    $last = array_slice($lines, -50);
    $lastError = implode('', $last);
} else {
    $lastError = 'Log dosyası yok veya okunamıyor.';
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KaleOps – Sunucu Kontrol</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        table { width: 100%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #1e40af; color: #fff; }
        .ok { color: #059669; }
        .fail { color: #dc2626; font-weight: bold; }
        pre { background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 8px; overflow-x: auto; font-size: 12px; }
        .warn { background: #fef3c7; border: 1px solid #f59e0b; padding: 12px; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>KaleOps – Sunucu Kontrol</h1>
    <p>Bu sayfayı gördüğünüze göre PHP çalışıyor. Aşağıdaki maddeleri düzeltin, sonra <strong>bu dosyayı silin</strong> (deploy-check.php).</p>
    <table>
        <thead>
            <tr><th>Kontrol</th><th>Durum</th></tr>
        </thead>
        <tbody>
            <?php foreach ($checks as $label => $value): ?>
            <tr>
                <td><?= htmlspecialchars($label) ?></td>
                <td class="<?= strpos($value, '✗') !== false ? 'fail' : 'ok' ?>"><?= htmlspecialchars($value) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Son log (storage/logs/laravel.log)</h2>
    <pre><?= htmlspecialchars($lastError ?: '(boş veya hata yok)') ?></pre>
    <div class="warn">
        <strong>Güvenlik:</strong> Sorunları giderdikten sonra <code>public/deploy-check.php</code> dosyasını sunucudan silin.
    </div>
</body>
</html>
