# Changelog

Semua perubahan penting pada proyek akan dicatat pada dokumen ini.

## [2026-06-23] - Nayasha - Sprint 5 Responsiveness

### Added

* Tampilan stacking cards yang responsif pada mobile (`block md:hidden`/`block xl:hidden`) untuk tabel Booking, Pembayaran, dan Driver.
* Overlay/backdrop modal detail yang berpusat (`fixed inset-0 z-[150] ...`) pada mobile untuk Pembayaran detail dan Driver detail.
* Tombol tutup/dismiss detail panel pada mobile yang membersihkan status pencarian dengan route link stripping query parameter `selected_id`.

### Changed

* Refactoring file `livewire/admin/booking-table.blade.php` untuk melipat baris tabel menjadi kartu-kartu informatif pada layar mobile.
* Refactoring file `livewire/admin/pembayaran-table.blade.php` untuk melipat baris tabel menjadi kartu-kartu informatif pada layar mobile, serta menampilkan detail pembayaran sebagai backdrop overlay terpusat di layar mobile.
* Refactoring file `admin/drivers/index.blade.php` untuk mendukung tampilan mobile kartu pengemudi, overlay backdrop untuk rincian detail pengemudi, serta responsive padding dan rounded corners pada modal Tambah/Edit Driver.

### Tested

* `php artisan view:cache` (Pass)
* `php artisan test` (62 passed, 237 assertions)

## [2026-06-22] - Tim - Sprint 4 Maps & Integration

### Added

* Component `resources/views/components/map-viewer.blade.php` sebagai Leaflet map viewer reusable untuk menampilkan titik jemput dan titik antar.
* Integrasi map viewer pada detail manifest driver dan detail trip admin.
* Command scheduler `booking:send-confirmation` pada `routes/console.php` untuk mengirim WhatsApp konfirmasi keberangkatan pagi melalui `FonnteService`.
* Jadwal otomatis `Schedule::command('booking:send-confirmation')->dailyAt('06:00')->timezone('Asia/Jakarta')`.
* Export laporan admin ke CSV kompatibel Excel tanpa dependency tambahan.
* Notification bell admin dengan count dinamis untuk pembayaran menunggu, booking perlu verifikasi, dan trip siap berangkat.

### Changed

* Modal export laporan admin diubah dari placeholder PDF menjadi export CSV yang membawa filter periode dan shift aktif.
* Refactor query laporan admin agar data index dan export memakai helper query yang sama.
* Memperbarui status Sprint 4 pada `context/sprint-planning.md` menjadi selesai.

### Notes

* WA notifikasi cancel booking sudah tersedia sebelumnya via `FonnteService` pada `BookingController::cancel()` dan tetap dipertahankan.
* Command WA pagi memakai hari operasional `Asia/Jakarta`, termasuk window anti-duplikasi notifikasi harian.

### Tested

* `php -l routes/console.php` (Pass).
* `php -l app/Http/Controllers/Admin/LaporanController.php` (Pass).
* `php artisan view:cache` (Pass).
* `php artisan booking:send-confirmation --dry-run` (Pass, 0 booking untuk 2026-06-22).
* `npm run build` (Pass).
* `php artisan test` (62 passed, 237 assertions).

## [2026-06-22] - Tim - UAS Documentation & GitHub Action

### Added

* Dokumentasi UAS utama pada `docs/UAS-KONSTRUKSI-EVOLUSI-PERANGKAT-LUNAK.md`.
* Dokumentasi pembagian peran GitHub pada `docs/github-roles.md`.
* Dokumentasi GitHub Action pada `docs/github-actions.md`.
* Dokumentasi refactoring pada `docs/refactoring.md`.
* Workflow CI Laravel pada `.github/workflows/laravel-ci.yml` untuk install dependency, migration, build frontend, dan test otomatis.

### Changed

