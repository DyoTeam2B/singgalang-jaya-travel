# Refactoring Documentation

Dokumen ini mencatat refactoring yang sudah dilakukan pada Singgalang Jaya Travel System. Fokus refactoring adalah menjaga kode tetap mudah dirawat saat fitur berkembang dari booking dasar menjadi sistem operasional travel penuh.

## Tujuan Refactoring

- Mengurangi duplikasi logika bisnis di controller.
- Memindahkan validasi ke Form Request.
- Memisahkan service eksternal dari controller.
- Menjaga sinkronisasi status booking dan trip secara otomatis.
- Membuat struktur modul lebih jelas berdasarkan aktor: pelanggan, admin, driver.
- Memudahkan testing regresi pada fitur booking, pembayaran, trip, dan driver.

## Ringkasan Refactoring

| Refactoring | Sebelum | Sesudah | Dampak |
| --- | --- | --- | --- |
| Extract booking logic | Generate kode dan hitung tarif berpotensi berada di controller | `BookingService` menangani kode booking, transaksi booking, dan kalkulasi total | Controller lebih ringkas dan logika booking mudah dites |
| Extract WhatsApp notification | Format pesan dan pengiriman bisa tersebar di controller | `FonnteService` dan `BookingWhatsappNotificationService` memusatkan integrasi | Format pesan konsisten dan pengiriman mudah dimock |
| Form Request validation | Validasi raw di controller | `StoreBookingRequest`, `StorePembayaranRequest`, `AssignBookingRequest`, dan request admin lainnya | Controller fokus pada alur bisnis |
| Observer status sync | Status booking diupdate manual pada beberapa controller | `TripObserver` dan `DetailTripObserver` menyinkronkan status otomatis | Mengurangi risiko status tidak konsisten |
| Separate Armada entity | Data kendaraan melekat pada driver/trip | Tabel/model `Armada`, relasi ke `Driver` dan `Trip` | Data armada lebih fleksibel dan tidak duplikatif |
| Role-based layouts | Tampilan admin/driver/publik berpotensi bercampur | Layout dan component dipisah: public, admin, driver | UI lebih konsisten per aktor |
| Trip conflict helper | Logika konflik driver/armada raw di query berulang | Method `whereConflictingSchedule()` pada `Admin\TripController` | Query konflik lebih terpusat dan mudah dibaca |

## 1. Extract `BookingService`

File terkait:

- `app/Services/BookingService.php`
- `app/Http/Controllers/BookingController.php`
- `app/Livewire/BookingForm.php`

Perubahan:

- Generate kode booking dipusatkan di `generateBookingCode()`.
- Pembuatan booking dilakukan dalam transaksi database.
- Perhitungan total harga dipusatkan di `calculateTotal()`.

Manfaat:

- Controller tidak memuat detail teknis generate kode dan kalkulasi tarif.
- Format kode booking konsisten: `SJT-{YYYYMMDD}-{RANDOM5}`.
- Risiko data booking setengah tersimpan berkurang karena memakai `DB::transaction()`.

## 2. Extract Fonnte dan Booking WhatsApp Notification Service

File terkait:

- `app/Services/FonnteService.php`
- `app/Services/BookingWhatsappNotificationService.php`
- `app/Models/WhatsappNotification.php`
- `config/services.php`

Perubahan:

- Pengiriman HTTP ke Fonnte dipusatkan di `FonnteService`.
- Format pesan booking dipusatkan di `BookingWhatsappNotificationService`.
- Nomor WhatsApp dinormalisasi sebelum dikirim.
- Respons Fonnte dicatat ke tabel `whatsapp_notifications`.

Manfaat:

- Controller tidak perlu tahu format payload Fonnte.
- Pesan DP verified dan trip assigned konsisten.
- Test dapat memakai `Http::fake()` tanpa request eksternal sungguhan.

## 3. Form Request Validation

File contoh:

- `app/Http/Requests/StoreBookingRequest.php`
- `app/Http/Requests/StorePembayaranRequest.php`
- `app/Http/Requests/CekBookingRequest.php`
- `app/Http/Requests/Admin/AssignBookingRequest.php`
- `app/Http/Requests/Admin/StoreJadwalRequest.php`
- `app/Http/Requests/Admin/UpdateJadwalRequest.php`
- `app/Http/Requests/Admin/StoreArmadaRequest.php`
- `app/Http/Requests/Admin/UpdateArmadaRequest.php`

Perubahan:

