-- ============================================================
-- jobs tablosuna quantity (adet) sütunu ekleme
-- Local (SQLite) veya Production (MySQL) için uygun komutu kullanın.
-- ============================================================

-- SQLite (local):
-- ALTER TABLE jobs ADD COLUMN quantity INTEGER NOT NULL DEFAULT 1;

-- MySQL (production / cPanel):
ALTER TABLE jobs ADD COLUMN quantity INT UNSIGNED NOT NULL DEFAULT 1 AFTER title;