* Memperbarui `README.md` sebagai indeks proyek dan indeks dokumentasi UAS.
* Memperbarui `docs/INSTALLATION.MD` agar sesuai dengan `.env.example`, akun seeder, storage link, dan command aktual.
* Memperbarui `docs/dependencies.md` berdasarkan `composer.json`, `package.json`, CDN, dan layanan eksternal yang digunakan.
* Memperbarui `docs/features.md` dengan aktor, route, controller, status booking/trip, dan alur fitur proyek.

### Tested

* `npm run build` (Pass).
* `php artisan test` (62 passed, 237 assertions).

## [2026-06-22] - Kevin - Sprint 3

### Added

* Controller `App\Http\Controllers\Driver\DashboardController` untuk portal utama driver yang menampilkan performa/statistik driver jika tidak ada trip aktif, atau menyajikan panel kontrol trip aktif jika ada trip yang ditugaskan.
* Controller `App\Http\Controllers\Driver\TripController` untuk operasi perjalanan driver:
  - `index` untuk menampilkan riwayat trip selesai (estimasi pendapatan cash, total PAX, detail rute/jadwal).
  - `show` untuk rincian manifest detail penumpang.
  - `start` untuk memulai trip.
  - `pickup` untuk menandai penjemputan penumpang.
  - `dropoff` untuk menandai pengantaran penumpang yang sekaligus otomatis mencatat pelunasan tunai (cash) di database.
  - `confirmPayment` untuk pencatatan konfirmasi pembayaran pelunasan cash secara terpisah/manual.
  - `complete` untuk menyelesaikan trip jika semua penumpang telah sampai di tujuan.
* View `resources/views/driver/trips/index.blade.php` sebagai tabel riwayat perjalanan driver dengan widget statistik ringkasan.
* View `resources/views/driver/trips/show.blade.php` sebagai rincian manifest penumpang statis.
* File pengujian `tests/Feature/DriverTripTest.php` untuk memvalidasi alur dashboard driver, startup trip, penjemputan, pengantaran & auto-pelunasan cash, serta penyelesaian trip.

### Changed

* Modifikasi `routes/web.php` untuk mendaftarkan 8 route driver operasional di bawah prefix `driver`, name `driver.`, dan middleware `role:driver`.
* Modifikasi `resources/views/driver/dashboard.blade.php` menjadi panel kontrol perjalanan driver yang interaktif dengan integrasi Leaflet Map OSM, progress stepper, checklist jemput/antar penumpang, tombol hubungi via WhatsApp, dan modal konfirmasi pembayaran pelunasan cash.

### Tested

* Menjalankan unit test `php artisan test tests/Feature/DriverTripTest.php` (Pass).
* Menjalankan semua suite pengujian `php artisan test` (60 passed, 100% OK).

## [2026-06-19] - Rayfo - Sprint 3

### Added

* Controller `App\Http\Controllers\Admin\LaporanController` untuk menampilkan halaman laporan keuangan admin (index) dan placeholder export (Sprint 4).
* View `resources/views/admin/laporan/index.blade.php` — halaman laporan keuangan admin dengan:
  - 4 summary card statistik (Total Booking, Pendapatan Bersih, Total Trip, Okupansi Rata-rata)
  - Grafik pendapatan harian menggunakan Chart.js via CDN
  - Tabel rincian laporan harian dengan breakdown booking, trip, pendapatan, dan pembatalan
  - Modal detail laporan operasional (breakdown DP, pelunasan, sisa pembayaran)
  - Modal export PDF (placeholder, fungsional dijadwalkan Sprint 4)
  - Filter periode (Hari Ini, 7 Hari Terakhir, Bulan Ini) dan filter shift (Semua, Pagi, Malam)

### Changed

* Modifikasi `routes/web.php` untuk menambahkan route `admin.laporan.index` dan `admin.laporan.export` di dalam admin group.

### Notes

* Grafik pendapatan menggunakan Chart.js (konversi dari Recharts/React pada design export Figma Make).
* Export PDF/Excel masih placeholder — implementasi fungsional dijadwalkan Sprint 4.
* Data laporan dihitung secara dinamis dari tabel `bookings`, `trips`, dan `pembayaran`.
## [2026-06-19] - Nayasha - Sprint 3

