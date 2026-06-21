# Feature Documentation - Singgalang Jaya Travel

Dokumen ini menjelaskan fitur aplikasi berdasarkan aktor, alur bisnis, route, controller, dan file pendukung yang ada pada proyek.

## Aktor dan Hak Akses

| Aktor | Hak akses | Middleware |
| --- | --- | --- |
| Pelanggan | Booking, pembayaran DP, cek status, booking saya, edit/cancel booking | `auth`, `role:pelanggan` |
| Admin | Dashboard, rute, armada, jadwal, driver, booking, pembayaran, trip, laporan | `auth`, `role:admin` |
| Driver | Dashboard driver, daftar trip, manifest, pickup/dropoff, pelunasan, complete trip | `auth`, `role:driver` |
| Guest | Landing page, lihat jadwal, cek booking publik | Tidak wajib login |

## Ringkasan Modul

| Modul | Aktor | Status | File utama |
| --- | --- | --- | --- |
| Landing page | Guest/Pelanggan | Selesai | `HomeController`, `resources/views/public/home.blade.php` |
| Jadwal publik | Guest/Pelanggan | Selesai | `JadwalPublicController`, `resources/views/public/jadwal/index.blade.php` |
| Booking travel | Pelanggan | Selesai | `BookingController`, `BookingService`, `BookingForm` |
| Pembayaran DP | Pelanggan/Admin | Selesai | `PembayaranController`, `Admin\PembayaranController`, `PembayaranTable` |
| Booking saya | Pelanggan | Selesai | `BookingController@index`, `BookingController@show` |
| Cek booking publik | Guest/Pelanggan | Selesai | `CekBookingController` |
| Dashboard admin | Admin | Selesai | `Admin\DashboardController` |
| CRUD rute | Admin | Selesai | `Admin\RuteController` |
| CRUD armada | Admin | Selesai | `Admin\ArmadaController` |
| CRUD jadwal | Admin | Selesai | `Admin\JadwalController` |
| CRUD driver | Admin | Selesai | `Admin\DriverController` |
| Kelola booking | Admin | Selesai | `Admin\BookingController`, `BookingTable` |
| Manajemen trip | Admin | Selesai | `Admin\TripController`, `AssignBookingRequest` |
| Dashboard dan manifest driver | Driver | Selesai | `Driver\DashboardController`, `Driver\TripController` |
| Laporan | Admin | Selesai | `Admin\LaporanController` |
| Notifikasi WhatsApp | Sistem | Selesai sebagian | `FonnteService`, `BookingWhatsappNotificationService` |

## 1. Authentication dan Role Redirect

Tujuan: memastikan pengguna masuk ke halaman sesuai perannya.

Alur:

1. Pengguna login melalui Laravel Breeze.
2. Sistem membaca `users.role`.
3. Admin diarahkan ke `/admin/dashboard`.
4. Driver diarahkan ke `/driver/dashboard`.
5. Pelanggan diarahkan ke landing page publik.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/login` | `login` | Breeze auth controller |
| POST | `/login` | `login` | Breeze auth controller |
| GET | `/register` | `register` | Breeze auth controller |
| POST | `/register` | `register` | Breeze auth controller |
| POST | `/logout` | `logout` | Breeze auth controller |
| GET | `/dashboard` | `dashboard` | Closure redirect by role |

## 2. Landing Page

Tujuan: menampilkan profil layanan travel, rute, armada, charter, dan kontak.

Aktor: Guest dan pelanggan.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/` | `home` | `HomeController@index` |

File view:

- `resources/views/public/home.blade.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/partials/public-navbar.blade.php`
- `resources/views/layouts/partials/public-footer.blade.php`

## 3. Jadwal Keberangkatan Publik

Tujuan: pengguna dapat melihat dan memfilter jadwal berdasarkan asal, tujuan, tanggal, shift, dan ketersediaan kursi.

