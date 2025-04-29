# Gunakan image PHP 8.3 resmi dengan Apache (Laravel 12 requires >= 8.2)
FROM php:8.3-apache

# Set variabel lingkungan untuk non-interactive install
ENV DEBIAN_FRONTEND=noninteractive

# Install dependency OS & PHP extension Laravel
# Gabungkan RUN untuk mengurangi layer
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libpq-dev \
    libxslt-dev \
    # libsqlite3-dev (Hanya jika menggunakan SQLite) \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        gd \
        bcmath \
        intl \
        xsl \
        opcache \
        exif \
    # Sering dibutuhkan untuk image handling
    # Bersihkan cache apt
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js LTS (via NodeSource)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest # Update npm ke versi terbaru

# Konfigurasi Apache
# Aktifkan mod_rewrite
RUN a2enmod rewrite expires headers

# Ubah DocumentRoot ke public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html/public|/var/www/html/public|g' /etc/apache2/apache2.conf # Pastikan path di Directory utama juga benar jika ada

# Tambahkan konfigurasi directory agar .htaccess dan routing Laravel bisa jalan
RUN { \
        echo '<Directory /var/www/html/public>'; \
        echo '    Options Indexes FollowSymLinks MultiViews'; \
        echo '    AllowOverride All'; \
        echo '    Require all granted'; \
        echo '</Directory>'; \
    } >> /etc/apache2/apache2.conf

# (Opsional) Salin konfigurasi PHP kustom jika ada
# COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www/html

# --- Build Cache Optimization ---
# Salin composer files
COPY composer.json composer.lock ./

# Install Composer dependencies (sesuaikan --no-dev jika perlu)
# --prefer-dist lebih cepat, --no-scripts/--no-autoloader untuk nanti
RUN composer install --prefer-dist --no-interaction --no-scripts --no-progress --no-dev --optimize-autoloader

# Salin package files
COPY package.json package-lock.json ./

# Install NPM dependencies menggunakan npm ci (lebih cepat jika lock file ada)
RUN npm ci --no-audit --no-fund --no-update-notifier

# --- End Build Cache Optimization ---

# Salin semua file aplikasi ke container
# Harusnya setelah install dependency agar cache lebih efektif
COPY . .

# Jalankan composer dump-autoload lagi setelah semua kode ada
RUN composer dump-autoload --optimize --no-dev

# Jalankan build asset (sesuaikan 'build' dengan script di package.json Anda)
RUN npm run build

# Ubah ownership agar Apache (www-data) bisa menulis
# Pastikan www-data ada dan gunakan ID yang sesuai jika perlu (misal: 33 untuk Debian/Ubuntu)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

# Bersihkan cache Laravel
RUN php artisan optimize:clear

# (Penting) storage:link biasanya dijalankan di container *setelah* start jika pakai volume mount
# Jika tidak pakai volume mount untuk production, bisa dijalankan di sini:
# RUN php artisan storage:link

# Buka port 80 yang digunakan Apache
EXPOSE 80

# Jalankan Apache di foreground
CMD ["apache2-foreground"]
