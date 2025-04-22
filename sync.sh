# Clone atau pull project
git pull origin main
# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Jalankan migrasi (kalau ada)
php artisan migrate --force

# Jalankan seeder kalau perlu
php artisan db:seed

# Build asset frontend
npm ci && npm run build

# Cache config, route, view
php artisan optimize
