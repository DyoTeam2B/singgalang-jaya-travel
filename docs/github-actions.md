# GitHub Action Documentation

Dokumen ini menjelaskan workflow GitHub Actions untuk proyek Singgalang Jaya Travel System.

## File Workflow

Workflow CI berada di:

```text
.github/workflows/laravel-ci.yml
```

## Tujuan Workflow

Workflow digunakan sebagai quality gate otomatis sebelum kode digabungkan ke branch utama. CI memastikan dependency dapat diinstal, asset frontend dapat dibuild, migration database berjalan, dan test Laravel lulus.

## Trigger

Workflow berjalan pada:

| Trigger | Keterangan |
| --- | --- |
| `push` ke `main` | Validasi perubahan langsung di branch stabil |
| `push` ke `develop` | Validasi branch integrasi bila digunakan |
| `pull_request` ke `main` | Validasi sebelum merge ke branch stabil |
| `pull_request` ke `develop` | Validasi sebelum merge ke branch integrasi |
| `workflow_dispatch` | Menjalankan workflow manual dari tab Actions |

## Environment CI

| Komponen | Konfigurasi |
| --- | --- |
| Runner | `ubuntu-latest` |
| PHP | `8.4` |
| Node.js | `22` |
| Database | MySQL 8.0 service |
| Database name | `singgalang_travel_system` |
| Cache/session/queue | Database driver agar sesuai konfigurasi aplikasi |

## Step Workflow

| Urutan | Step | Fungsi |
| --- | --- | --- |
| 1 | Checkout repository | Mengambil source code dari GitHub |
| 2 | Setup PHP | Menyiapkan PHP 8.4 dan ekstensi yang dibutuhkan Laravel |
| 3 | Setup Node.js | Menyiapkan Node 22 dan cache NPM |
| 4 | Install Composer dependencies | Menjalankan `composer install` |
| 5 | Install NPM dependencies | Menjalankan `npm ci` |
| 6 | Prepare environment | Menyalin `.env.example` ke `.env` dan generate key |
| 7 | Run migration | Menjalankan `php artisan migrate --force` pada MySQL service |
| 8 | Build frontend assets | Menjalankan `npm run build` |
| 9 | Run Laravel tests | Menjalankan `php artisan test` |

## Secret dan Environment Variable

Workflow test tidak membutuhkan secret production.

| Variable | Nilai CI | Keterangan |
| --- | --- | --- |
| `APP_ENV` | `testing` | Mode testing |
| `DB_CONNECTION` | `mysql` | Menggunakan MySQL service |
| `DB_DATABASE` | `singgalang_travel_system` | Database CI |
| `DB_USERNAME` | `root` | User MySQL service |
| `DB_PASSWORD` | `root` | Password MySQL service |
| `FONNTE_TOKEN` | kosong | Test memakai fake/mock HTTP atau melewati pengiriman real |

Jika nanti menambah deployment, secret production seperti `FONNTE_TOKEN`, SSH key, atau credential hosting harus disimpan di GitHub Repository Secrets, bukan di file `.env` repository.

## Cara Melihat Hasil CI

1. Buka repository GitHub.
2. Pilih tab `Actions`.
3. Pilih workflow `Laravel CI`.
4. Buka run terbaru.
5. Periksa step yang gagal bila status merah.

## Cara Menjalankan Ulang CI

1. Buka tab `Actions`.
2. Pilih run yang ingin diulang.
3. Klik `Re-run jobs`.

Untuk menjalankan manual:

1. Buka tab `Actions`.
2. Pilih workflow `Laravel CI`.
3. Klik `Run workflow`.
4. Pilih branch.
5. Klik tombol run.

## Perintah Lokal yang Setara

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm run build
php artisan test
```

## Troubleshooting CI

| Masalah | Penyebab umum | Solusi |
| --- | --- | --- |
| Composer install gagal | Versi PHP tidak sesuai atau lock file bermasalah | Pastikan PHP 8.4 dan `composer.lock` valid |
| NPM install gagal | `package-lock.json` tidak sinkron | Jalankan `npm install`, commit perubahan lock file |
| Migration gagal | Konfigurasi database salah atau migration error | Cek env DB di workflow dan file migration terakhir |
| Test Fonnte gagal | Request eksternal tidak dimock | Pastikan test memakai `Http::fake()` atau token kosong ditangani |
| Build frontend gagal | Error Blade/Vite/Tailwind atau dependency hilang | Jalankan `npm run build` lokal dan perbaiki error |
