#!/bin/bash

echo "🛠️  Fixing Laravel permissions..."

# Ganti owner ke www-data untuk semua yang relevan
chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    vendor \
    public \
    resources \
    routes \
    .env || true

# Pastikan Laravel bisa menulis ke storage & cache
chmod -R 775 storage bootstrap/cache

# Pastikan file .env bisa dibaca
chmod 644 .env || true

echo "✅ Permissions set."

# Jalankan Apache
echo "🚀 Starting Apache..."
exec apache2-foreground
