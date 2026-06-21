# Dependency Proyek Singgalang Jaya Travel

Dokumen ini mencatat dependency backend, frontend, dan layanan eksternal yang digunakan pada proyek Singgalang Jaya Travel System. Versi diambil dari `composer.json`, `package.json`, dan implementasi view/service yang ada di repository.

## Ringkasan Stack

| Layer | Teknologi | Versi/Constraint | Kegunaan |
| --- | --- | --- | --- |
| Backend | Laravel Framework | `^13.0` | MVC, routing, ORM, migration, validation, queue, testing |
| Runtime backend | PHP | `^8.3` | Bahasa utama aplikasi |
| Frontend server-rendered | Blade | Laravel built-in | Template UI admin, driver, dan publik |
| Frontend interaktif | Livewire | `^4.3` | Form booking dan tabel interaktif admin |
| UI utility | Tailwind CSS | `^3.1.0` | Styling halaman dan komponen |
| JS interaktif | Alpine.js | `^3.4.2` | Modal, dropdown, dan interaksi ringan di Blade |
| Build tool | Vite | `^8.0.0` | Bundling asset frontend |
| HTTP client JS | Axios | `^1.16.0` | Request AJAX bila diperlukan |
| Database | MySQL | 8.x direkomendasikan | Penyimpanan data operasional |
| Map | Leaflet + OpenStreetMap | CDN Leaflet 1.9.4 | Picker dan viewer lokasi jemput |
| Chart | Chart.js | CDN 4.4.7 | Grafik pendapatan di laporan admin |
| WhatsApp API | Fonnte API | External service | Notifikasi WhatsApp booking/trip |

## Dependency Backend Production

| Package | Versi | Fungsi | Digunakan pada |
| --- | --- | --- | --- |
| `laravel/framework` | `^13.0` | Framework utama aplikasi: routing, controller, model, migration, validation, queue, scheduler, testing helper | Seluruh aplikasi |
| `laravel/tinker` | `^3.0` | REPL Laravel untuk inspeksi model/data saat development | Debugging lokal |
| `livewire/livewire` | `^4.3` | Membuat komponen UI interaktif tanpa SPA penuh | `BookingForm`, `Admin\BookingTable`, `Admin\PembayaranTable` |

## Dependency Backend Development

| Package | Versi | Fungsi | Keterangan |
| --- | --- | --- | --- |
| `fakerphp/faker` | `^1.23` | Data dummy untuk factory/test | Digunakan test dan seeding bila diperlukan |
| `laravel/breeze` | `^2.4` | Starter kit authentication | Login, register, reset password, profile |
| `laravel/pail` | `^1.2.5` | Membaca log Laravel secara interaktif | Development |
| `laravel/pint` | `^1.27` | Code style fixer Laravel | Quality gate opsional |
| `mockery/mockery` | `^1.6` | Mocking library untuk PHPUnit | Test suite |
| `nunomaduro/collision` | `^8.6` | Error reporting CLI yang lebih jelas | Development/test |
| `phpunit/phpunit` | `^12.5.12` | Framework unit dan feature test | `php artisan test` |

## Dependency Frontend

| Package | Versi | Fungsi | Digunakan pada |
| --- | --- | --- | --- |
| `@tailwindcss/forms` | `^0.5.2` | Styling form berbasis Tailwind | Form login, booking, admin CRUD |
| `@tailwindcss/vite` | `^4.0.0` | Integrasi Tailwind dengan Vite | Build asset |
| `alpinejs` | `^3.4.2` | State kecil di UI Blade | Modal, dropdown, preview, toggle |
| `autoprefixer` | `^10.4.2` | Prefix CSS lintas browser | Build CSS |
| `concurrently` | `^9.0.1` | Menjalankan server Laravel, queue, dan Vite paralel | Script `composer run dev` |
| `laravel-vite-plugin` | `^3.0.0` | Integrasi Laravel dan Vite | `vite.config.js` |
| `postcss` | `^8.4.31` | Pipeline CSS | Tailwind build |
| `tailwindcss` | `^3.1.0` | Utility-first CSS framework | Seluruh UI |
| `vite` | `^8.0.0` | Dev server dan bundler frontend | `npm run dev`, `npm run build` |
| `axios` | `^1.16.0` | HTTP client browser | Bootstrap JS/AJAX bila dibutuhkan |

## Library CDN dan Layanan Eksternal

| Nama | Sumber | Fungsi | File terkait |
| --- | --- | --- | --- |
| Leaflet 1.9.4 | `https://unpkg.com/leaflet@1.9.4` | Peta interaktif untuk titik penjemputan | `resources/views/layouts/public.blade.php`, `resources/views/driver/dashboard.blade.php`, `resources/views/components/map-picker.blade.php` |
| OpenStreetMap Tile | Leaflet tile provider | Menampilkan peta dasar | Komponen map picker/viewer |
| Chart.js 4.4.7 | `https://cdn.jsdelivr.net/npm/chart.js@4.4.7` | Grafik pendapatan admin | `resources/views/admin/laporan/index.blade.php` |
| Fonnte API | `https://api.fonnte.com/send` | Mengirim pesan WhatsApp otomatis | `app/Services/FonnteService.php`, `app/Services/BookingWhatsappNotificationService.php` |

## Alasan Pemilihan Dependency

| Dependency | Alasan |
| --- | --- |
| Laravel 13 | Cocok untuk aplikasi CRUD operasional, menyediakan migration, Eloquent, validation, middleware, dan testing bawaan |
| Breeze | Authentication sederhana, ringan, dan mudah disesuaikan dengan role admin/driver/pelanggan |
| Livewire | Memenuhi kebutuhan interaktivitas tanpa membangun SPA terpisah |
| Tailwind CSS | Mempercepat pembuatan UI responsif dan konsisten |
| Alpine.js | Cukup untuk interaksi modal/dropdown tanpa framework frontend besar |
| Leaflet | Gratis, ringan, dan sesuai untuk map picker berbasis OpenStreetMap |
| Fonnte | Sesuai kebutuhan notifikasi WhatsApp operasional travel |
| Chart.js | Mudah digunakan untuk visualisasi laporan pendapatan sederhana |
| PHPUnit | Terintegrasi dengan Laravel dan GitHub Actions |

## Instalasi Dependency

Backend:

```bash
composer install
```

Frontend lokal:

```bash
npm install
```

Frontend CI:

```bash
npm ci
```

Build asset:

```bash
npm run build
```

Test backend:

```bash
php artisan test
```

## Dependency yang Sengaja Tidak Digunakan

| Dependency | Alasan tidak digunakan |
| --- | --- |
| Spatie Laravel Permission | Role hanya `admin`, `driver`, dan `pelanggan`, sehingga cukup memakai `RoleMiddleware` custom |
| Framework SPA penuh seperti React/Vue | Scope proyek lebih cocok dengan Blade, Livewire, dan Alpine.js |
| Paid map provider | Leaflet dan OpenStreetMap sudah cukup untuk kebutuhan titik jemput operasional |

## Catatan Maintenance

- Jalankan `composer update` dan `npm update` hanya jika ada kebutuhan upgrade yang jelas.
- Setelah upgrade dependency, jalankan `npm run build` dan `php artisan test`.
- Update dokumen ini jika ada package baru, package dihapus, atau versi mayor berubah.