Aktor: Guest dan pelanggan.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/jadwal` | `jadwal.index` | `JadwalPublicController@index` |
| GET | `/jadwal/available` | `jadwal.available` | `JadwalPublicController@available` |
| GET | `/jadwal/{id}/check-kuota` | `jadwal.checkKuota` | `JadwalPublicController@checkKuota` |

## 4. Booking Travel Pelanggan

Tujuan: pelanggan membuat pemesanan travel pada jadwal yang tersedia.

Aktor: Pelanggan login.

Alur:

1. Pelanggan memilih jadwal.
2. Pelanggan mengisi data booking, jumlah penumpang, alamat jemput, tujuan, dan titik lokasi jemput.
3. `BookingForm` menghitung total tarif berdasarkan rute dan jumlah penumpang.
4. `BookingService` membuat `kode_booking` dengan format `SJT-{YYYYMMDD}-{RANDOM5}`.
5. Booking disimpan dengan status awal `booking_dibuat`.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/booking/create` | `booking.create` | `BookingController@create` |
| POST | `/booking` | `booking.store` | `BookingController@store` |
| GET | `/booking/{kode}/review` | `booking.review` | `BookingController@review` |

File pendukung:

- `app/Services/BookingService.php`
- `app/Livewire/BookingForm.php`
- `resources/views/livewire/booking-form.blade.php`
- `resources/views/components/map-picker.blade.php`

## 5. Pembayaran DP Pelanggan

Tujuan: pelanggan mengunggah bukti pembayaran DP flat Rp50.000.

Aktor: Pelanggan login.

Alur:

1. Pelanggan membuka halaman pembayaran booking.
2. Pelanggan mengunggah bukti transfer.
3. Sistem menyimpan data pembayaran.
4. Status booking berubah menjadi `menunggu_verifikasi`.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/booking/{kode}/pembayaran` | `booking.pembayaran` | `PembayaranController@show` |
| POST | `/booking/{kode}/pembayaran` | `booking.pembayaran.store` | `PembayaranController@store` |

## 6. Booking Saya

Tujuan: pelanggan melihat daftar booking, detail perjalanan, status pembayaran, timeline, dan informasi driver/armada setelah booking masuk trip.

Aktor: Pelanggan login.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/booking-saya` | `booking.index` | `BookingController@index` |
| GET | `/booking/{kode}` | `booking.show` | `BookingController@show` |
| GET | `/booking/{kode}/edit` | `booking.edit` | `BookingController@edit` |
| PUT | `/booking/{kode}` | `booking.update` | `BookingController@update` |
| PUT | `/booking/{kode}/cancel` | `booking.cancel` | `BookingController@cancel` |

## 7. Cek Status Booking Publik

Tujuan: pengguna dapat mengecek status booking menggunakan kode booking tanpa login.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/cek-booking` | `cek-booking.index` | `CekBookingController@index` |
| POST | `/cek-booking` | `cek-booking.show` | `CekBookingController@show` |

## 8. Dashboard Admin

Tujuan: admin melihat ringkasan operasional seperti total booking, trip, pendapatan, driver, armada, dan booking terbaru.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/admin/dashboard` | `admin.dashboard` | `Admin\DashboardController@index` |

## 9. CRUD Rute

Tujuan: admin mengelola master rute yang menjadi dasar jadwal dan tarif.

Route terkait: resource route `/admin/rute` dengan name `admin.rute.*`.

Controller: `App\Http\Controllers\Admin\RuteController`.

Operasi utama:

- List dan search rute.
- Tambah rute.
- Edit rute.
- Hapus rute jika tidak memiliki jadwal terkait.

## 10. CRUD Armada

Tujuan: admin mengelola data kendaraan secara terpisah dari driver.

Route terkait: resource route `/admin/armada` dengan name `admin.armada.*`.

Controller: `App\Http\Controllers\Admin\ArmadaController`.

Data utama:

- Nama mobil.
- Nomor plat.
- Kapasitas kursi.
- Status armada.

## 11. CRUD Jadwal

