<?php
use Illuminate\Support\Facades\Artisan;

// Laravel'i başlatmak için gerekli
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Cache temizleme komutları
Artisan::call('config:clear');
Artisan::call('cache:clear');
Artisan::call('config:cache');

echo "Ayarlar sıfırlandı ve yeniden yüklendi!";