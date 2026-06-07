# Changelog

Semua perubahan penting pada proyek akan dicatat pada dokumen ini.

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
