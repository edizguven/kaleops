# Canlıda users tablosu: id NULL sorunu

Panelden eklenen kullanıcıların `id` değeri NULL kalıyorsa, canlıdaki `users` tablosunda **id** sütunu AUTO_INCREMENT değildir. Aşağıdaki SQL’i **phpMyAdmin → SQL** sekmesinde çalıştırın.

## 1. Önce id’si NULL olan kayıtları silin

```sql
DELETE FROM users WHERE id IS NULL;
```

## 2. id sütununu PRIMARY KEY + AUTO_INCREMENT yapın

```sql
ALTER TABLE users
  MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;
```

Eğer "Duplicate entry" veya primary key hatası alırsanız, önce mevcut primary key’i kaldırıp sonra tekrar deneyin (MySQL sürümüne göre gerekebilir):

```sql
-- Sadece gerekirse: önce PK kaldır (bazı sunucularda gerekir)
ALTER TABLE users DROP PRIMARY KEY;
ALTER TABLE users MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;
```

## 3. Sonuç

Bundan sonra panelden eklenen her yeni kullanıcıya otomatik artan bir `id` atanır.

**Not:** `created_at` / `updated_at` hâlâ `0000-00-00 00:00:00` veya "data too long" veriyorsa, bu sütunları da düzeltin:

```sql
ALTER TABLE users
  MODIFY COLUMN created_at TIMESTAMP NULL,
  MODIFY COLUMN updated_at TIMESTAMP NULL;
```
