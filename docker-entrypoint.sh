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

until php -r "
try {
    \$dsn = 'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE');
    \$pdo = new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), [PDO::ATTR_TIMEOUT => 5]);
    echo 'OK';
} catch (Exception \$e) {
    exit(1);
}
" 2>/dev/null | grep -q 'OK'; do
  RETRY_COUNT=$((RETRY_COUNT + 1))
  if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
    echo "ERROR: Database tidak dapat dihubungi setelah $MAX_RETRIES percobaan."
    echo "  DB_HOST     = $DB_HOST"
    echo "  DB_PORT     = $DB_PORT"
    echo "  DB_DATABASE = $DB_DATABASE"
    echo "  DB_USERNAME = $DB_USERNAME"
    echo "  Pastikan variabel sudah benar di Render Dashboard."
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
USER_COUNT=$(php -r "
try {
    \$dsn = 'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE');
    \$pdo = new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    \$stmt = \$pdo->query('SELECT COUNT(*) FROM users');
    echo \$stmt->fetchColumn();
} catch (Exception \$e) {
    echo '0';
}
" 2>/dev/null)

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
