# Canlıya Alım Rehberi (GitHub Kullanılmıyor)

Bu dosya, **Adet gösterimi** ve **İstasyon ataması** özelliklerinin canlı sunucuya alınması için yapılacakları ve veritabanı sorgularını içerir.

---

## 0. Yerel (local) ortamda çalıştırma

Projeyi **bilgisayarınızda** çalıştırırken aşağıdakilere dikkat edin.

### 0.1. “Storage path” / “No existing directory” hatası

**Belirti:** `There is no existing directory at "/home/castlera/public_html/storage/logs"` (veya benzeri sunucu yolu).

**Sebep:** `bootstrap/cache/config.php` dosyası canlı sunucuda oluşturulmuş ve içinde sunucu yolları var. Yerelde Laravel bu yolu kullanmaya çalışır ve hata verir.

**Çözüm:** Config önbelleğini temizleyin (bu dosya projede silindi, tekrar oluşursa aynısını yapın):

```bash
php artisan config:clear
```

Veya `bootstrap/cache/config.php` dosyasını elle silin. Sonrasında Laravel yerel yolları kullanır.

### 0.2. Migrate hatası: “Access denied” (veritabanı)

**Belirti:** `Access denied for user 'castlera_kaleops'@'localhost'`.

**Sebep:** `.env` dosyasında canlı sunucu veritabanı kullanıcı adı/şifresi yazıyor; yerel MySQL’de bu kullanıcı yok.

**Çözüm:** Yerelde kendi MySQL bilgilerinizi kullanın. `.env` içinde şunları **yerel** değerlerle güncelleyin:

```env
DB_DATABASE=yerel_veritabani_adiniz
DB_USERNAME=root
DB_PASSWORD=yerel_mysql_sifreniz
```

(Örnek: `DB_DATABASE=kaleops_local`, `DB_USERNAME=root`, `DB_PASSWORD=` boş veya kendi şifreniz.)

Veritabanını oluşturduktan sonra:

```bash
php artisan migrate
```

**Özet:** Yerelde çalışması için (1) `php artisan config:clear`, (2) `.env` içinde yerel DB bilgileri, (3) `php artisan migrate`.

---

## 1. Yapılan Değişikliklerin Özeti

| Dosya | Değişiklik |
|-------|------------|
| `resources/views/operator/index.blade.php` | Her iş kartında **Adet** bilgisi gösteriliyor (CAM, Lazer, CMM, Tesviye, Planning, Paketleme, Lojistik, Muhasebe tüm ekranlarda). |
| `database/migrations/2026_02_09_000001_add_assign_stations_to_jobs_table.php` | **Yeni migration:** `jobs` tablosuna `assign_cam`, `assign_lazer`, `assign_cmm`, `assign_tesviye` sütunları ekleniyor. |
| `app/Models/Job.php` | Yeni alanlar fillable/casts'a eklendi; `hasAllAssignedProductionStationsFilled()` metodu eklendi. |
| `resources/views/admin/jobs/index.blade.php` | Yeni iş emri formuna **"Bu işte süre girecek istasyonlar"** (CAM, Lazer, CMM, Tesviye) checkbox'ları eklendi. |
| `app/Http/Controllers/Admin/JobController.php` | İş kaydederken istasyon atamaları alınıyor ve en az bir istasyon zorunlu. |
| `app/Http/Controllers/OperatorController.php` | Sadece atanmış istasyonlara iş listeleniyor; planning aşaması atanmış istasyonlar dolunca açılıyor. |

---

## 2. Canlıya Alımda Sırayla Yapılacaklar

### Adım 1: Dosyaları sunucuya atın

Değişen ve yeni dosyaları canlı sunucudaki proje klasörüne kopyalayın (FTP/SFTP veya panel üzerinden):

