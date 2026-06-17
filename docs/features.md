# Singgalang Jaya Travel — Daftar Fitur & Alur Sistem

Sistem ini memiliki 3 aktor utama: **Pelanggan**, **Admin**, dan **Driver**. Berikut adalah daftar fitur lengkap beserta alur bisnis dan rute/controller terkait sesuai spesifikasi proyek.

---

## 👥 Aktor & Hak Akses

### 1. Pelanggan (Customer)
* Melakukan pendaftaran (Register) & masuk (Login).
* Melihat jadwal keberangkatan secara publik.
* Melakukan pemesanan (Booking) travel (wajib login).
* Mengunggah bukti pembayaran DP (Uang Muka) flat Rp50.000.
* Melihat riwayat dan melacak status booking melalui halaman "Booking Saya".
* Melihat informasi Driver & Armada yang ditugaskan setelah booking masuk ke trip.

### 2. Admin / Koordinator
* Mengelola master data: Rute, Armada, Driver, dan Jadwal Keberangkatan.
* Memantau dan mengelola pesanan (Booking) pelanggan.
* Memverifikasi atau menolak bukti pembayaran DP.
* Mengelola Trip operasional (membuat trip, menugaskan driver & armada, mengelompokkan booking pelanggan ke dalam trip).
* Melihat laporan booking, trip, dan pendapatan travel.

### 3. Driver (Sopir)
* Melihat daftar Trip hari ini dan riwayat Trip yang ditugaskan.
* Melihat manifest penumpang (alamat penjemputan, titik peta Leaflet, sisa pembayaran).
* Melakukan konfirmasi penjemputan penumpang di lapangan.
* Mengubah status perjalanan/trip (`Mulai` → `Menjemput` → `Berangkat` → `Tiba` → `Selesai`).
* Mengonfirmasi pelunasan sisa pembayaran dari pelanggan secara langsung.

---

## 🚐 Detail Fitur & Alur Bisnis

### 1. Login & Register
* **Tujuan**: Memungkinkan pengguna masuk ke sistem sesuai hak akses masing-masing.
* **Aktor**: Pelanggan, Admin, Driver.
* **Alur**: Pengguna membuka halaman login/register → input data kredensial → sistem memvalidasi dan memeriksa role → mengarahkan ke halaman tujuan sesuai role:
  * Pelanggan → `/` (Landing Page dengan session aktif)
  * Admin → `/admin/dashboard`
  * Driver → `/driver/dashboard`
* **Route/Controller**:
  * GET `/login` & POST `/login` (Breeze `AuthenticatedSessionController`)
  * GET `/register` & POST `/register` (Breeze `RegisteredUserController`)
  * POST `/logout` (Breeze `AuthenticatedSessionController`)

---

### 2. Lihat Jadwal Keberangkatan (Public)
* **Tujuan**: Memungkinkan pelanggan (guest maupun yang sudah login) melihat ketersediaan jadwal travel.
* **Aktor**: Pelanggan (Umum).
* **Alur**: Pelanggan membuka `/jadwal` → melakukan filter asal, tujuan, dan tanggal keberangkatan → sistem menampilkan daftar jadwal beserta rute, jam, shift (Pagi/Malam), sisa kuota kursi, dan harga dasar.
* **Route/Controller**:
  * GET `/jadwal` (`JadwalPublicController@index`)
  * GET `/jadwal/available` (AJAX - `JadwalPublicController@available`)
  * GET `/jadwal/{id}/check-kuota` (AJAX - `JadwalPublicController@checkKuota`)

---