Tujuan: admin membuat jadwal keberangkatan berdasarkan rute, tanggal, jam, shift, kuota, dan status.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| Resource | `/admin/jadwal` | `admin.jadwal.*` | `Admin\JadwalController` |
| PUT | `/admin/jadwal/{jadwal}/toggle` | `admin.jadwal.toggle` | `Admin\JadwalController@toggleStatus` |

Aturan bisnis:

- Jadwal mengambil harga dari rute.
- Kuota tidak boleh lebih kecil dari jumlah penumpang aktif.
- Jadwal otomatis dapat ditandai penuh jika booked seat mencapai kuota.

## 12. CRUD Driver

Tujuan: admin mengelola data driver dan akun login driver.

Route terkait: resource route `/admin/drivers` dengan name `admin.drivers.*`.

Controller: `App\Http\Controllers\Admin\DriverController`.

Aturan bisnis:

- Driver terhubung ke satu akun user ber-role `driver`.
- Driver wajib memiliki armada aktif saat dipakai membuat/menjalankan trip.

## 13. Kelola Booking Admin

Tujuan: admin memantau booking pelanggan dan melakukan pembatalan bila diperlukan.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/admin/bookings` | `admin.bookings.index` | `Admin\BookingController@index` |
| GET | `/admin/bookings/{booking}` | `admin.bookings.show` | `Admin\BookingController@show` |
| PUT | `/admin/bookings/{booking}/cancel` | `admin.bookings.cancel` | `Admin\BookingController@cancel` |

Komponen Livewire:

- `App\Livewire\Admin\BookingTable`
- `resources/views/livewire/admin/booking-table.blade.php`

## 14. Verifikasi Pembayaran Admin

Tujuan: admin memverifikasi atau menolak bukti pembayaran DP.

Alur:

1. Pelanggan upload bukti DP.
2. Admin membuka daftar pembayaran.
3. Admin melihat detail bukti transfer.
4. Jika valid, status booking menjadi `dikonfirmasi`.
5. Jika ditolak, booking kembali menunggu upload bukti yang benar.
6. Setelah DP diverifikasi, sistem dapat mengirim notifikasi WhatsApp pelanggan.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/admin/pembayaran` | `admin.pembayaran.index` | `Admin\PembayaranController@index` |
| GET | `/admin/pembayaran/{pembayaran}` | `admin.pembayaran.show` | `Admin\PembayaranController@show` |
| PUT | `/admin/pembayaran/{pembayaran}/verify` | `admin.pembayaran.verify` | `Admin\PembayaranController@verify` |
| PUT | `/admin/pembayaran/{pembayaran}/reject` | `admin.pembayaran.reject` | `Admin\PembayaranController@reject` |

## 15. Manajemen Trip Admin

Tujuan: admin membuat trip dari jadwal, menugaskan driver/armada, dan mengalokasikan booking ke trip.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| Resource | `/admin/trips` | `admin.trips.*` | `Admin\TripController` |
| POST | `/admin/trips/{trip}/assign` | `admin.trips.assign` | `Admin\TripController@assignBooking` |
| DELETE | `/admin/trips/{trip}/remove/{detailTrip}` | `admin.trips.remove` | `Admin\TripController@removeBooking` |

Aturan bisnis:

- Trip dibuat dari satu jadwal.
- Satu jadwal dapat memiliki banyak trip.
- Driver dan armada tidak boleh bentrok pada tanggal dan shift yang sama.
- Armada mengikuti armada yang terhubung ke driver.
- Booking hanya dapat di-assign jika statusnya `dikonfirmasi` dan jadwalnya sama dengan trip.
- Kapasitas armada harus cukup untuk jumlah penumpang booking.

## 16. Dashboard Driver dan Manifest

