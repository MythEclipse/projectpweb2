# Gunakan image PHP resmi dengan Apache
FROM php:8.4-apache

# Install dependency OS & PHP extension Laravel
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    unzip \
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
    libsqlite3-dev \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        gd \
        bcmath \
        intl \
        xsl \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Ubah DocumentRoot ke public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Tambahkan konfigurasi directory agar .htaccess dan routing Laravel bisa jalan
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js LTS (via NodeSource)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Salin semua file Laravel ke container
COPY . .

# Ubah permission storage & bootstrap
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod 644 /var/www/html/.env || true

# Buka port 80
# EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
