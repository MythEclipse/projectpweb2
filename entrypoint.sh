#!/bin/bash

echo "🛠️  Fixing Laravel permissions..."
composer require laravel/octane

# Ganti owner ke www-data untuk semua yang relevan
chown -R www-data:www-data * || true

# Pastikan Laravel bisa menulis ke storage & cache
chmod -R 775 storage bootstrap/cache
# Pastikan file .env bisa dibaca
chmod 644 .env || true

echo "✅ Permissions set."

# Jalankan Laravel Octane (Swoole)
echo "🚀 Starting Laravel Octane (Swoole)..."
exec php artisan octane:start --server=swoole --host=0.0.0.0 --port=80
