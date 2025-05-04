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
# (Opsional) Group dari web server user (seringkali sama dengan user)
# Pastikan user yang menjalankan skrip ini (root) adalah anggota group ini jika berbeda.
# Cek dengan 'groups root' dan 'id www-data'
WEB_GROUP="${WEB_USER}" # <-- Sesuaikan jika perlu

# Path ke binary PHP dan Composer (biasanya otomatis terdeteksi jika ada di PATH)
PHP_BINARY=$(command -v php || echo "/usr/bin/php") # Sesuaikan jika PHP tidak ada di PATH
COMPOSER_BINARY=$(command -v composer || echo "/usr/local/bin/composer") # Sesuaikan jika Composer tidak ada di PATH

# Nama service PHP-FPM (sesuaikan versi PHP Anda!)
PHP_FPM_SERVICE="php8.4-fpm" # <-- GANTI DENGAN VERSI PHP-FPM ANDA (e.g., php7.4-fpm, php8.2-fpm)
# Nama service Web Server (jika perlu direstart)
WEB_SERVER_SERVICE="nginx" # atau 'apache2', atau kosongkan jika tidak perlu: ""

# === Variabel Internal & Warna ===
SCRIPT_USER=$(whoami)
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# === Fungsi Pembantu ===
log_info() {
    echo -e "${BLUE}INFO:${NC} $1"
}

log_success() {
    echo -e "${GREEN}SUCCESS:${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}WARNING:${NC} $1"
}

log_error() {
    echo -e "${RED}ERROR:${NC} $1" >&2 # Kirim error ke stderr
}

# Fungsi untuk cleanup (menonaktifkan maintenance mode) jika skrip exit
cleanup() {
    log_info "Menjalankan cleanup..."
    cd "$PROJECT_DIR" || return # Coba kembali ke direktori proyek
    if [ -f "storage/framework/down" ]; then
        log_warning "Skrip terinterupsi atau gagal. Menonaktifkan Maintenance Mode..."
        "${PHP_BINARY}" artisan up
        log_success "Maintenance Mode Dinonaktifkan."
    fi
}

# Trap EXIT signal untuk menjalankan fungsi cleanup
trap cleanup EXIT

# === Validasi Awal ===
if [ "$SCRIPT_USER" != "root" ]; then
    log_error "Skrip ini harus dijalankan sebagai root (atau via sudo) untuk mengatur permissions dan me-restart service."
    exit 1
fi

if [ -z "$WEB_USER" ]; then
    log_error "Variabel WEB_USER belum diatur. Edit script dan isi nama user web server."
    exit 1
fi

if ! command -v "$PHP_BINARY" &> /dev/null; then
    log_error "PHP binary tidak ditemukan di '$PHP_BINARY'. Periksa konfigurasi atau PATH."
    exit 1
fi

if ! command -v "$COMPOSER_BINARY" &> /dev/null; then
    log_warning "Composer binary tidak ditemukan di '$COMPOSER_BINARY'. Langkah Composer akan dilewati jika command 'composer' juga tidak ada."
    # Skrip akan mencoba 'composer' jika $COMPOSER_BINARY gagal
fi

# === Script Update ===

echo "=============================================="
echo -e "${BLUE} Memulai Proses Update Aplikasi Laravel${NC}"
echo " Waktu    : $(date)"
echo " User Skrip: ${SCRIPT_USER}"
echo " Direktori: ${PROJECT_DIR}"
echo " Branch   : ${GIT_BRANCH}"
echo " Web User : ${WEB_USER} (Group: ${WEB_GROUP})"
echo "=============================================="
echo ""

# Pindah ke direktori proyek
log_info "Pindah ke direktori proyek: ${PROJECT_DIR}"
cd "$PROJECT_DIR" || { log_error "Tidak bisa masuk ke direktori ${PROJECT_DIR}"; exit 1; }

# 1. Aktifkan Maintenance Mode
log_info "[1/9] Mengaktifkan Maintenance Mode..."
if ! "${PHP_BINARY}" artisan down --message="Aplikasi sedang dalam proses pembaruan. Silakan coba lagi dalam beberapa saat." --retry=60; then
    log_warning "Gagal mengaktifkan maintenance mode (mungkin sudah aktif?). Melanjutkan..."
    # Kita lanjutkan saja, mungkin memang sudah down atau ada masalah lain yg akan muncul nanti