### Added
* Menambahkan `App\Observers\TripObserver` untuk sinkronisasi otomatis status booking saat status trip di-update (`on_trip`, `completed`, `cancelled`, `ready`, `new`), serta pemulihan status saat trip dihapus.
* Menambahkan `App\Observers\DetailTripObserver` untuk mengubah status booking menjadi `assigned_to_trip` secara otomatis saat ditugaskan ke trip, dan mengembalikannya ke `dikonfirmasi` saat dihapus/dikeluarkan dari trip.

### Changed
* Mendaftarkan model observers (`TripObserver` dan `DetailTripObserver`) pada `App\Providers\AppServiceProvider`.
* Melakukan refaktorisasi `App\Http\Controllers\Admin\TripController` untuk membersihkan kode pembaruan manual status booking dan mendelegasikannya sepenuhnya ke Eloquent Observers.

### Tested
* `php artisan test --filter test_booking_status_auto_updates_via_observers` (Pass)

## [2026-06-18] - Role Profile Refresh

### Added
* Menambahkan halaman profil khusus admin dan driver agar tidak lagi memakai tampilan Breeze default.
* Menambahkan field `Nomor Telepon / WhatsApp` pada profil pelanggan dan driver.

### Changed
* Menyelaraskan tampilan profil pelanggan dengan layout publik Singgalang Jaya Travel.
* Menyinkronkan perubahan nama dan nomor telepon profil ke tabel `pelanggan` dan `drivers` sesuai role akun.

### Tested
* `php artisan test tests/Feature/ProfileTest.php tests/Feature/Auth/PasswordUpdateTest.php`
* `npm run build`

## [2026-06-18] - Fonnte Delivery Fix

### Changed
* Menormalisasi nomor WhatsApp sebelum dikirim ke Fonnte, misalnya `0812...` menjadi `62812...`.
* Menambahkan payload `countryCode` dan `connectOnly=false` agar request Fonnte tidak langsung ditolak saat device sedang offline.
* Menambahkan konfigurasi `.env.example` untuk `FONNTE_COUNTRY_CODE` dan `FONNTE_CONNECT_ONLY`.
* Menyimpan respons Fonnte `process: pending` sebagai status lokal `pending`, bukan `sent`.
* Mengubah pesan sukses tombol WA manual menjadi informasi antrean Fonnte agar tidak disalahartikan sebagai delivered.

### Tested
* `php artisan test tests/Feature/FonnteServiceTest.php tests/Feature/Admin/PembayaranNotificationTest.php tests/Feature/Admin/TripAssignTest.php`

## [2026-06-18] - Auth UI Polish

### Added
* Menambahkan field `Nomor Telepon` pada form register pelanggan dan menyimpannya ke kolom `pelanggan.no_hp`.

### Changed
* Mempercantik halaman login dan register mengikuti desain export: split screen, branding Singgalang Jaya Travel, Poppins, form card responsif, dan styling navy blue.
* Menyesuaikan layout guest agar halaman login/register bisa memakai tampilan full-page tanpa merusak halaman Breeze auth lain.

### Tested
* `php artisan test tests/Feature/Auth/RegistrationTest.php tests/Feature/Auth/AuthenticationTest.php tests/Feature/Auth/PasswordResetTest.php`
* `npm run build`

## [2026-06-18] - Trip Driver Armada Binding

### Changed
* Menghapus pilihan armada terpisah pada form buat trip baru. Armada sekarang otomatis mengikuti armada yang terhubung ke driver.
* Menyesuaikan update driver trip agar `trips.armada_id` ikut berubah ke armada milik driver baru.
* Menyembunyikan aksi ganti armada terpisah pada detail trip agar driver dan armada tetap satu paket.

### Fixed
* Menolak pembuatan trip dengan driver nonaktif dari request manual.

### Tested
* `php artisan test tests/Feature/Admin/TripAssignTest.php`
* `npm run build`

