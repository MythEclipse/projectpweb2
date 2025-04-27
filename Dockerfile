# Gunakan PHP + Swoole resmi
FROM phpswoole/swoole:latest

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
        pdo \
        pdo_mysql \
        mbstring \
        xml \
        bcmath \
        zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy hanya file composer untuk optimasi cache
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Copy seluruh project setelah install dependency
COPY . .

# Cek dan generate .env serta optimize jika ada
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate --ansi || true \
    && php artisan storage:link || true \
    && php artisan optimize:clear || true

# Set permission storage & cache (writeable)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Expose port untuk Octane
EXPOSE 8000

# Jalankan Laravel Octane pakai Swoole
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000", "--workers=4", "--max-requests=500"]