fi
log_success "      Maintenance Mode Aktif (atau sudah aktif)."
echo ""

# 2. Tarik Perubahan Terbaru dari Git
log_info "[2/9] Menarik kode terbaru dari Git (Branch: ${GIT_BRANCH})..."
log_warning "      Membersihkan local changes (git reset --hard & git clean -fd)..."
git reset --hard HEAD || { log_error "Gagal menjalankan git reset --hard HEAD."; exit 1; }
git clean -fd || { log_error "Gagal menjalankan git clean -fd."; exit 1; }

if git pull origin "$GIT_BRANCH"; then
    log_success "      Kode terbaru berhasil ditarik."
else
    log_error "Gagal menarik kode dari Git. Membatalkan update."
    # Cleanup trap akan otomatis menonaktifkan maintenance mode
    exit 1
fi
echo ""

# 3. Install/Update Dependensi Composer
log_info "[3/9] Menginstall/Update dependensi Composer..."
# Gunakan $COMPOSER_BINARY jika ada, fallback ke 'composer'
if command -v "$COMPOSER_BINARY" &> /dev/null; then
    COMPOSER_CMD="$COMPOSER_BINARY"
else
    COMPOSER_CMD="composer"
    if ! command -v composer &> /dev/null; then
      log_error "Composer tidak ditemukan. Tidak bisa menginstall dependensi."
      exit 1
    fi
fi
"$COMPOSER_CMD" install --optimize-autoloader --no-dev --no-interaction || { log_error "Gagal menjalankan composer install."; exit 1; }
log_success "      Dependensi Composer terinstall/update."
echo ""

# 4. Install/Update Dependensi NPM & Build Aset Frontend
log_info "[4/9] Menginstall/Update dependensi NPM dan membangun aset frontend..."
if [ -f "package.json" ]; then
    if ! command -v npm &> /dev/null; then
        log_warning "      Perintah 'npm' tidak ditemukan. Melewati build aset frontend."
    else
        log_info "      Menjalankan 'npm ci' (mungkin butuh waktu)..."
        if npm ci --no-audit --no-fund; then
            log_info "      Menjalankan 'npm run build'..."
            if npm run build; then
                log_success "      Aset frontend berhasil dibangun."
            else
                log_error "      Gagal menjalankan 'npm run build'."
                exit 1
            fi
        else
            log_error "      Gagal menjalankan 'npm ci'."
            exit 1
        fi
    fi
else
    log_info "      File package.json tidak ditemukan. Melewati langkah NPM."
fi
echo ""

# 5. Jalankan Migrasi Database
log_info "[5/9] Menjalankan migrasi database..."
"${PHP_BINARY}" artisan migrate --force || { log_error "Gagal menjalankan migrasi database."; exit 1; }
log_success "      Migrasi database selesai."
echo ""

# 6. (Opsional) Jalankan Seeder Tertentu Jika Diperlukan
# log_info "[*] Menjalankan database seeder..."
# "${PHP_BINARY}" artisan db:seed --class=NamaSeederPenting --force || { log_error "Gagal menjalankan Seeder."; exit 1; }
# log_success "      Seeder selesai."
# echo ""

# 7. Bersihkan Cache Lama dan Optimalkan Aplikasi
log_info "[6/9] Membersihkan cache lama dan mengoptimalkan aplikasi..."
# Bersihkan semua cache yang mungkin ada
"${PHP_BINARY}" artisan optimize:clear || log_warning "      Gagal membersihkan cache (mungkin tidak ada cache sebelumnya)."
# Buat cache baru untuk produksi
"${PHP_BINARY}" artisan config:cache || { log_error "Gagal membuat cache config."; exit 1; }
"${PHP_BINARY}" artisan route:cache || { log_error "Gagal membuat cache route."; exit 1; }
"${PHP_BINARY}" artisan view:cache || { log_error "Gagal membuat cache view."; exit 1; }
# "${PHP_BINARY}" artisan event:cache # Aktifkan jika menggunakan event discovery
log_success "      Cache dibersihkan dan optimasi dibuat."
echo ""

