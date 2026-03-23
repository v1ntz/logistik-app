# Menggunakan image resmi PHP 8.3 dengan server Apache bawaan
FROM php:8.3-apache

# Menginstall ekstensi sistem yang dibutuhkan Laravel, PostgreSQL, dan Excel (GD)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql pdo_pgsql pgsql zip gd

# Mengaktifkan mod_rewrite Apache untuk URL Laravel yang cantik (tanpa index.php)
RUN a2enmod rewrite

# Menginstall Composer (Manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menentukan direktori kerja di dalam server
WORKDIR /var/www/html

# Menyalin seluruh kode pemrograman Logistik PAD ke dalam server
COPY . /var/www/html

# Mengatur hak akses folder storage dan cache agar Laravel bisa menyimpan gambar/file 
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Mengubah titik kumpul Apache ke folder "/public" milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Membuka gerbang port 80 untuk akses internet
EXPOSE 80

# Menginstall pustaka Laravel (Tanpa pustaka mode testing/development)
RUN composer install --optimize-autoloader --no-dev

# Mempersiapkan skrip pintu masuk (entrypoint) yang berisi perintah migrasi database otomatis
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Menjalankan skrip saat server menyala
ENTRYPOINT ["docker-entrypoint.sh"]