### 3. Booking Travel (Pemesanan)
* **Tujuan**: Pelanggan memesan kursi perjalanan travel pada jadwal tertentu.
* **Aktor**: Pelanggan (Wajib Login).
* **Alur**: Pelanggan memilih jadwal → diarahkan ke form booking `/booking/create` → menentukan jumlah penumpang, memilih nomor kursi, dan menandai lokasi jemput/antar via Leaflet Map → Livewire menghitung total tarif secara otomatis → submit → data disimpan dengan status awal `booking_dibuat`.
* **Route/Controller**:
  * GET `/booking/create` (`BookingController@create`)
  * POST `/booking` (`BookingController@store`)
  * GET `/booking/{kode}/review` (`BookingController@review`)
  * Livewire Component: `BookingForm`

---

### 4. Upload Bukti Pembayaran DP (Customer)
* **Tujuan**: Pelanggan melakukan konfirmasi pemesanan dengan membayar DP tetap sebesar Rp50.000.
* **Aktor**: Pelanggan.
* **Alur**: Pelanggan membuka halaman `/booking/{kode}/pembayaran` → melihat instruksi rekening transfer → mengunggah gambar bukti transfer DP → submit → sistem mengubah status booking menjadi `menunggu_verifikasi`.
* **Route/Controller**:
  * GET `/booking/{kode}/pembayaran` (`PembayaranController@show`)
  * POST `/booking/{kode}/pembayaran` (`PembayaranController@store`)

---

### 5. Booking Saya (Halaman Pelanggan)
* **Tujuan**: Pelanggan melihat riwayat perjalanan dan status booking aktif secara mendalam.
* **Aktor**: Pelanggan.
* **Alur**: Pelanggan membuka `/booking-saya` → sistem menampilkan daftar pesanan → pelanggan klik booking tertentu → sistem menampilkan detail booking, timeline status (booking dibuat → menunggu verifikasi → dikonfirmasi → assigned to trip → on trip → completed), info jadwal, detail rute, serta informasi Driver, Armada, dan tombol kontak WhatsApp driver setelah booking dimasukkan ke Trip (`assigned_to_trip`).
* **Route/Controller**:
  * GET `/booking-saya` (`BookingController@index`)
  * GET `/booking/{kode}` (`BookingController@show`)
  * GET `/booking/{kode}/edit` & PUT `/booking/{kode}` (Edit lokasi jemput)
  * PUT `/booking/{kode}/cancel` (Membatalkan booking, DP hangus jika sudah bayar)

---

### 6. Cek Status Booking (Public)
* **Tujuan**: Melacak pesanan secara cepat menggunakan kode booking tanpa harus login.
* **Aktor**: Pelanggan (Umum).
* **Alur**: Pengguna membuka halaman `/cek-booking` → menginput kode booking (format: `SJT-{YYYYMMDD}-{RANDOM5}`) → sistem menampilkan ringkasan informasi perjalanan, status booking, dan status pembayaran.
* **Route/Controller**:
  * GET `/cek-booking` (`CekBookingController@index`)
  * POST `/cek-booking` (`CekBookingController@show`)

---

### 7. Dashboard Admin
* **Tujuan**: Menampilkan statistik ringkas kinerja operasional travel bagi admin.
* **Aktor**: Admin.
* **Alur**: Admin masuk ke `/admin/dashboard` → sistem mengambil data agregat dari database → menampilkan widget Total Booking, Total Trip, Total Pendapatan, Total Driver, Total Armada, serta daftar booking terbaru.
* **Route/Controller**:
  * GET `/admin/dashboard` (`Admin\DashboardController@index`)

---

### 8. CRUD Rute (Admin)
* **Tujuan**: Mengelola data rute perjalanan utama (asal, tujuan, harga dasar).
* **Aktor**: Admin.
* **Alur**: Admin mengelola master rute melalui menu Rute (tambah, edit, hapus). Data rute ini menjadi acuan pembuatan Jadwal dan harga tiket.
* **Route/Controller**:
  * Resource Route `/admin/rute` (`Admin\RuteController`)

---