## [2026-06-18] - WhatsApp Booking Notifications

### Added
* Mengirim WhatsApp ke pelanggan saat DP diverifikasi admin dan status booking menjadi `dikonfirmasi`.
* Mengirim WhatsApp ke pelanggan dan driver saat booking ditugaskan ke trip.
* Menambahkan `BookingWhatsappNotificationService` untuk memusatkan format pesan booking dan pengiriman via Fonnte.

### Changed
* Menyesuaikan `FonnteService` agar mengirim payload sebagai form request ke API Fonnte.

### Fixed
* Mencegah pesan WhatsApp verifikasi DP terkirim ulang ketika aksi verifikasi yang sama dipanggil lagi.

### Tested
* `php artisan test tests/Feature/Admin/PembayaranNotificationTest.php tests/Feature/Admin/TripAssignTest.php`

## [2026-06-17] - Sprint 0-2 Corrections Follow-up

### Changed
* Mengubah status booking pelanggan menjadi timeline dengan titik biru untuk progress yang sudah terjadi dan abu-abu untuk langkah berikutnya.
* Menampilkan jadwal berangkat, shift, dan jam pada detail booking pelanggan serta kartu/detail trip admin.
* Menampilkan informasi driver, armada, dan tombol WhatsApp driver setelah booking masuk status `assigned_to_trip`.

### Fixed
* Menyelaraskan validasi ganti driver/armada trip agar konflik hanya dihitung pada tanggal dan shift yang sama.
* Menambahkan test regresi untuk multi-trip pada satu jadwal dan konflik driver/armada saat assign trip.
* Menandai halaman `Booking Saya` sebagai selesai di dokumen Sprint 2.
## [2026-06-16] - Sprint 0-2 Corrections

### Added
* Menambahkan route dan view `booking.index` / `booking.show` untuk halaman Booking Saya pelanggan.
* Menambahkan `AssignBookingRequest` untuk validasi assign booking ke trip.

### Changed
* Mengarahkan login/register pelanggan ke halaman publik, bukan dashboard driver/default Breeze.
* Mengubah navbar publik setelah pelanggan login menjadi Home, Jadwal, Booking Saya, Charter, Kontak, dan dropdown Profil.
* Menyelaraskan flow pembayaran DP ke status final: `booking_dibuat` -> `menunggu_verifikasi` -> `dikonfirmasi` tanpa timer 30 menit.
* Mengizinkan lebih dari satu trip untuk jadwal yang sama selama driver/armada tidak bentrok di shift yang sama.

### Fixed
* Memperbaiki tombol Assign Trip admin yang sebelumnya memakai `jadwal_id` sebagai `trip_id`.
* Menghapus pemakaian status lama `menunggu_pembayaran` dan kolom `batas_bayar_at` dari flow booking.
* Memindahkan query assignable trip dari Blade ke `Admin\TripController`.

## [2026-06-14] - Rayhan - Sprint 3 (Armada Separation & CRUD)

### Added
* Migration `2026_06_07_000000_create_armada_table.php` untuk tabel `armada` terpisah.
* Eloquent model `App\Models\Armada` dengan relasi `driver` dan `trips`.
* Seeder `Database\Seeders\ArmadaSeeder` untuk menyuntikkan data armada.
* Controller `App\Http\Controllers\Admin\ArmadaController` untuk operasi CRUD armada.
* Form request `App\Http\Requests\Admin\StoreArmadaRequest` & `UpdateArmadaRequest` untuk memvalidasi form armada.
* View `resources/views/admin/armada/index.blade.php` untuk antarmuka CRUD armada menggunakan modal.
* Tes fitur `Tests\Feature\Admin\ArmadaTest` untuk menjamin kualitas fitur CRUD armada.

