# SQLite → MySQL Geçiş Notu

Uygulama artık **MySQL** kullanacak şekilde ayarlandı (`.env` içinde `DB_CONNECTION=mysql`).

## Hiçbir şeyin bozulmaması için

### 1. MySQL'in çalıştığından emin olun
- Local: XAMPP/MAMP/WAMP veya `mysql` servisi açık olmalı.
- Canlı: cPanel / hosting tarafında MySQL zaten açıktır.

### 2. Veritabanının var olduğundan emin olun
- `.env` içindeki `DB_DATABASE=castlera_kaleops` için bu isimde bir veritabanı oluşturulmuş olmalı.
- Yoksa MySQL’de (phpMyAdmin veya konsol):
  ```sql
  CREATE DATABASE castlera_kaleops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

### 3. Tabloları oluşturun (migration)
- İlk kez MySQL kullanıyorsanız veya boş bir veritabanı kullanıyorsanız:
  ```bash
  php artisan migrate --force
  ```

### 4. Admin kullanıcısı (local)
- Local ortamda tarayıcıdan: `http://localhost/__create_admin` (veya projenizin adresi)
- Giriş: **admin@firma.com** / **password**

### 5. Eski SQLite verisini taşımak isterseniz
- SQLite’taki mevcut veriyi MySQL’e taşımak için:
  - Ya migration’ları MySQL’de çalıştırıp sonra seed / `__create_admin` ile kullanıcıları yeniden oluşturursunuz (basit yol),
  - Ya da bir “export from SQLite / import to MySQL” aracı kullanırsınız (daha karmaşık).

**Özet:** `.env` MySQL’e çevrildi. MySQL’de veritabanı var ve `php artisan migrate` çalıştırıldıysa uygulama hiçbir şey bozulmadan MySQL ile çalışır. SQLite dosyası (`database/database.sqlite`) silinmez; sadece artık kullanılmaz.