### 9. CRUD Armada (Admin)
* **Tujuan**: Mengelola armada/kendaraan travel secara independen dari driver.
* **Aktor**: Admin.
* **Alur**: Admin mengelola data kendaraan (plat nomor, jenis, kapasitas kursi) melalui modal dialog di halaman armada.
* **Route/Controller**:
  * Resource Route `/admin/armada` (`Admin\ArmadaController`)

---

### 10. CRUD Driver (Admin)
* **Tujuan**: Mengelola data driver dan menautkannya dengan akun login serta armada kendaraan.
* **Aktor**: Admin.
* **Alur**: Admin mendaftarkan driver baru (sekaligus membuat akun user login ber-role `driver`) dan menugaskan armada kendaraan tertentu ke driver tersebut.
* **Route/Controller**:
  * Resource Route `/admin/drivers` (`Admin\DriverController`)

---

### 11. CRUD Jadwal (Admin)
* **Tujuan**: Mengelola jadwal operasional harian berdasarkan rute yang ada.
* **Aktor**: Admin.
* **Alur**: Admin menentukan jadwal (rute, tanggal, jam, shift pagi/malam). Admin juga dapat menonaktifkan jadwal secara dinamis melalui tombol toggle status.
* **Route/Controller**:
  * Resource Route `/admin/jadwal` (`Admin\JadwalController`)
  * PUT `/admin/jadwal/{id}/toggle` (`Admin\JadwalController@toggleStatus`)

---

### 12. Kelola Booking & Pembatalan (Admin)
* **Tujuan**: Memantau daftar seluruh pemesanan pelanggan dan membatalkan pesanan jika diperlukan.
* **Aktor**: Admin.
* **Alur**: Admin membuka `/admin/bookings` → sistem menampilkan daftar pesanan (dengan fitur pencarian & filter Livewire) → admin dapat melihat detail pemesanan atau membatalkan pesanan (DP hangus).
* **Route/Controller**:
  * Resource Route `/admin/bookings` (`Admin\BookingController`)
  * PUT `/admin/bookings/{id}/cancel` (`Admin\BookingController@cancel`)
  * Livewire Component: `Admin\BookingTable`

---

### 13. Verifikasi Pembayaran DP (Admin)
* **Tujuan**: Memvalidasi bukti pembayaran DP yang diunggah pelanggan.
* **Aktor**: Admin.
* **Alur**: Admin membuka `/admin/pembayaran` → melihat daftar pembayaran tertunda → klik detail untuk melihat gambar bukti transfer → admin memilih verifikasi (status booking menjadi `dikonfirmasi`) atau tolak (status booking kembali ke `booking_dibuat`).
* **Route/Controller**:
  * Resource Route `/admin/pembayaran` (`Admin\PembayaranController`)
  * PUT `/admin/pembayaran/{id}/verify` (`Admin\PembayaranController@verify`)
  * PUT `/admin/pembayaran/{id}/reject` (`Admin\PembayaranController@reject`)
  * Livewire Component: `Admin\PembayaranTable`

---

### 14. Manajemen Trip & Alokasi Penumpang (Admin)
* **Tujuan**: Mengelompokkan booking penumpang ke armada perjalanan tertentu.
* **Aktor**: Admin.
* **Alur**: Admin membuat Trip dari jadwal keberangkatan tertentu → menentukan Driver & Armada pengangkut → masuk ke detail Trip `/admin/trips/{id}` → admin mengalokasikan (assign) pesanan pelanggan dari antrean jadwal ke Trip tersebut (sistem otomatis memvalidasi sisa kuota kursi armada) → status booking berubah menjadi `assigned_to_trip`. Admin juga dapat menghapus (remove) booking dari Trip.
* **Route/Controller**:
  * Resource Route `/admin/trips` (`Admin\TripController`)
  * POST `/admin/trips/{trip}/assign` (`Admin\TripController@assignBooking`)
  * DELETE `/admin/trips/{trip}/remove/{detailTrip}` (`Admin\TripController@removeBooking`)

---