Tujuan: driver melihat trip yang ditugaskan dan daftar penumpang.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/driver/dashboard` | `driver.dashboard` | `Driver\DashboardController@index` |
| GET | `/driver/trips` | `driver.trips.index` | `Driver\TripController@index` |
| GET | `/driver/trips/{trip}` | `driver.trips.show` | `Driver\TripController@show` |

Informasi manifest:

- Nama pelanggan.
- Nomor HP.
- Jumlah penumpang.
- Alamat jemput dan tujuan.
- Titik peta Leaflet.
- Status jemput/antar.
- Sisa pelunasan.

## 17. Operasional Driver

Tujuan: driver menjalankan trip dari mulai hingga selesai.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| PUT | `/driver/trips/{trip}/start` | `driver.trips.start` | `Driver\TripController@start` |
| PUT | `/driver/trips/{trip}/pickup/{detailTrip}` | `driver.trips.pickup` | `Driver\TripController@pickup` |
| PUT | `/driver/trips/{trip}/dropoff/{detailTrip}` | `driver.trips.dropoff` | `Driver\TripController@dropoff` |
| PUT | `/driver/trips/{trip}/confirm-payment/{detailTrip}` | `driver.trips.confirmPayment` | `Driver\TripController@confirmPayment` |
| PUT | `/driver/trips/{trip}/complete` | `driver.trips.complete` | `Driver\TripController@complete` |

Aturan bisnis:

- Driver hanya dapat mengakses trip miliknya.
- Pickup menandai penumpang sudah dijemput.
- Dropoff menandai penumpang sudah diantar.
- Pelunasan dicatat saat driver menerima sisa pembayaran.
- Trip hanya selesai jika semua penumpang sudah selesai diantar.

## 18. Laporan Admin

Tujuan: admin melihat performa operasional dan pendapatan.

Route terkait:

| Method | URL | Name | Controller |
| --- | --- | --- | --- |
| GET | `/admin/laporan` | `admin.laporan.index` | `Admin\LaporanController@index` |
| GET | `/admin/laporan/export` | `admin.laporan.export` | `Admin\LaporanController@export` |

Isi laporan:

- Total booking.
- Pendapatan bersih.
- Total trip.
- Okupansi rata-rata.
- Grafik pendapatan harian dengan Chart.js.
- Rincian laporan harian.

## 19. Notifikasi WhatsApp

Tujuan: mengirim informasi penting kepada pelanggan, driver, dan admin melalui WhatsApp.

File utama:

- `app/Services/FonnteService.php`
- `app/Services/BookingWhatsappNotificationService.php`
- `app/Models/WhatsappNotification.php`

Event yang didukung:

- DP diverifikasi admin.
- Booking ditugaskan ke trip.
- Booking dibatalkan.

Konfigurasi `.env`:

```env
FONNTE_TOKEN=
FONNTE_URL=https://api.fonnte.com/send
FONNTE_COUNTRY_CODE=62
FONNTE_CONNECT_ONLY=false
ADMIN_WA_NUMBER=
```

## Status Booking

| Status | Arti | Trigger |
| --- | --- | --- |
| `booking_dibuat` | Booking dibuat, menunggu upload DP | Pelanggan submit booking |
| `menunggu_verifikasi` | Bukti DP sudah diupload | Pelanggan upload bukti pembayaran |
| `dikonfirmasi` | DP valid | Admin verifikasi pembayaran |
| `assigned_to_trip` | Booking masuk trip | Admin assign booking ke trip |
| `on_trip` | Penumpang/trip sedang berjalan | Driver mulai trip |
| `completed` | Perjalanan selesai | Driver menyelesaikan trip |
| `cancelled` | Booking dibatalkan | Pelanggan/admin cancel |
| `expired` | Booking kedaluwarsa | Proses sistem bila diterapkan |

## Status Trip

| Status | Arti | Trigger |
| --- | --- | --- |
| `new` | Trip baru dibuat | Admin membuat trip |
| `ready` | Trip siap berjalan | Admin/operasional menyiapkan trip |
| `on_trip` | Trip sedang berjalan | Driver start trip |
| `completed` | Trip selesai | Driver complete trip |
| `cancelled` | Trip dibatalkan | Admin membatalkan trip |

## Relasi Modul Utama

```text
Rute -> Jadwal -> Booking -> DetailTrip -> Trip -> Driver -> Armada
User -> Pelanggan -> Booking
User -> Driver -> Trip
Booking -> Pembayaran
Booking -> WhatsappNotification
```