- Aturan validasi dipindah dari controller ke class request.
- Pesan validasi menjadi konsisten dan lebih mudah dicari.
- Controller menerima data yang sudah tervalidasi melalui `$request->validated()`.

Manfaat:

- Method controller lebih pendek.
- Validasi dapat diuji dan direview per resource.
- Perubahan aturan input tidak mengganggu alur controller.

## 4. Observer untuk Sinkronisasi Status

File terkait:

- `app/Observers/TripObserver.php`
- `app/Observers/DetailTripObserver.php`
- `app/Providers/AppServiceProvider.php`

Perubahan:

- Saat `DetailTrip` dibuat, booking otomatis menjadi `assigned_to_trip`.
- Saat `DetailTrip` dihapus, booking kembali menjadi `dikonfirmasi`.
- Saat status trip berubah menjadi `on_trip`, booking terkait ikut menjadi `on_trip`.
- Saat trip selesai, booking terkait menjadi `completed`.
- Saat trip dihapus/dibatalkan, booking dikembalikan ke status aman sesuai alur bisnis.

Manfaat:

- Status booking tidak perlu diupdate manual di semua controller.
- Mengurangi bug ketika admin remove booking atau driver menyelesaikan trip.
- Alur status lebih mudah diaudit.

## 5. Pemisahan Armada dari Driver

File terkait:

- `app/Models/Armada.php`
- `app/Models/Driver.php`
- `app/Models/Trip.php`
- `database/migrations/2026_06_07_000000_create_armada_table.php`
- `database/migrations/2026_06_16_174252_add_armada_id_to_trips_table.php`
- `app/Http/Controllers/Admin/ArmadaController.php`

Perubahan:

- Data kendaraan dipisah ke tabel `armada`.
- Driver memiliki `armada_id`.
- Trip memiliki `armada_id` agar manifest operasional tetap historis.
- Saat trip dibuat, armada mengikuti armada milik driver.

Manfaat:

- Data kendaraan tidak diduplikasi pada driver.
- Armada dapat dikelola terpisah dari driver.
- Kapasitas kursi trip dapat dihitung dari armada.

## 6. Layout dan Component Reuse

File terkait:

- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/layouts/driver.blade.php`
- `resources/views/components/sidebar-admin.blade.php`
- `resources/views/components/sidebar-driver.blade.php`
- `resources/views/components/status-badge.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/components/card.blade.php`

Perubahan:

- Layout dipisahkan berdasarkan aktor.
- Sidebar dan badge status dipakai ulang.
- Flash message dipusatkan lewat component alert.

Manfaat:

- Tampilan lebih konsisten.
- Perubahan navigasi tidak perlu dilakukan di banyak view.
- Status booking/trip/driver punya representasi visual yang seragam.

## 7. Helper Query Konflik Trip

File terkait:

- `app/Http/Controllers/Admin/TripController.php`

Perubahan:

- Query konflik driver/armada pada tanggal dan shift yang sama dipusatkan di `whereConflictingSchedule()`.

Manfaat:

- Mengurangi query duplikatif.
- Validasi konflik driver dan armada memakai aturan yang sama.
- Lebih mudah diubah jika aturan konflik berubah.

## Test Pendukung

| Area | File test |
| --- | --- |
| Booking timeline/status | `tests/Feature/BookingTimelineTest.php` |
| Pembatalan dan notifikasi booking | `tests/Feature/BookingCancellationNotificationTest.php` |
| Fonnte service | `tests/Feature/FonnteServiceTest.php` |
| Admin trip assign | `tests/Feature/Admin/TripAssignTest.php` |
| Admin pembayaran notification | `tests/Feature/Admin/PembayaranNotificationTest.php` |
| Admin armada | `tests/Feature/Admin/ArmadaTest.php` |
| Admin driver | `tests/Feature/Admin/DriverTest.php` |
| Driver trip operations | `tests/Feature/DriverTripTest.php` |
| Profile role sync | `tests/Feature/ProfileTest.php` |

## Rekomendasi Refactoring Lanjutan

| Area | Rekomendasi |
| --- | --- |
| Laporan export | Pisahkan generator PDF/Excel ke service khusus, misalnya `ReportExportService` |
| Driver operation | Pertimbangkan `TripOperationService` jika method driver makin panjang |
| Notification | Tambahkan event/listener untuk semua notifikasi agar controller makin bersih |
| Status machine | Jika status makin kompleks, buat enum/status transition service |
| UI map | Jadikan map viewer reusable agar tidak ada konfigurasi Leaflet berulang di view |
