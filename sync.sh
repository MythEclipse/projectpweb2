#!/bin/bash

# === Konfigurasi (WAJIB SESUAIKAN!) ===
# Direktori root proyek Laravel Anda
PROJECT_DIR="/home/asephs/projectpweb2"
# Branch Git yang digunakan untuk produksi
GIT_BRANCH="main"
# User yang MENJALANKAN WEB SERVER (nginx, apache). Ini SANGAT PENTING!
# Umumnya 'www-data' di Ubuntu/Debian, 'nginx' di CentOS/Fedora, 'apache'
# Ganti ini dengan user yang benar! Cek dengan 'ps aux | grep nginx' atau 'ps aux | grep apache'
WEB_USER="www-data" # <-- PASTIKAN INI BENAR!

# === Script Update (Dijalankan oleh root) ===

# Hentikan script jika terjadi error
set -e

echo "=============================================="
echo " Memulai Proses Update Aplikasi Laravel (via root)"
echo " Waktu    : $(date)"
echo " Direktori: ${PROJECT_DIR}"
echo " Branch   : ${GIT_BRANCH}"
echo " Web User : ${WEB_USER}"
echo "=============================================="
echo ""

# Pastikan WEB_USER sudah diset
if [ -z "$WEB_USER" ]; then
    echo "ERROR: Variabel WEB_USER belum diatur dalam script. Edit script dan isi nama user web server."
    exit 1
fi

# Pindah ke direktori proyek
cd "$PROJECT_DIR" || { echo "ERROR: Tidak bisa masuk ke direktori ${PROJECT_DIR}"; exit 1; }

# 1. Aktifkan Maintenance Mode
echo "[1/8] Mengaktifkan Maintenance Mode..."
php artisan down --message="Aplikasi sedang dalam proses pembaruan. Silakan coba lagi dalam beberapa saat." --retry=60 || true
echo "      Maintenance Mode Aktif."
echo ""

# 2. Tarik Perubahan Terbaru dari Git
echo "[2/8] Menarik kode terbaru dari Git (Branch: ${GIT_BRANCH})..."
# Pastikan root memiliki akses ke repo (SSH key atau HTTPS credential)
git reset --hard HEAD
git clean -fd # Hati-hati! Menghapus file tak terlacak.
if git pull origin "$GIT_BRANCH"; then
    echo "      Kode terbaru berhasil ditarik."
else
    echo "      ERROR: Gagal menarik kode dari Git. Membatalkan update."
    php artisan up # Nonaktifkan maintenance mode jika pull gagal
    exit 1
fi
echo ""

# 3. Install/Update Dependensi Composer
echo "[3/8] Menginstall/Update dependensi Composer..."
composer install --optimize-autoloader --no-dev --no-interaction
echo "      Dependensi Composer terinstall."
echo ""

# 4. Install/Update Dependensi NPM & Build Aset Frontend
echo "[4/8] Menginstall/Update dependensi NPM dan membangun aset frontend..."
if [ -f "package.json" ]; then
    if ! command -v npm &> /dev/null; then
        echo "      PERINGATAN: Perintah 'npm' tidak ditemukan. Melewati build aset frontend."
    else
        echo "      Menjalankan 'npm ci'..."
        npm ci --no-audit --no-fund
        echo "      Menjalankan 'npm run build'..."
        npm run build
        echo "      Aset frontend berhasil dibangun."
    fi
else
    echo "      File package.json tidak ditemukan. Melewati langkah NPM."
fi
echo ""

# 5. Jalankan Migrasi Database
echo "[5/8] Menjalankan migrasi database..."
php artisan migrate --force
echo "      Migrasi database selesai."
echo ""

# 6. (Opsional) Jalankan Seeder Tertentu Jika Diperlukan
# echo "[*] Menjalankan database seeder..."
# php artisan db:seed --class=NamaSeederPenting --force
# echo "      Seeder selesai."
# echo ""
# 6.1. Membersihkan cache Laravel tambahan jika diperlukan
echo "[6/8] Membersihkan cache tambahan Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "      Cache tambahan berhasil dibersihkan."
echo ""
# 7. Bersihkan Cache Lama dan Optimalkan Aplikasi
echo "[7/8] Membersihkan cache lama dan mengoptimalkan aplikasi..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
# php artisan event:cache # Jika perlu
echo "      Cache dibersihkan dan optimasi dibuat."
echo ""

# 8. Atur Ulang Kepemilikan dan Permissions (KRUSIAL!)
echo "[8/8] Mengatur ulang kepemilikan dan permissions untuk user '$WEB_USER'..."
# Mengubah kepemilikan direktori penting ke user web server
echo "      Mengubah kepemilikan storage/* dan bootstrap/cache/* ke $WEB_USER:$WEB_USER"
chown -R "$WEB_USER":"$WEB_USER" "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
echo "      Kepemilikan diubah."

# Mengatur izin tulis untuk group (dan user) pada direktori penting
echo "      Mengatur izin tulis (chmod ug+w) pada storage/* dan bootstrap/cache/*"
chmod -R ug+w "$PROJECT_DIR/storage"
chmod -R ug+w "$PROJECT_DIR/bootstrap/cache"
echo "      Permissions diatur."
echo ""

# (Opsional Tambahan) Restart Servis Jika Perlu
# echo "[*] Merestart service terkait..."
systemctl restart php8.4-fpm # Ganti X dengan versi PHP Anda
systemctl restart nginx # Jika menggunakan Nginx
php artisan queue:restart
echo "      Service direstart."
echo ""

# Nonaktifkan Maintenance Mode
echo "[Langkah Terakhir] Menonaktifkan Maintenance Mode..."
php artisan up
echo "      Maintenance Mode Dinonaktifkan."
echo ""

echo "=============================================="
echo " Proses Update Selesai!                     "
echo " Waktu Selesai: $(date)"
echo "=============================================="
echo "" # Baris kosong di akhir untuk pemisah log

exit 0
