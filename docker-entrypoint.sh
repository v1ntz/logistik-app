#!/bin/bash
set -e

echo "Menyiapkan server untuk PT PAD Logistik..."

# Membersihkan cache lama
php artisan config:clear
php artisan route:clear

# Menjalankan migrasi database ke PostgreSQL (dengan force karena ini env production)
echo "Menjalankan migrasi Database..."
php artisan migrate --force
php artisan db:seed --force

# Membersihkan cache setelah tabel dibuat
php artisan cache:clear

# Menjalankan server Apache untuk melayani aplikasi
echo "Menyalakan Server Apache..."
exec apache2-foreground