### Changed
* Modifikasi migration `2026_06_07_000004_create_drivers_table.php` untuk menambahkan foreign key `armada_id` dan menghapus kolom spesifikasi kendaraan langsung.
* Modifikasi migration `2026_06_07_000007_create_trips_table.php` untuk menambahkan foreign key `armada_id`.
* Modifikasi model `App\Models\Driver` dan `App\Models\Trip` untuk relasi `armada` (`belongsTo`).
* Modifikasi controller `App\Http\Controllers\Admin\DriverController` dan `App\Http\Controllers\Admin\TripController` untuk menyesuaikan relasi armada yang baru.
* Modifikasi controller `App\Http\Controllers\HomeController` dan `App\Http\Controllers\CekBookingController` untuk memuat data armada dari relasi.
* Modifikasi views `admin.drivers.index`, `admin.trips.index`, `admin.trips.create`, `admin.trips.show` agar mengambil detail kendaraan dari relasi `armada`.
* Modifikasi public views `home.blade.php` (Landing Page) dan `cek-booking.show.blade.php` agar membaca detail armada dari model Armada.
* Modifikasi driver view `dashboard.blade.php` agar memuat detail armada dari relasi driver.
* Modifikasi seeder `DriverSeeder` dan `DatabaseSeeder` untuk menautkan driver ke armada yang valid.
* Modifikasi `routes/web.php` untuk menambahkan resource route `admin.armada`.
* Modifikasi component `resources/views/components/sidebar-admin.blade.php` untuk mengarahkan link armada ke route `admin.armada.index`.
* Modifikasi test suite `Tests\Feature\Admin\DriverTest` untuk menyertakan `armada_id` pada factory/seeder driver.

### Fixed
* Perbaikan method `assignBooking` pada controller dan form action binding di `admin.trips.index` menggunakan dynamic `url()` helper agar dapat berfungsi di subdirektori.
* Menghindari JavaScript / Alpine.js syntax error pada pencarian driver, armada, dan booking di `admin.trips.show` dengan membalut string data PHP menggunakan `addslashes()`.

## [2026-06-14] - Nayasha - Sprint 2

### Added

* Controller `App\Http\Controllers\Admin\BookingController` untuk mengelola booking admin (index, show, cancel).
* Controller `App\Http\Controllers\Admin\PembayaranController` untuk verifikasi pembayaran admin (index, show, verify, reject).
* Livewire Component `App\Livewire\Admin\BookingTable` dan view `resources/views/livewire/admin/booking-table.blade.php` untuk antarmuka pencarian, pemfilteran, dan pengiriman WA konfirmasi.
* Livewire Component `App\Livewire\Admin\PembayaranTable` dan view `resources/views/livewire/admin/pembayaran-table.blade.php` untuk antarmuka split-screen verifikasi pembayaran beserta panel detail interaktif, preview lightbox, modal persetujuan, dan modal alasan penolakan.
* Blade view `resources/views/admin/bookings/index.blade.php`, `resources/views/admin/bookings/show.blade.php`, dan `resources/views/admin/pembayaran/index.blade.php` untuk halaman admin.

### Changed

* Modifikasi `routes/web.php` untuk mendaftarkan resource route bookings, pembayaran, aksi cancel booking, verifikasi, dan penolakan pembayaran.
## [2026-06-14] - Kevin - Sprint 2

### Added
* Berkas view `resources/views/admin/trips/show.blade.php` untuk antarmuka detail trip admin, manifes penumpang, serta modal penugasan driver dan alokasi booking.

### Changed
* Modifikasi `routes/web.php` untuk menambahkan route POST `trips/{trip}/assign` dan DELETE `trips/{trip}/remove/{detailTrip}` di bawah middleware admin.
* Modifikasi `app/Http/Controllers/Admin/TripController.php` untuk mengimplementasikan method `show`, `update` (transisi status trip dan penugasan driver), `destroy` (menghapus trip dengan aman), `assignBooking` (penugasan booking ke trip dengan validasi sisa kuota kursi), dan `removeBooking` (pelepasan booking dari trip).
* Modifikasi `resources/views/admin/trips/index.blade.php` untuk menghubungkan tautan Detail Trip serta mengaktifkan tombol alokasi booking dari panel antrean ke trip terkait.
* Modifikasi `resources/views/admin/trips/create.blade.php` untuk mendukung pre-seleksi jadwal berdasarkan query parameter `jadwal_id`.