# 8. Atur Ulang Kepemilikan dan Permissions (KRUSIAL!)
log_info "[7/9] Mengatur ulang kepemilikan dan permissions untuk user '${WEB_USER}'..."

# Pastikan user/group web server ada
if ! id -u "$WEB_USER" > /dev/null 2>&1; then
    log_error "User '$WEB_USER' tidak ditemukan di sistem."
    exit 1
fi
if ! getent group "$WEB_GROUP" > /dev/null 2>&1; then
    log_error "Group '$WEB_GROUP' tidak ditemukan di sistem."
    exit 1
fi

# Mengubah kepemilikan direktori penting ke user:group web server
log_info "      Mengubah kepemilikan storage/ dan bootstrap/cache/ ke ${WEB_USER}:${WEB_GROUP}"
chown -R "${WEB_USER}":"${WEB_GROUP}" storage bootstrap/cache || { log_error "Gagal mengubah kepemilikan."; exit 1; }
log_success "      Kepemilikan storage/ dan bootstrap/cache/ diubah."

# Mengatur izin yang lebih aman
# Direktori: 775 (rwxrwxr-x) -> User=RWX, Group=RWX, Other=RX
# File: 664 (rw-rw-r--) -> User=RW, Group=RW, Other=R
log_info "      Mengatur permissions (Directories: 775, Files: 664)..."
find . -type d -exec chmod 775 {} \;
find . -type f -exec chmod 664 {} \;
log_success "      Permissions proyek diatur."

# Berikan izin tulis spesifik pada direktori yang dibutuhkan oleh Laravel
# (chown di atas sudah memberi akses ke user, chmod di bawah memastikan group juga bisa menulis jika perlu)
log_info "      Memastikan izin tulis group pada storage/ dan bootstrap/cache/ (chmod g+w)..."
chmod -R g+w storage bootstrap/cache
log_success "      Izin tulis group untuk storage/ dan bootstrap/cache/ dipastikan."
echo ""

# 9. (Opsional Tambahan) Restart Servis Jika Perlu
log_info "[8/9] Merestart service terkait..."
if [ -n "$PHP_FPM_SERVICE" ]; then
    if systemctl is-active --quiet "$PHP_FPM_SERVICE"; then
        log_info "      Merestart ${PHP_FPM_SERVICE}..."
        systemctl restart "$PHP_FPM_SERVICE" || log_warning "      Gagal merestart ${PHP_FPM_SERVICE}. Periksa nama service."
    else
        log_warning "      Service ${PHP_FPM_SERVICE} tidak aktif atau tidak ditemukan. Restart dilewati."
    fi
fi

if [ -n "$WEB_SERVER_SERVICE" ]; then
     if systemctl is-active --quiet "$WEB_SERVER_SERVICE"; then
        log_info "      Merestart ${WEB_SERVER_SERVICE}..."
        systemctl restart "$WEB_SERVER_SERVICE" || log_warning "      Gagal merestart ${WEB_SERVER_SERVICE}. Periksa nama service."
    else
        log_warning "      Service ${WEB_SERVER_SERVICE} tidak aktif atau tidak ditemukan. Restart dilewati."
    fi
fi

# Restart queue worker (jika menggunakan)
log_info "      Mengirim sinyal restart ke queue worker (jika berjalan)..."
"${PHP_BINARY}" artisan queue:restart || log_warning "      Gagal mengirim sinyal restart queue (mungkin tidak ada worker berjalan)."
log_success "      Service direstart (atau dicoba restart)."
echo ""

# Hapus trap karena kita akan keluar secara normal
trap - EXIT

# Nonaktifkan Maintenance Mode
log_info "[9/9] Menonaktifkan Maintenance Mode..."
"${PHP_BINARY}" artisan up || { log_error "Gagal menonaktifkan maintenance mode! Periksa aplikasi secara manual."; exit 1; } # Error di sini krusial
log_success "      Maintenance Mode Dinonaktifkan."
echo ""

echo "=============================================="
echo -e "${GREEN} Proses Update Selesai! ${NC}"
echo " Waktu Selesai: $(date)"
echo "=============================================="
echo "" # Baris kosong di akhir

exit 0
