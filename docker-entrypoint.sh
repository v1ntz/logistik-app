#!/bin/bash
set -e

echo "Menyiapkan server untuk PT PAD Logistik..."

# Hapus file .env jika ada di dalam image (harus pakai env vars dari Render Dashboard)
if [ -f ".env" ]; then
  echo "Menghapus .env dari image, menggunakan environment variables dari Render..."
  rm -f .env
fi

# Hapus semua config cache yang mungkin berisi kredensial lama
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes*.php
rm -f bootstrap/cache/events.php

# Membersihkan cache lama
php artisan config:clear
php artisan route:clear

# Debug: tampilkan nilai env vars yang akan dipakai
echo "Konfigurasi database yang digunakan:"
echo "  DB_CONNECTION = $DB_CONNECTION"
echo "  DB_HOST       = $DB_HOST"
echo "  DB_PORT       = $DB_PORT"
echo "  DB_DATABASE   = $DB_DATABASE"
echo "  DB_USERNAME   = $DB_USERNAME"

# Menunggu database PostgreSQL siap (maksimal 30 detik)
echo "Menunggu koneksi database..."
MAX_RETRIES=10
RETRY_COUNT=0

until php -r "
try {
    \$dsn = 'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE');
    \$pdo = new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), [PDO::ATTR_TIMEOUT => 5]);
    echo 'OK';
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage();
    exit(1);
}
" 2>/dev/null | grep -q 'OK'; do
  RETRY_COUNT=$((RETRY_COUNT + 1))
  if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
    echo "ERROR: Database tidak dapat dihubungi setelah $MAX_RETRIES percobaan."
    exit 1
  fi
  echo "  Database belum siap, mencoba lagi dalam 3 detik... ($RETRY_COUNT/$MAX_RETRIES)"
  sleep 3
done

echo "Database terhubung!"

# Menjalankan migrasi database ke PostgreSQL (dengan force karena ini env production)
echo "Menjalankan migrasi Database..."
php artisan migrate --force

# Menjalankan seeder hanya jika tabel logbooks masih kosong
echo "Mengecek data awal..."
LOGBOOK_COUNT=$(php -r "
try {
    \$dsn = 'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE');
    \$pdo = new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    \$stmt = \$pdo->query('SELECT COUNT(*) FROM logbooks');
    echo \$stmt->fetchColumn();
} catch (Exception \$e) {
    echo '0';
}
" 2>/dev/null)

if [ "$LOGBOOK_COUNT" = "0" ] || [ -z "$LOGBOOK_COUNT" ]; then
  echo "Menjalankan Database Seeder (30 data dummy)..."
  php artisan db:seed --force
else
  echo "Data logbook sudah ada ($LOGBOOK_COUNT record), seeder dilewati."
fi

# Cache setelah semua siap
php artisan config:cache
php artisan route:cache

# Menjalankan server Apache untuk melayani aplikasi
echo "Menyalakan Server Apache..."
exec apache2-foreground