### Notes
* Fitur alokasi booking ke trip secara otomatis mengintegrasikan data driver dan armada kendaraan, karena informasi armada melekat langsung pada profil driver.

## [2026-06-12] - Rayhan - Sprint 1 (Alignment)

### Added

* Migration `2026_06_07_000009_create_whatsapp_notifications_table.php` untuk tabel `whatsapp_notifications`.
* Eloquent model `App\Models\WhatsappNotification` dengan relasi `booking`.
* Service `App\Services\FonnteService` untuk mengirimkan notifikasi WhatsApp via FonnteAPI dengan auto-logging.

### Changed

* Modifikasi migration `2026_06_07_000005_create_bookings_table` untuk memperbarui status booking enum (9 nilai) dan menambahkan kolom `batas_bayar_at` dan `alasan_pembatalan`.
* Modifikasi migration `2026_06_07_000007_create_trips_table` untuk memperbarui status trip enum (new, ready, on_trip, completed, cancelled).
* Modifikasi model `App\Models\Booking` untuk menyesuaikan konstanta status, fillable, casts, helper functions, dan relasi `whatsappNotifications()`.
* Modifikasi model `App\Models\Trip` untuk menyesuaikan konstanta status trip baru.
* Modifikasi controller `App\Http\Controllers\Admin\DashboardController`, `DriverController`, dan `TripController` agar selaras dengan nama status booking dan status trip baru (`on_trip`, `completed`).
* Modifikasi component `resources/views/components/status-badge.blade.php` untuk memetakan nama status booking dan trip yang baru ke style Tailwind.

## [2026-06-10] - Nayasha - Sprint 1

### Added

* Controller `App\Http\Controllers\Admin\DriverController` untuk menangani CRUD Driver dan relasi akun login user.
* Form request `App\Http\Requests\Driver\StoreDriverRequest` & `UpdateDriverRequest` untuk memvalidasi form input tambah/edit driver.
* Berkas view `resources/views/admin/drivers/index.blade.php` untuk menampilkan antarmuka manajemen driver dengan layout dua kolom (tabel dan detail panel), pencarian, penyaringan status, serta modal input.
* Tes fitur `Tests\Feature\Admin\DriverTest` untuk menjamin validitas alur CRUD driver secara otomatis.

### Changed

* Modifikasi `routes/web.php` untuk mendaftarkan resource route `drivers` di bawah admin group.
* Modifikasi `resources/views/components/status-badge.blade.php` untuk memetakan status driver dinamis (`tersedia`, `sedang_bertugas`, `tidak_aktif`) dengan warna badge yang sesuai.
* Modifikasi model `App\Models\Driver` untuk menambahkan dynamic accessor `getDynamicStatusAttribute()`.

### Notes

* Implementasi menggunakan database transactions (`DB::transaction`) untuk menjaga integritas data relasi 1:1 antara driver dan user login.
## [2026-06-11] - Kevin - Sprint 1

### Added

* Controller `App\Http\Controllers\Admin\TripController` untuk menangani modul Admin Trip (Index & Create).
* Form Request `App\Http\Requests\Admin\StoreTripRequest` untuk memvalidasi pembuatan data trip baru.
* View daftar trip `resources/views/admin/trips/index.blade.php` dengan grid trip berdasarkan status dan sidebar antrean booking.
* View form pembuatan trip `resources/views/admin/trips/create.blade.php` dengan dropdown jadwal & driver aktif serta panel preview interaktif (Alpine.js).

### Changed

* Modifikasi `routes/web.php` untuk mendaftarkan resource route `trips` admin.

### Notes

* Modul trips admin berjalan secara dinamis dan memiliki safeguard/placeholder untuk fitur show, edit, update, dan destroy yang akan diimplementasikan pada Sprint 2 & 4.

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
