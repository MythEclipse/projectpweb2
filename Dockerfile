# Gunakan image PHP dengan versi spesifik dan Swoole (sesuaikan dengan PHP versi yang dibutuhkan)
FROM phpswoole/swoole:php8.2

# Install ekstensi tambahan yang diperlukan Laravel
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    && docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql \
    mbstring \
    xml \
    bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer dari image terpisah
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files terlebih dahulu untuk optimasi cache Docker
COPY composer.json composer.lock ./

# Install dependencies Laravel (untuk production gunakan --no-dev)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Copy seluruh project
COPY . .

# Generate key aplikasi dan optimasi
RUN if [ -f .env.example ]; then cp .env.example .env; fi \
    && php artisan key:generate --ansi \
    && php artisan storage:link \
    && php artisan optimize:clear

# Set permission untuk Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    /var/www/bootstrap/cache

# Expose port untuk Octane
EXPOSE 8000

# Command untuk menjalankan Octane dengan Swoole (production)
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000", "--workers=4", "--max-requests=500"]
