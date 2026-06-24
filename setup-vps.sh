#!/bin/bash

# Script Instalasi Otomatis VPS - UIN Lab Scheduling
# Harap jalankan script ini HANYA di server VPS Anda (Ubuntu/Debian), BUKAN di komputer lokal Mac Anda.

if [ "$EUID" -ne 0 ]
  then echo "Tolong jalankan script ini sebagai root menggunakan sudo (contoh: sudo bash setup-vps.sh)"
  exit
fi

echo "🚀 Memulai Konfigurasi Otomatis VPS..."

# Dapatkan direktori project tempat script ini dijalankan
PROJECT_DIR=$(pwd)
echo "📁 Direktori Project terdeteksi di: $PROJECT_DIR"

# 1. Update OS & Install Supervisor
echo "⚙️ Memeriksa & menginstall Supervisor..."
apt-get update
apt-get install -y supervisor

# 2. Buat file konfigurasi Supervisor secara dinamis sesuai path project
echo "🔄 Membuat konfigurasi Background Queue Worker..."
cat > /etc/supervisor/conf.d/uin_lab_worker.conf <<EOF
[program:uin_lab_worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_DIR/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Terapkan & Jalankan Supervisor
supervisorctl reread
supervisorctl update
supervisorctl start uin_lab_worker:*
echo "✅ Supervisor Worker berhasil dinyalakan."

# 3. Masukkan Cron Job untuk Laravel Scheduler
echo "⏱️ Menambahkan Cronjob otomatis..."
CRON_JOB="* * * * * cd $PROJECT_DIR && php artisan schedule:run >> /dev/null 2>&1"
# Cek cron eksisting, buang jika sudah ada, lalu pasang yang baru (mencegah duplikat)
(crontab -u www-data -l 2>/dev/null | grep -Fv "artisan schedule:run"; echo "$CRON_JOB") | crontab -u www-data -
echo "✅ Cronjob berhasil dipasang."

# 4. Build Aset & Optimasi Server
echo "📦 Menjalankan optimasi Production Laravel..."

# Build file statis frontend (jika Anda pakai npm di VPS)
# Jika node/npm belum ada di VPS, pastikan untuk menginstallnya terlebih dahulu.
if command -v npm &> /dev/null; then
    npm install
    npm run build
    echo "✅ Frontend berhasil di-build."
else
    echo "⚠️ NPM tidak ditemukan, melewati tahap frontend build..."
fi

# Optimasi Backend Laravel (Perintah ini harusnya berjalan tanpa error jika dijalankan di path Laravel)
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Backend Laravel berhasil dioptimasi."

# 5. Fix Permission Storage
echo "🔒 Memperbaiki file permission..."
chown -R www-data:www-data $PROJECT_DIR/storage
chown -R www-data:www-data $PROJECT_DIR/bootstrap/cache
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache
echo "✅ Perizinan folder berhasil disesuaikan."

echo ""
echo "🎉 SEMUA SELESAI! Aplikasi UIN Lab Anda kini siap tempur 24/7 di Production!"