### 15. Dashboard Driver & Manifest
* **Tujuan**: Memberikan informasi daftar tugas perjalanan kepada sopir.
* **Aktor**: Driver.
* **Alur**: Driver masuk ke `/driver/dashboard` → melihat daftar Trip yang ditugaskan hari ini dan total penumpang → klik Trip → sistem menampilkan manifest lengkap (nama penumpang, nomor kursi, alamat jemput, koordinat lokasi penjemputan di Leaflet Map, dan sisa pembayaran pelunasan).
* **Route/Controller**:
  * GET `/driver/dashboard` (dashboard utama driver)
  * GET `/driver/trips/{id}` (`Driver\TripController@show`)
  * Livewire Component: `Driver\TripManifest`

---

### 16. Operasional Trip & Konfirmasi Jemput (Driver)
* **Tujuan**: Driver memperbarui status keberangkatan dan status jemput penumpang secara realtime.
* **Aktor**: Driver.
* **Alur**: Driver membuka detail Trip → menekan tombol transisi status perjalanan secara bertahap (`Mulai` → `Menjemput` → `Berangkat` → `Tiba` → `Selesai`) → Driver melakukan checklist untuk setiap penumpang ketika sudah dijemput (status berubah menjadi `on_trip`/`Sudah Dijemput`).
* **Route/Controller**:
  * PUT `/driver/trips/{id}/start` (`Driver\TripController@start`)
  * PUT `/driver/trips/{id}/pickup/{detailId}` (`Driver\TripController@pickup`)
  * PUT `/driver/trips/{id}/dropoff/{detailId}` (`Driver\TripController@dropoff`)
  * PUT `/driver/trips/{id}/complete` (`Driver\TripController@complete`)

---

### 17. Konfirmasi Pelunasan Tiket (Driver)
* **Tujuan**: Mencatat pembayaran sisa tarif yang diserahkan pelanggan langsung di lapangan.
* **Aktor**: Driver.
* **Alur**: Pelanggan membayar sisa tarif (Total Tarif dikurangi DP Rp50.000) secara tunai/transfer langsung ke Driver → Driver menekan tombol konfirmasi pelunasan pada manifest penumpang → sistem mencatat pelunasan tiket tersebut.
* **Route/Controller**:
  * PUT `/driver/trips/{id}/confirm-payment/{detailId}` (`Driver\TripController@confirmPayment`)

---

### 18. Laporan & Export (Admin)
* **Tujuan**: Menyediakan data rekapitulasi performa bisnis travel.
* **Aktor**: Admin.
* **Alur**: Admin membuka `/admin/laporan` → menentukan filter tanggal atau rute → melihat data transaksi pendapatan, trip, dan booking terproses → klik Export untuk mengunduh laporan berformat PDF/Excel.
* **Route/Controller**:
  * GET `/admin/laporan` (`Admin\LaporanController@index`)
  * GET `/admin/laporan/export` (`Admin\LaporanController@export`)

---

### 19. WhatsApp Notification (Fonnte API)
* **Tujuan**: Memberikan notifikasi otomatis via WhatsApp kepada pengguna.
* **Aktor**: Sistem (Otomatis).
* **Alur**:
  * **Notifikasi Pembatalan**: Ketika pelanggan membatalkan booking, sistem otomatis mengirimkan notifikasi WhatsApp berisi rincian pembatalan ke nomor Admin dan Driver (jika sudah ditugaskan ke trip).
  * **Pengingat Keberangkatan**: Scheduler sistem berjalan secara otomatis setiap pagi pukul 06:00 untuk mengirimkan konfirmasi ulang keberangkatan ke nomor WhatsApp pelanggan yang memiliki jadwal keberangkatan pada hari tersebut.
* **Kelas Pendukung**:
  * `App\Services\FonnteService` (Integrasi Fonnte API & Auto logging ke tabel `whatsapp_notifications`)
  * Scheduler Command: `booking:send-confirmation` (Run `dailyAt('06:00')`)