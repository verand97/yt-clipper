# YouTube Video Clipper & Processor

Aplikasi web untuk memotong (trimming) dan mengunduh klip dari video YouTube berdasarkan waktu yang ditentukan pengguna.

## Prasyarat

- PHP 8.2+
- Composer
- MySQL (production) atau SQLite (development)
- [yt-dlp](https://github.com/yt-dlp/yt-dlp) - untuk mengunduh video YouTube
- [FFmpeg](https://ffmpeg.org/download.html) - untuk memotong video

## Instalasi

```bash
# 1. Install dependencies
composer install

# 2. Copy .env dan generate key (sudah dilakukan)
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env
# Untuk development (SQLite - default):
#   DB_CONNECTION=sqlite

# Untuk production (MySQL):
#   DB_CONNECTION=mysql
#   DB_HOST=127.0.0.1
#   DB_PORT=3306
#   DB_DATABASE=yt_clipper
#   DB_USERNAME=root
#   DB_PASSWORD=

# 4. Jalankan migrasi
php artisan migrate

# 5. Buat symlink storage
php artisan storage:link
```

## Menjalankan Aplikasi

```bash
# Jalankan server development
php artisan serve

# Jalankan queue worker (di terminal terpisah) - WAJIB untuk memproses video
php artisan queue:work

# Jalankan scheduler (opsional, untuk cleanup otomatis)
php artisan schedule:work
```

## Penggunaan

1. Buka `http://localhost:8000`
2. Tempel tautan video YouTube
3. Tentukan waktu mulai dan selesai (format `HH:MM:SS` atau `MM:SS`)
4. Klik **Proses Pemotongan**
5. Tunggu sampai status berubah menjadi **Completed**
6. Klik **Unduh Berkas** untuk mengunduh klip

## Fitur

- Video Link Parser (mendukung youtube.com/watch, youtu.be, shorts)
- Precise Trimming dengan format HH:MM:SS
- Asynchronous Queue Processing (tidak memblokir browser)
- Auto-polling status setiap 5 detik
- Automated Storage Cleanup (via cron job setiap 6 jam)

## Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Blade + Tailwind CSS (CDN)
- **Icons:** Lucide Icons
- **Queue:** Laravel Queue (database driver)
- **Video Processing:** yt-dlp + FFmpeg
- **Database:** MySQL (prod) / SQLite (dev)
