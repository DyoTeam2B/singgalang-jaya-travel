# Changelog

Semua perubahan penting pada proyek akan dicatat pada dokumen ini.

## [2026-06-10] - Rayfo - Sprint 1

### Added

* Controller `App\Http\Controllers\Admin\RuteController` untuk menangani operasi CRUD Rute perjalanan.
* Controller `App\Http\Controllers\Admin\JadwalController` untuk menangani operasi CRUD & toggle status Jadwal keberangkatan.
* Form request `App\Http\Requests\Admin\StoreRuteRequest` & `UpdateRuteRequest` untuk validasi form Rute.
* Form request `App\Http\Requests\Admin\StoreJadwalRequest` & `UpdateJadwalRequest` untuk validasi form Jadwal.
* View CRUD Rute: `index.blade.php`, `create.blade.php`, `edit.blade.php` di bawah folder `resources/views/admin/rute/`.
* View CRUD Jadwal: `index.blade.php`, `create.blade.php`, `edit.blade.php` di bawah folder `resources/views/admin/jadwal/`.

### Changed

* Modifikasi `routes/web.php` untuk mendaftarkan resource route Rute & Jadwal, custom route toggle status jadwal, serta mengarahkan dashboard ke `DashboardController`.
* Modifikasi `App\Http\Controllers\Admin\DashboardController` untuk menghitung statistik widget secara dinamis dari database dan memuat daftar booking terbaru.
* Modifikasi `resources/views/admin/dashboard.blade.php` untuk menampilkan statistik dinamis dan loop data booking terbaru dari database.
* Modifikasi `resources/views/components/sidebar-admin.blade.php` untuk menambahkan tautan menu navigasi Rute.

### Fixed

* Mengatasi error autoloading composer PSR-4 dengan melakukan regenerasi file optimized classmap autoload.

### Notes

* Modul Rute, Jadwal, dan Dashboard admin berjalan sepenuhnya secara dinamis dan siap diintegrasikan dengan modul operasional lain.

## [2026-06-07] - Rayhan - Sprint 1

### Added
* Controller `App\Http\Controllers\HomeController` untuk menangani halaman landing page publik.
* Controller `App\Http\Controllers\JadwalPublicController` untuk menangani halaman pencarian dan daftar jadwal publik.
* View `resources/views/public/home.blade.php` sebagai landing page dengan layout responsif (Hero, Features, Schedules, Fleet, Charter, Contact, CTA).
* View `resources/views/public/jadwal/index.blade.php` sebagai halaman khusus daftar jadwal publik dengan filter pencarian asal, tujuan, dan tanggal.
* Class view component `App\View\Components\PublicLayout` untuk binding `<x-public-layout>`.

### Changed
* Modifikasi `routes/web.php` untuk mendaftarkan route landing page (`/` -> `home`) dan jadwal publik (`/jadwal` -> `jadwal.index`).

## [2026-06-07] - Kevin - Sprint 0

### Added
* Migration `2026_06_07_000007_create_trips_table` untuk tabel `trips`
* Migration `2026_06_07_000008_create_detail_trip_table` untuk tabel `detail_trip`
* Eloquent model `App\Models\Trip` dengan relasi `driver`, `jadwal`, dan `detailTrips`
* Eloquent model `App\Models\DetailTrip` dengan relasi `trip` dan `booking`
* Component sidebar driver `resources/views/components/sidebar-driver.blade.php` untuk navigasi driver
* Layout driver `resources/views/layouts/driver.blade.php` terintegrasi dengan header, profil dropdown, dan menu mobile
* Component class `App\View\Components\DriverLayout` untuk mempermudah binding `<x-driver-layout>`

### Changed
* View dashboard driver `resources/views/driver/dashboard.blade.php` dari placeholder ke layout driver proper

## [2026-06-07] - Rayfo - Sprint 0

