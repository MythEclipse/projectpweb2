# Base image dari Microsoft Dev Containers PHP
FROM mcr.microsoft.com/devcontainers/php:0-8.3

# Install Redis extension (dan ekstensi tambahan jika perlu)
RUN pecl install redis && docker-php-ext-enable redis

# Laravel installer (opsional)
RUN composer global require laravel/installer

# Pastikan path global composer tersedia
ENV PATH="/root/.composer/vendor/bin:$PATH"

# Tambahkan nodejs 20 dan yarn jika belum
RUN su vscode -c "corepack enable && corepack prepare yarn@stable --activate"
