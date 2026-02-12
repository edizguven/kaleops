# Canlıya Alım: Torna + İstasyon Parça Detayları (phpMyAdmin SQL)

cPanel’de terminal olmadığı için **phpMyAdmin** üzerinden çalıştırmanız gereken SQL’ler aşağıdadır. Sırayla uygulayın.

---

## 1. Hangi dosyalar canlıya atılacak?

GitHub ile push/pull yapıyorsanız aynı commit’i canlıda çekin. Manuel atıyorsanız şu dosyaları güncelleyin:

- `app/Models/Job.php`
- `app/Models/JobStationDetail.php` **(yeni)**
- `app/Http/Controllers/Admin/JobController.php`
- `app/Http/Controllers/OperatorController.php`
- `resources/views/admin/jobs/index.blade.php`
- `resources/views/admin/jobs/show.blade.php`
- `resources/views/operator/index.blade.php`
- `routes/web.php`
- `database/migrations/2026_02_10_000001_add_torna_to_jobs_table.php` **(yeni)**
- `database/migrations/2026_02_10_000002_create_job_station_details_table.php` **(yeni)**
- `database/migrations/2026_02_10_100001_allow_multiple_job_station_details_per_station.php` **(yeni – çoklu parça)**

---

## 2. phpMyAdmin’de çalıştırılacak SQL’ler

Veritabanınızı seçin, **SQL** sekmesine girin ve aşağıdakileri **sırayla** çalıştırın.

### Sorgu 1: jobs tablosuna Torna sütunları

```sql
-- Torna istasyonu: atanabilir mi + süre (dakika)
ALTER TABLE jobs
  ADD COLUMN assign_torna TINYINT(1) NOT NULL DEFAULT 1 AFTER assign_tesviye,
  ADD COLUMN torna_minutes INT NULL AFTER tesviye_minutes;
```

**Hata alırsanız:** `assign_tesviye` yoksa önce mevcut “assign\_\*” migration’ınızı (CANLIYA_ALIM.md) çalıştırmış olun. Sütun adı farklıysa `AFTER assign_tesviye` kısmını kendi son sütununuza göre değiştirin (örn. `AFTER current_stage`).

---

### Sorgu 2: job_station_details tablosu

```sql
-- İstasyon bazlı parça bilgileri (Parça No, En, Boy, Yükseklik, Adet, Cinsi)
CREATE TABLE job_station_details (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  job_id BIGINT UNSIGNED NOT NULL,
  station VARCHAR(20) NOT NULL,
  parca_no VARCHAR(255) NULL,
  en VARCHAR(255) NULL,
  boy VARCHAR(255) NULL,
  yukseklik VARCHAR(255) NULL,
  adet INT UNSIGNED NULL,
  cinsi VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY job_station_unique (job_id, station),
  CONSTRAINT job_station_details_job_id_foreign
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);
```

---

### Sorgu 3: torna@firma.com kullanıcısı (isteğe bağlı)

Torna operatörü için kullanıcı oluşturmak istiyorsanız aşağıdaki SQL’i çalıştırın. **Şifre: `password`** (Laravel bcrypt ile hash’lenmiş).

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES (
  'Torna',
  'torna@firma.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'torna',
  NOW(),
  NOW()
);
```

**Not:** `users` tablonuzda `email_verified_at`, `remember_token`, `department_id`, `usertype` gibi zorunlu sütunlar varsa ve NULL kabul etmiyorsa, bu sütunları da ekleyin (NULL veya uygun varsayılan değer ile). Örnek:

```sql
INSERT INTO users (name, email, email_verified_at, password, remember_token, role, created_at, updated_at)
VALUES (
  'Torna',
  'torna@firma.com',
  NULL,
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  NULL,
  'torna',
  NOW(),
  NOW()
);
```

Zaten başka bir yolla (admin panel, seed vb.) kullanıcı ekliyorsanız Sorgu 3’ü atlayabilirsiniz; sadece rolün **torna** olması yeterli.

---

## 3. Canlıda kontrol

- Admin → Yeni iş emri: **“Bu işte süre girilecek istasyonlar”** içinde **Torna** seçeneği görünmeli.
- **torna@firma.com** ile giriş yapınca operatör paneline düşmeli; Torna süresi + Parça No, En, Boy, Adet, Cinsi alanları görünmeli.
- CAM / Lazer / CMM / Tesviye formlarında **Parça No, En, Boy, Yükseklik, Adet, Cinsi** alanları görünmeli.
- Bir işte bu alanlar doldurulup kaydedildikten sonra Admin → İş detay sayfasında **“İstasyon Parça Detayları”** tablosu ve **Torna** süresi görünmeli.

---

## 4. Kısa özet

1. Güncel dosyaları canlıya atın (veya GitHub’dan çekin).
2. phpMyAdmin’de **Sorgu 1** (jobs’a Torna sütunları) ve **Sorgu 2** (job_station_details tablosu) çalıştırın.
3. İsterseniz **Sorgu 3** ile torna@firma.com kullanıcısını ekleyin.
4. Cache: `php artisan cache:clear` ve `php artisan view:clear` (panel veya tek seferlik SSH ile).

Bu adımlardan sonra Torna ve parça detayları canlıda çalışır.

---

## 5. Güncelleme: Birden fazla parça (Parça Ekle)

İstasyonlarda **birden fazla parça** girilebilmesi için aşağıdaki SQL’i çalıştırın. (job_station_details tablosu zaten varsa.)

```sql
-- Çoklu parça: unique kısıtı kaldır (FK geçici kaldırılıp tekrar eklenir)
ALTER TABLE job_station_details DROP FOREIGN KEY job_station_details_job_id_foreign;
ALTER TABLE job_station_details DROP INDEX job_station_details_job_id_station_unique;
ALTER TABLE job_station_details ADD CONSTRAINT job_station_details_job_id_foreign
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE;
```

**Not:** Foreign key adı farklıysa (ör. `job_station_details_job_id_foreign` değilse) phpMyAdmin → job_station_details → Yapı sekmesinden FK adını kontrol edin ve ilk satırdaki adı buna göre değiştirin.