### Added
* Migration `2026_06_07_000001_create_rute_table` untuk tabel `rute`
* Migration `2026_06_07_000002_create_jadwal_table` untuk tabel `jadwal`
* Eloquent model `App\Models\Rute` dengan relasi `jadwal`
* Eloquent model `App\Models\Jadwal` dengan relasi `rute`, `bookings`, dan `trips`
* Seeder `Database\Seeders\RuteSeeder` untuk menyuntikkan data rute perjalanan default
* Component card `resources/views/components/card.blade.php` sebagai kartu reusable
* Component sidebar admin `resources/views/components/sidebar-admin.blade.php` untuk menu navigasi admin
* Layout admin `resources/views/layouts/admin.blade.php` terintegrasi dengan mobile drawer dan modal logout
* Component class `App\View\Components\AdminLayout` untuk mempermudah binding `<x-admin-layout>`
* Controller `App\Http/Controllers/Admin/DashboardController` untuk mengontrol dashboard admin

### Changed
* Modifikasi migration `2026_06_07_000005_create_bookings_table` untuk menambahkan foreign key constraint `jadwal_id` ke tabel `jadwal`
* Modifikasi `Database\Seeders\DatabaseSeeder` untuk menjalankan `RuteSeeder`
* Modifikasi dashboard view `resources/views/admin/dashboard.blade.php` dengan layout proper premium yang dinamis dan responsif
* Modifikasi `routes/web.php` untuk mengarahkan route dashboard admin ke `DashboardController@index`

### Notes
* Database seeding (`php artisan migrate:fresh --seed`) telah sukses dijalankan secara lokal.

## [2026-06-07] - Nayasha - Sprint 0

### Added
* Migration `2026_06_07_000004_create_drivers_table` untuk tabel `drivers`
* Eloquent model `App\Models\Driver` dengan relasi `user` dan `trips`
* Seeder `Database\Seeders\DriverSeeder` untuk menyuntikkan 3 sample driver
* Component status-badge `resources/views/components/status-badge.blade.php` untuk memetakan status di seluruh panel
* Component alert `resources/views/components/alert.blade.php` untuk menampilkan flash message terpadu

### Changed
* Modifikasi `App\Models\User` untuk menambahkan relasi 1:1 `driver()`
* Modifikasi `Database\Seeders\DatabaseSeeder` untuk memanggil `DriverSeeder`

### Notes
* Pengecekan database seeding (`php artisan migrate:fresh --seed`) telah sukses dijalankan.

## [2026-06-07] - Rayhan - Sprint 0

### Added
* Migration `2026_06_07_000003_create_pelanggan_table` untuk tabel `pelanggan`
* Migration `2026_06_07_000005_create_bookings_table` untuk tabel `bookings`
* Migration `2026_06_07_000006_create_pembayaran_table` untuk tabel `pembayaran`
* Eloquent model `App\Models\Pelanggan` dengan relasi `bookings`
* Eloquent model `App\Models\Booking` dengan relasi `pelanggan`, `jadwal`, `pembayaran`, dan `detailTrips`
* Eloquent model `App\Models\Pembayaran` dengan relasi `booking`
* Layout file `resources/views/layouts/public.blade.php` untuk halaman public
* Sub-view navbar `resources/views/layouts/public/navbar.blade.php` dengan Alpine.js mobile menu
* Sub-view footer `resources/views/layouts/public/footer.blade.php`

### Changed
* Modifikasi `tailwind.config.js` untuk menambahkan font family `poppins`

### Notes
* Migration `bookings` ditunda foreign key constraint-nya untuk `jadwal_id` karena tabel `jadwal` belum dibuat oleh Rayfo.

## [Unreleased]

### Added

* Inisialisasi proyek Laravel 13
* Konfigurasi Git Repository
* Instalasi Laravel Breeze Authentication
* Instalasi Livewire v3
* Konfigurasi Tailwind CSS
* Pembuatan TestComponent untuk pengujian Livewire

### Tested

* Login Authentication berjalan normal
* Dashboard berhasil diakses
* Livewire berhasil ditampilkan pada halaman dashboard
