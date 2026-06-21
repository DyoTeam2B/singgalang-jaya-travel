# Installation Guide

Dokumen ini menjelaskan cara memasang dan menjalankan Singgalang Jaya Travel System di lingkungan lokal.

## Prasyarat

Pastikan perangkat sudah memiliki:

| Kebutuhan | Versi rekomendasi | Keterangan |
| --- | --- | --- |
| PHP | 8.3 atau lebih baru | Sesuai `composer.json` (`php: ^8.3`) |
| Composer | 2.x | Dependency manager backend Laravel |
| Node.js | 22 LTS atau lebih baru | Untuk Vite, Tailwind CSS, dan asset frontend |
| NPM | Sesuai Node.js | Dependency manager frontend |
| MySQL | 8.x atau MariaDB setara | Database aplikasi |
| Git | Versi stabil | Clone repository dan kolaborasi |

Ekstensi PHP yang umum dibutuhkan Laravel: `bcmath`, `ctype`, `curl`, `fileinfo`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, dan `zip`.

## Clone Repository

```bash
git clone <repository-url>
cd singgalang-jaya-travel
```

## Install Dependency Backend

```bash
composer install
```

## Install Dependency Frontend

Untuk instalasi lokal:

```bash
npm install
```

Untuk instalasi CI atau environment bersih dengan `package-lock.json`:

```bash
npm ci
```

## Konfigurasi Environment

Salin file environment:

```bash
cp .env.example .env
```

Pada Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi Database

Buat database MySQL:

```sql
CREATE DATABASE singgalang_travel_system;
```

Sesuaikan konfigurasi pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=singgalang_travel_system
DB_USERNAME=root
DB_PASSWORD=
```

## Konfigurasi Fonnte API Opsional

Notifikasi WhatsApp tetap bisa dilewati saat token belum tersedia. Untuk mengaktifkan integrasi, isi konfigurasi berikut di `.env`:

```env
FONNTE_TOKEN=your_fonnte_token
FONNTE_URL=https://api.fonnte.com/send
FONNTE_COUNTRY_CODE=62
FONNTE_CONNECT_ONLY=false
ADMIN_WA_NUMBER=628xxxxxxxxxx
```

## Migrasi dan Seeder

Jalankan migration:

```bash
php artisan migrate
```

Untuk memasukkan data awal admin, driver, pelanggan, rute, dan armada:

```bash
php artisan db:seed
```

Atau jalankan ulang database dari awal:

```bash
php artisan migrate:fresh --seed
```

## Storage Link

Buat symbolic link storage agar bukti pembayaran dapat diakses dari browser:

```bash
php artisan storage:link
```

## Menjalankan Aplikasi

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

Alternatif menjalankan server, queue listener, dan Vite sekaligus:

```bash
composer run dev
```

## Akun Login Seeder

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@gmail.com` | `admin12345` |
| Driver | `driver@gmail.com` | `driver12345` |
| Pelanggan | `pelanggan@gmail.com` | `pelanggan12345` |

## Build Production Asset

```bash
npm run build
```

## Menjalankan Test

```bash
php artisan test
```

Atau melalui script Composer:

```bash
composer test
```

## Troubleshooting

| Masalah | Solusi |
| --- | --- |
| `APP_KEY` kosong | Jalankan `php artisan key:generate` |
| Tabel session/cache/jobs belum ada | Jalankan `php artisan migrate` |
| Bukti pembayaran tidak tampil | Jalankan `php artisan storage:link` |
| Asset CSS/JS tidak berubah | Jalankan `npm run dev` saat development atau `npm run build` untuk production |
| Koneksi database gagal | Periksa `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dan pastikan MySQL aktif |
| Notifikasi WhatsApp tidak terkirim | Pastikan `FONNTE_TOKEN` dan `ADMIN_WA_NUMBER` benar |
