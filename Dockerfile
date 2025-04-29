FROM php:8.4-cli

# Set environment
ENV DEBIAN_FRONTEND=noninteractive

# Install OS and PHP deps
# Install PHP extensions (lanjutan)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libpq-dev \
    libxslt-dev \
    git \
    php-curl \
    php-mbstring \
    php-mysqli \
    php-openssl \
    php-pdo_mysql \
    php-sockets \
    php-bcmath \
    php-exif \
    php-gd \
    php-intl \
    php-pcntl \
    php-sodium \
    php-xml \
    php-zip \
    php-fileinfo \
    unzip \
    zip \
    curl \
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
        pcntl \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && apt-get install -y nodejs

# Set working dir
WORKDIR /var/www/html

# Copy and install dependencies
COPY . .
# Salin php.ini dari folder lokal ke direktori konfigurasi PHP di container
COPY docker/php.ini /usr/local/etc/php/php.ini
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Expose Laravel Octane port (default 8000)
EXPOSE 80

# Start Laravel Octane (Swoole) on container boot
# Jalankan Apache di foreground
# Salin entrypoint.sh
COPY entrypoint.sh /entrypoint.sh

# Beri permission agar bisa dieksekusi
RUN chmod +x /entrypoint.sh

# Gunakan entrypoint custom
ENTRYPOINT ["/entrypoint.sh"]