- `app/Models/Job.php`
- `app/Http/Controllers/Admin/JobController.php`
- `app/Http/Controllers/OperatorController.php`
- `resources/views/admin/jobs/index.blade.php`
- `resources/views/operator/index.blade.php`
- `database/migrations/2026_02_09_000001_add_assign_stations_to_jobs_table.php`

### Adım 2: Veritabanı güncellemesi (önemli: önce bu)

**Önce** aşağıdaki SQL’i canlı veritabanında çalıştırın. Sütunlar eklenmeden yeni kod çalışırsa operatör listesi sayfasında hata alırsınız.

#### Seçenek A: Laravel migration ile (tercih edilen)

Sunucuda proje kökünde (örn. `public_html`):

```bash
php artisan migrate --force
```

`--force` canlı ortamda “production” uyarısını atlamak içindir.

**Beklenen çıktı örneği:**

```
Migrating: 2026_02_09_000001_add_assign_stations_to_jobs_table
Migrated:  2026_02_09_000001_add_assign_stations_to_jobs_table (XXXms)
```

#### Seçenek B: SQL’i elle çalıştırma (phpMyAdmin / MySQL client)

Migration çalıştıramıyorsanız aşağıdaki SQL’i veritabanında **tek seferde** çalıştırın. Tablo adı `jobs`; farklıysa `jobs` kısmını kendi tablo adınızla değiştirin.

**MySQL / MariaDB:**

```sql
ALTER TABLE jobs
  ADD COLUMN assign_cam TINYINT(1) NOT NULL DEFAULT 1 AFTER current_stage,
  ADD COLUMN assign_lazer TINYINT(1) NOT NULL DEFAULT 1 AFTER assign_cam,
  ADD COLUMN assign_cmm TINYINT(1) NOT NULL DEFAULT 1 AFTER assign_lazer,
  ADD COLUMN assign_tesviye TINYINT(1) NOT NULL DEFAULT 1 AFTER assign_cmm;
```

**Sorgu sonrası kontrol (isteğe bağlı):**

```sql
DESCRIBE jobs;
```

veya

```sql
SHOW COLUMNS FROM jobs LIKE 'assign_%';
```

**Beklenen:** `assign_cam`, `assign_lazer`, `assign_cmm`, `assign_tesviye` sütunları görünmeli, hepsi `DEFAULT 1` (true) olmalı. Mevcut tüm işler otomatik olarak dört istasyona da atanmış kabul edilir.

### Adım 3: View/cache temizliği (Blade kullanıyorsanız)

Sunucuda proje kökünde:

```bash
php artisan view:clear
php artisan cache:clear
```

### Adım 4: Kontrol listesi

- [ ] Veritabanında `assign_*` sütunları eklendi.
- [ ] Güncel PHP dosyaları ve view’lar sunucuda.
- [ ] Admin panel → Yeni iş emri: “Bu işte süre girecek istasyonlar” alanı görünüyor, en az bir istasyon seçilmeden kayıt hata veriyor.
- [ ] Operatör paneli: CAM/Lazer/CMM/Tesviye giriş yapan kullanıcılar sadece kendilerine atanmış işleri görüyor.
- [ ] Her iş kartında **Adet** bilgisi görünüyor.

---

## 3. Geri Alma (gerekirse)

İstasyon ataması özelliğini geri almak için önce kodu eski haline getirir, sonra sütunları kaldırırsınız:

```sql
ALTER TABLE jobs
  DROP COLUMN assign_cam,
  DROP COLUMN assign_lazer,
  DROP COLUMN assign_cmm,
  DROP COLUMN assign_tesviye;
```

---

## 4. Kısa Özet: Ne çalıştırmalıyım?

1. **Dosyaları** sunucuya at.
2. **Veritabanı:** `php artisan migrate --force` **veya** yukarıdaki `ALTER TABLE jobs ADD COLUMN ...` SQL’ini çalıştır.
3. **Cache:** `php artisan view:clear` ve `php artisan cache:clear`.

Önce SQL/migration, sonra güncel kod ile canlıya alım yaparsanız hata riski en aza iner.
