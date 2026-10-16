#!/bin/bash
set -e

echo "Menyiapkan server untuk PT PAD Logistik..."

# Membersihkan cache lama
php artisan config:clear
php artisan route:clear

# Menunggu database PostgreSQL siap (maksimal 30 detik)
echo "Menunggu koneksi database..."
MAX_RETRIES=10
RETRY_COUNT=0

until php artisan db:show --json > /dev/null 2>&1; do
  RETRY_COUNT=$((RETRY_COUNT + 1))
  if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
    echo "ERROR: Database tidak dapat dihubungi setelah $MAX_RETRIES percobaan. Pastikan DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD sudah benar di Render Dashboard."
    exit 1
  fi
  echo "  Database belum siap, mencoba lagi dalam 3 detik... ($RETRY_COUNT/$MAX_RETRIES)"
  sleep 3
done

echo "Database terhubung!"

# Menjalankan migrasi database ke PostgreSQL (dengan force karena ini env production)
echo "Menjalankan migrasi Database..."
php artisan migrate --force

# Menjalankan seeder hanya jika tabel users masih kosong
echo "Mengecek data awal..."
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
  echo "Menjalankan Database Seeder..."
  php artisan db:seed --force
else
  echo "Data sudah ada ($USER_COUNT user), seeder dilewati."
fi

# Membersihkan cache setelah tabel dibuat
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Menjalankan server Apache untuk melayani aplikasi
echo "Menyalakan Server Apache..."
exec apache2-foreground
