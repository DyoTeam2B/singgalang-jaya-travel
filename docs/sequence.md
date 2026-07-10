# Sequence Diagram — Singgalang Jaya Travel System

Dokumen ini berisi semua alur (sequence) utama pada sistem. Setiap diagram menggunakan format **Mermaid** sehingga dapat langsung di-render di tools seperti [Mermaid Live Editor](https://mermaid.live), draw.io, PlantUML converter, atau markdown viewer yang mendukung Mermaid.

---

## Daftar Aktor

| Aktor | Deskripsi |
|---|---|
| **Pelanggan** | Pengguna yang melakukan booking travel |
| **Admin** | Pengelola sistem (verifikasi, trip, master data) |
| **Driver** | Supir yang menjalankan trip dan manifest penumpang |
| **Sistem** | Proses otomatis (scheduler, observer) |
| **FonnteAPI** | Layanan WhatsApp API eksternal |

---

## Daftar Model & Relasi

| Model | Tabel | Relasi Utama |
|---|---|---|
| `User` | `users` | hasOne → Driver, hasOne → Pelanggan |
| `Pelanggan` | `pelanggan` | belongsTo → User, hasMany → Booking |
| `Booking` | `bookings` | belongsTo → Pelanggan, belongsTo → Jadwal, hasMany → Pembayaran, hasMany → DetailTrip, hasOne → Rating, hasMany → WhatsappNotification |
| `Jadwal` | `jadwal` | belongsTo → Rute, hasMany → Booking, hasMany → Trip |
| `Rute` | `rute` | hasMany → Jadwal |
| `Pembayaran` | `pembayaran` | belongsTo → Booking |
| `Trip` | `trips` | belongsTo → Jadwal, belongsTo → Driver, belongsTo → Armada, hasMany → DetailTrip |
| `DetailTrip` | `detail_trip` | belongsTo → Trip, belongsTo → Booking |
| `Driver` | `drivers` | belongsTo → User, belongsTo → Armada, hasMany → Trip |
| `Armada` | `armada` | hasOne → Driver, hasMany → Trip |
| `Rating` | `ratings` | belongsTo → Booking, belongsTo → Pelanggan |
| `WhatsappNotification` | `whatsapp_notifications` | belongsTo → Booking |

---

## Status Booking (State Flow)

```
booking_dibuat → menunggu_verifikasi → dikonfirmasi → assigned_to_trip → on_trip → completed
      ↓                                      ↓              ↓
   expired                              cancelled        cancelled
```

| Status | Keterangan | Dipicu oleh |
|---|---|---|
| `booking_dibuat` | Booking baru dibuat, menunggu upload bukti DP | `BookingService::createBooking()` |
| `menunggu_verifikasi` | Bukti DP sudah diupload, menunggu admin | `PembayaranController::store()` |
| `dikonfirmasi` | DP diverifikasi admin | `PembayaranTable::verifyPayment()` / `PembayaranController::verify()` |
| `assigned_to_trip` | Booking masuk manifest trip | `DetailTripObserver::created()` |
| `on_trip` | Trip sedang berjalan | `TripObserver::updated()` |
| `completed` | Trip selesai | `TripObserver::updated()` |
| `cancelled` | Dibatalkan pelanggan/admin | `BookingController::cancel()` |
| `expired` | Batas waktu DP habis (30 menit) | `booking:expire` command |

---

## 1. Pelanggan — Membuat Booking Baru

**Controller:** `BookingController::create()`, `BookingController::store()`
**Service:** `BookingService::createBooking()`
**Livewire:** `BookingForm`
**View:** `public.booking.create`, `public.booking.review`

```mermaid
sequenceDiagram
    actor P as Pelanggan
    participant V as View<br/>(booking/create)
    participant LW as Livewire<br/>(BookingForm)
    participant BC as BookingController
    participant BS as BookingService
    participant JM as Jadwal Model
    participant PM as Pelanggan Model
    participant BM as Booking Model
    participant DB as Database

    P->>V: Buka halaman booking
    V->>LW: Render form booking (Livewire)
    LW->>JM: Jadwal::aktif() — ambil jadwal tersedia
    JM->>DB: SELECT jadwal WHERE status_jadwal='aktif'
    DB-->>JM: Daftar jadwal
    JM-->>LW: Jadwal dengan kuota tersedia
    LW-->>V: Tampilkan form dengan pilihan jadwal

    P->>V: Isi form (jadwal, alamat, jumlah penumpang)
    V->>BC: POST /booking (StoreBookingRequest)
    BC->>BS: createBooking(validated, user)

    BS->>PM: Pelanggan::updateOrCreate(user_id)
    PM->>DB: INSERT/UPDATE pelanggan
    DB-->>PM: Pelanggan record

    BS->>BM: Cek duplikat booking aktif
    BM->>DB: SELECT bookings WHERE pelanggan_id & jadwal_id & status aktif
    DB-->>BM: Hasil cek

    alt Duplikat ditemukan
        BS-->>BC: throw Exception
        BC-->>V: Redirect dengan error
    end

    BS->>JM: Jadwal::with('rute')->findOrFail(jadwal_id)
    JM->>DB: SELECT jadwal + rute
    DB-->>JM: Jadwal + tarif

    BS->>BM: Booking::create(data + kode_booking + expired_at=now+30menit)
    BM->>DB: INSERT bookings (status: booking_dibuat)
    DB-->>BM: Booking record

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    BS-->>BC: Return booking
    BC-->>V: Redirect ke halaman review
    V-->>P: Tampilkan halaman review booking
```

---

## 2. Pelanggan — Upload Bukti Pembayaran DP

**Controller:** `PembayaranController::show()`, `PembayaranController::store()`
**View:** `public.pembayaran.show`

```mermaid
sequenceDiagram
    actor P as Pelanggan
    participant V as View<br/>(pembayaran/show)
    participant PC as PembayaranController
    participant BS as BookingService
    participant BM as Booking Model
    participant PM as Pembayaran Model
    participant FS as FileStorage
    participant DB as Database

    P->>V: Buka halaman pembayaran DP
    V->>PC: GET /booking/{kode}/pembayaran
    PC->>BM: Booking::where('kode_booking', kode)
    BM->>DB: SELECT booking + pelanggan + jadwal
    DB-->>BM: Booking record

    PC->>PC: Cek expired_at sudah lewat?
    alt Booking sudah expired
        PC->>BS: expireBooking(booking)
        BS->>DB: DELETE pembayaran + DELETE booking
        PC-->>V: Redirect ke booking.index + error
    end

    PC-->>V: Tampilkan form upload bukti DP

    P->>V: Upload file bukti + pilih metode pembayaran
    V->>PC: POST /booking/{kode}/pembayaran (StorePembayaranRequest)

    PC->>PC: Cek status booking = booking_dibuat?
    alt Status bukan booking_dibuat
        PC-->>V: Redirect dengan info "sudah diproses"
    end

    PC->>FS: storeAs('bukti-pembayaran', fileName, 'public')
    FS-->>PC: filePath

    PC->>PM: Pembayaran::create(jenis=dp, jumlah=50000, status=menunggu)
    PM->>DB: INSERT pembayaran
    DB-->>PM: Pembayaran record

    PC->>BM: booking->update(status: menunggu_verifikasi)
    BM->>DB: UPDATE bookings SET status_booking='menunggu_verifikasi'
    DB-->>BM: OK

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    PC-->>V: Redirect ke booking.show + success
    V-->>P: Tampilkan "Menunggu verifikasi admin"
```

---

## 3. Admin — Verifikasi Pembayaran DP

**Controller:** `Admin\PembayaranController::verify()` atau **Livewire:** `PembayaranTable::verifyPayment()`
**Service:** `BookingWhatsappNotificationService::sendDpVerifiedToCustomer()`
**View:** `admin.pembayaran.index` (Livewire), `livewire.admin.pembayaran-table`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/pembayaran)
    participant LW as Livewire<br/>(PembayaranTable)
    participant PM as Pembayaran Model
    participant BM as Booking Model
    participant WS as BookingWhatsapp<br/>NotificationService
    participant FS as FonnteService
    participant FA as FonnteAPI
    participant WN as WhatsappNotification Model
    participant DB as Database

    A->>V: Buka halaman verifikasi pembayaran
    V->>LW: Render daftar pembayaran
    LW->>PM: Pembayaran::with('booking.pelanggan')
    PM->>DB: SELECT pembayaran + booking + pelanggan
    DB-->>PM: Daftar pembayaran
    LW-->>V: Tampilkan daftar + detail split-screen

    A->>LW: Klik "Verifikasi"
    LW->>LW: verifyPayment()

    LW->>PM: pembayaran->update(status: terverifikasi)
    PM->>DB: UPDATE pembayaran SET status_pembayaran='terverifikasi'
    LW->>BM: booking->update(status: dikonfirmasi)
    BM->>DB: UPDATE bookings SET status_booking='dikonfirmasi'

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    LW->>WS: sendDpVerifiedToCustomer(booking)
    WS->>WS: buildDpVerifiedMessage(booking)
    WS->>FS: send(no_hp, message, TYPE_CUSTOM, booking_id)
    FS->>WN: WhatsappNotification::create(status: pending)
    WN->>DB: INSERT whatsapp_notifications

    alt Token kosong (mock mode)
        FS->>WN: update(status: sent)
    else Token ada
        FS->>FA: POST https://api.fonnte.com/send
        FA-->>FS: Response (status, process)
        FS->>WN: update(status berdasarkan response)
    end

    WS-->>LW: true/false
    LW-->>V: Flash success message
    V-->>A: Tampilkan notifikasi "Pembayaran diverifikasi"
```

---

## 4. Admin — Tolak Pembayaran DP

**Controller:** `Admin\PembayaranController::reject()` atau **Livewire:** `PembayaranTable::rejectPayment()`

```mermaid
sequenceDiagram
    actor A as Admin
    participant LW as Livewire<br/>(PembayaranTable)
    participant PM as Pembayaran Model
    participant BM as Booking Model
    participant DB as Database

    A->>LW: Klik "Tolak" + isi alasan
    LW->>LW: rejectPayment()
    LW->>LW: validate(rejectReason required)

    LW->>PM: pembayaran->update(status: ditolak, catatan: alasan)
    PM->>DB: UPDATE pembayaran
    LW->>BM: booking->update(status: booking_dibuat)
    BM->>DB: UPDATE bookings SET status_booking='booking_dibuat'

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    LW-->>A: Flash error "Bukti pembayaran ditolak"
```

---

## 5. Admin — Buat Trip Baru

**Controller:** `Admin\TripController::create()`, `Admin\TripController::store()`
**View:** `admin.trips.create`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/trips/create)
    participant TC as Admin<br/>TripController
    participant JM as Jadwal Model
    participant DM as Driver Model
    participant TM as Trip Model
    participant DB as Database

    A->>TC: GET /admin/trips/create
    TC->>JM: Jadwal::aktif()->with('rute')
    JM->>DB: SELECT jadwal aktif + rute
    DB-->>JM: Daftar jadwal
    TC->>DM: Driver::with('armada')->where(aktif)->whereHas(armada aktif)
    DM->>DB: SELECT driver aktif + armada aktif
    DB-->>DM: Daftar driver
    TC-->>V: Tampilkan form (jadwal + driver)

    A->>V: Pilih jadwal + driver → Submit
    V->>TC: POST /admin/trips (StoreTripRequest)

    TC->>DM: Driver::with('armada')->findOrFail(driver_id)
    DM->>DB: SELECT driver + armada
    DB-->>DM: Driver record

    TC->>TM: Trip::create(jadwal_id, driver_id, armada_id, status: new)
    TM->>DB: INSERT trips
    DB-->>TM: Trip record

    TC-->>V: Redirect ke trips.index + success
    V-->>A: "Trip baru berhasil dibuat"
```

---

## 6. Admin — Assign Booking ke Trip

**Controller:** `Admin\TripController::assignBooking()`
**Observer:** `DetailTripObserver::created()`
**Service:** `BookingWhatsappNotificationService`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/trips/show)
    participant TC as Admin<br/>TripController
    participant BM as Booking Model
    participant TM as Trip Model
    participant DT as DetailTrip Model
    participant DTO as DetailTrip<br/>Observer
    participant WS as BookingWhatsapp<br/>NotificationService
    participant FS as FonnteService
    participant WN as WhatsappNotification
    participant DB as Database

    A->>V: Pilih booking → Klik "Assign ke Trip"
    V->>TC: POST /admin/trips/{trip}/assign (booking_id)

    TC->>BM: Booking::findOrFail(booking_id)
    BM->>DB: SELECT booking
    DB-->>BM: Booking record

    TC->>TC: Validasi: jadwal_id cocok? status dikonfirmasi? kapasitas cukup?

    alt Validasi gagal
        TC-->>V: Redirect back + error
    end

    TC->>DT: trip->detailTrips()->create(booking_id, status_jemput=belum, status_antar=belum)
    DT->>DB: INSERT detail_trip
    DB-->>DT: DetailTrip record

    Note over DT,DTO: DetailTripObserver::created()
    DTO->>BM: booking->update(status: assigned_to_trip)
    BM->>DB: UPDATE bookings SET status_booking='assigned_to_trip'

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    TC->>WS: sendTripAssignedToCustomer(booking, trip)
    WS->>FS: send(pelanggan.no_hp, customerMessage, TYPE_CUSTOM, booking_id)
    FS->>WN: create + kirim via FonnteAPI
    WN->>DB: INSERT whatsapp_notifications

    TC->>WS: sendTripAssignedToDriver(booking, trip)
    WS->>FS: send(driver.no_hp, driverMessage, TYPE_CUSTOM, booking_id)
    FS->>WN: create + kirim via FonnteAPI
    WN->>DB: INSERT whatsapp_notifications

    TC-->>V: Redirect ke trips.index + success
    V-->>A: "Booking berhasil ditugaskan ke trip"
```

---

## 7. Admin — Approve Trip (new → ready)

**Controller:** `Admin\TripController::update()`
**Observer:** `TripObserver::updated()`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/trips/show)
    participant TC as Admin<br/>TripController
    participant TM as Trip Model
    participant TO as TripObserver
    participant BM as Booking Model
    participant JM as Jadwal Model
    participant DB as Database

    A->>V: Klik "Approve / Setujui Trip"
    V->>TC: PUT /admin/trips/{trip} (status_trip=ready)

    TC->>TC: Validasi minimal 3 penumpang

    alt Penumpang < 3
        TC-->>V: Redirect back + error
    end

    TC->>TM: trip->update(status_trip: ready)
    TM->>DB: UPDATE trips SET status_trip='ready'

    Note over TM,TO: TripObserver::updated() (status_trip berubah)
    TO->>TM: loadMissing('detailTrips.booking')
    TO->>BM: Setiap booking → update(status: assigned_to_trip)
    BM->>DB: UPDATE bookings

    TO->>JM: jadwal->checkAndUpdateStatus()
    JM->>DB: UPDATE jadwal status jika perlu

    TC-->>V: Redirect ke trips.show + success
    V-->>A: "Trip berhasil diperbarui"
```

---

## 8. Driver — Mulai Trip (ready → on_trip)

**Controller:** `Driver\TripController::start()`
**Observer:** `TripObserver::updated()`

```mermaid
sequenceDiagram
    actor D as Driver
    participant V as View<br/>(driver/dashboard)
    participant TC as Driver<br/>TripController
    participant TM as Trip Model
    participant TO as TripObserver
    participant BM as Booking Model
    participant JM as Jadwal Model
    participant DB as Database

    D->>V: Klik "Mulai Trip"
    V->>TC: PUT /driver/trips/{trip}/start

    TC->>TC: Cek: driver = pemilik trip? status = ready?

    alt Validasi gagal
        TC-->>V: Redirect back + error
    end

    TC->>TM: trip->update(status_trip: on_trip, started_at: now())
    TM->>DB: UPDATE trips

    Note over TM,TO: TripObserver::updated() (status_trip: ready → on_trip)
    TO->>BM: Semua booking di trip → update(status: on_trip)
    BM->>DB: UPDATE bookings SET status_booking='on_trip'

    TO->>JM: jadwal->checkAndUpdateStatus()
    JM->>DB: UPDATE jadwal (→ nonaktif karena trip aktif)

    TC-->>V: Redirect back + success
    V-->>D: "Trip berhasil dimulai"
```

---

## 9. Driver — Jemput Penumpang

**Controller:** `Driver\TripController::pickup()`

```mermaid
sequenceDiagram
    actor D as Driver
    participant V as View<br/>(driver/trips/show)
    participant TC as Driver<br/>TripController
    participant DT as DetailTrip Model
    participant DB as Database

    D->>V: Klik "Sudah Dijemput" pada penumpang
    V->>TC: PUT /driver/trips/{trip}/pickup/{detailTrip}

    TC->>TC: Validasi kepemilikan trip + detailTrip

    TC->>DT: detailTrip->update(status_jemput: sudah_dijemput, picked_up_at: now())
    DT->>DB: UPDATE detail_trip
    DB-->>DT: OK

    TC-->>V: Redirect back + success
    V-->>D: "Penumpang berhasil ditandai sudah naik"
```

---

## 10. Driver — Antar Penumpang (Dropoff + Auto Pelunasan)

**Controller:** `Driver\TripController::dropoff()`

```mermaid
sequenceDiagram
    actor D as Driver
    participant V as View<br/>(driver/trips/show)
    participant TC as Driver<br/>TripController
    participant DT as DetailTrip Model
    participant BM as Booking Model
    participant PM as Pembayaran Model
    participant DB as Database

    D->>V: Klik "Sudah Diantar" pada penumpang
    V->>TC: PUT /driver/trips/{trip}/dropoff/{detailTrip}

    TC->>TC: Validasi kepemilikan trip + detailTrip

    Note over TC,DB: DB::transaction() begin

    TC->>DT: detailTrip->update(status_antar: sudah_diantar, dropped_off_at: now())
    DT->>DB: UPDATE detail_trip

    TC->>PM: Cek sudah ada pelunasan terverifikasi?
    PM->>DB: SELECT pembayaran WHERE jenis=pelunasan & status=terverifikasi
    DB-->>PM: Ada / Tidak ada

    alt Belum ada pelunasan
        TC->>PM: Pembayaran::create(jenis=pelunasan, jumlah=total_harga-50000, metode=cash, status=terverifikasi)
        PM->>DB: INSERT pembayaran (auto pelunasan)
        DB-->>PM: OK
    end

    Note over TC,DB: DB::transaction() commit

    TC-->>V: Redirect back + success
    V-->>D: "Penumpang telah sampai di tujuan"
```

---

## 11. Driver — Selesaikan Trip (on_trip → completed)

**Controller:** `Driver\TripController::complete()`
**Observer:** `TripObserver::updated()`

```mermaid
sequenceDiagram
    actor D as Driver
    participant V as View<br/>(driver/trips/show)
    participant TC as Driver<br/>TripController
    participant TM as Trip Model
    participant TO as TripObserver
    participant BM as Booking Model
    participant JM as Jadwal Model
    participant DB as Database

    D->>V: Klik "Selesaikan Trip"
    V->>TC: PUT /driver/trips/{trip}/complete

    TC->>TC: Cek semua penumpang sudah diantar?

    alt Ada penumpang belum diantar
        TC-->>V: Redirect back + error
    end

    TC->>TM: trip->update(status_trip: completed, completed_at: now())
    TM->>DB: UPDATE trips

    Note over TM,TO: TripObserver::updated() (status_trip: on_trip → completed)
    TO->>BM: Semua booking di trip → update(status: completed)
    BM->>DB: UPDATE bookings SET status_booking='completed'

    TO->>JM: jadwal->checkAndUpdateStatus()
    JM->>DB: UPDATE jadwal

    TC-->>V: Redirect ke driver.dashboard + success
    V-->>D: "Trip berhasil diselesaikan"
```

---

## 12. Pelanggan — Batalkan Booking

**Controller:** `BookingController::cancel()`

```mermaid
sequenceDiagram
    actor P as Pelanggan
    participant V as View<br/>(booking/show)
    participant BC as BookingController
    participant BM as Booking Model
    participant FS as FonnteService
    participant WN as WhatsappNotification
    participant DB as Database

    P->>V: Klik "Batalkan Booking" + isi alasan
    V->>BC: PUT /booking/{kode}/cancel (alasan_pembatalan)

    BC->>BM: Booking::where('kode_booking', kode)
    BM->>DB: SELECT booking + pelanggan + jadwal
    DB-->>BM: Booking record

    BC->>BC: Validasi: status bukan on_trip/completed/cancelled/expired?

    alt Tidak bisa dibatalkan
        BC-->>V: Redirect back + error
    end

    BC->>BM: booking->update(status: cancelled, alasan_pembatalan)
    BM->>DB: UPDATE bookings
    DB-->>BM: OK

    Note over BM,DB: BookingObserver::saved() → jadwal->checkAndUpdateStatus()

    BC->>BC: buildCancellationMessage(booking, alasan)

    BC->>FS: send(adminWA, message, TYPE_PEMBATALAN_BOOKING, booking_id)
    FS->>WN: create + kirim ke Admin
    WN->>DB: INSERT whatsapp_notifications

    alt Booking sudah di-assign ke trip
        BC->>FS: send(driver.no_hp, message, TYPE_PEMBATALAN_BOOKING, booking_id)
        FS->>WN: create + kirim ke Driver
        WN->>DB: INSERT whatsapp_notifications
    end

    BC-->>V: Redirect ke booking.show + success
    V-->>P: "Pemesanan berhasil dibatalkan"
```

---

## 13. Pelanggan — Beri Rating & Ulasan

**Controller:** `RatingController::store()`

```mermaid
sequenceDiagram
    actor P as Pelanggan
    participant V as View<br/>(booking/show)
    participant RC as RatingController
    participant BM as Booking Model
    participant RM as Rating Model
    participant DB as Database

    P->>V: Isi form rating (1-5 bintang) + ulasan
    V->>RC: POST /booking/{kode}/rating

    RC->>BM: Booking::with('pelanggan')->where('kode_booking', kode)
    BM->>DB: SELECT booking + pelanggan
    DB-->>BM: Booking record

    RC->>RC: Validasi: status = completed? belum ada rating?

    alt Status bukan completed
        RC-->>V: Redirect back + error
    end
    alt Sudah ada rating
        RC-->>V: Redirect back + error "sudah memberikan rating"
    end

    RC->>RM: Rating::create(booking_id, pelanggan_id, rating, ulasan, status: menunggu)
    RM->>DB: INSERT ratings (status: menunggu)
    DB-->>RM: Rating record

    RC-->>V: Redirect ke booking.show + success
    V-->>P: "Ulasan menunggu persetujuan admin"
```

---

## 14. Admin — Kelola Rating (Publish/Hide/Hapus)

**Controller:** `Admin\RatingController`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/rating/index)
    participant RC as Admin<br/>RatingController
    participant RM as Rating Model
    participant DB as Database

    A->>V: Lihat daftar ulasan

    alt Publish ulasan
        A->>RC: PUT /admin/rating/{id}/publish
        RC->>RM: rating->update(status: published)
        RM->>DB: UPDATE ratings SET status='published'
        RC-->>V: "Ulasan berhasil dipublikasikan di Landing Page"
    end

    alt Sembunyikan ulasan
        A->>RC: PUT /admin/rating/{id}/hide
        RC->>RM: rating->update(status: hidden)
        RM->>DB: UPDATE ratings SET status='hidden'
        RC-->>V: "Ulasan berhasil disembunyikan"
    end

    alt Hapus ulasan
        A->>RC: DELETE /admin/rating/{id}
        RC->>RM: rating->delete()
        RM->>DB: DELETE ratings WHERE id={id}
        RC-->>V: "Ulasan berhasil dihapus"
    end
```

---

## 15. Sistem — Expirasi Booking Otomatis (Scheduler)

**Console:** `booking:expire` command
**Service:** `BookingService::expireBooking()`
**Schedule:** Setiap menit (`everyMinute()`)

```mermaid
sequenceDiagram
    participant SCH as Scheduler<br/>(everyMinute)
    participant CMD as Artisan Command<br/>(booking:expire)
    participant BS as BookingService
    participant BM as Booking Model
    participant PM as Pembayaran Model
    participant JM as Jadwal Model
    participant FS as FileStorage
    participant DB as Database

    SCH->>CMD: Jalankan booking:expire

    CMD->>BM: Booking::where(status=booking_dibuat, expired_at <= now())
    BM->>DB: SELECT bookings yang expired
    DB-->>BM: Daftar booking expired

    loop Untuk setiap booking expired
        CMD->>BS: expireBooking(booking)

        Note over BS,DB: DB::transaction() begin

        BS->>BS: Cek: status masih booking_dibuat? expired_at sudah lewat?

        BS->>PM: booking->pembayaran (load relasi)
        PM->>DB: SELECT pembayaran
        loop Untuk setiap pembayaran
            BS->>FS: Storage::disk('public')->delete(bukti_pembayaran)
        end

        BS->>PM: booking->pembayaran()->delete()
        PM->>DB: DELETE pembayaran WHERE booking_id={id}

        BS->>JM: Simpan referensi jadwal
        BS->>BM: booking->delete()
        BM->>DB: DELETE bookings WHERE id={id}

        BS->>JM: jadwal->refresh() → checkAndUpdateStatus()
        JM->>DB: UPDATE jadwal (kuota dikembalikan)

        Note over BS,DB: DB::transaction() commit
    end

    CMD-->>SCH: Log: "Berhasil menghapus X booking"
```

---

## 16. Sistem — Konfirmasi Keberangkatan Pagi (Scheduler)

**Console:** `booking:send-confirmation` command
**Schedule:** Setiap hari jam 06:00 WIB

```mermaid
sequenceDiagram
    participant SCH as Scheduler<br/>(daily 06:00 WIB)
    participant CMD as Artisan Command<br/>(booking:send-confirmation)
    participant BM as Booking Model
    participant FS as FonnteService
    participant FA as FonnteAPI
    participant WN as WhatsappNotification
    participant DB as Database

    SCH->>CMD: Jalankan booking:send-confirmation

    CMD->>BM: Booking yang berangkat hari ini + belum dapat notifikasi konfirmasi hari ini
    BM->>DB: SELECT bookings + jadwal + pelanggan + driver (status: dikonfirmasi/assigned_to_trip)
    DB-->>BM: Daftar booking hari ini

    loop Untuk setiap booking
        CMD->>CMD: buildConfirmationMessage(booking)
        CMD->>FS: send(pelanggan.no_hp, message, TYPE_KONFIRMASI_KEBERANGKATAN, booking_id)
        FS->>WN: WhatsappNotification::create(status: pending)
        WN->>DB: INSERT whatsapp_notifications

        alt Token kosong (mock)
            FS->>WN: update(status: sent)
        else Token ada
            FS->>FA: POST api.fonnte.com/send
            FA-->>FS: Response
            FS->>WN: update(status berdasarkan response)
        end
    end

    CMD-->>SCH: Log: "Terkirim: X, Gagal: Y"
```

---

## 17. Pengunjung / Pelanggan — Cek Booking Publik

**Controller:** `CekBookingController::index()`, `CekBookingController::show()`
**View:** `public.cek-booking.index`, `public.cek-booking.show`

```mermaid
sequenceDiagram
    actor P as Pengunjung
    participant V as View<br/>(cek-booking/index)
    participant CC as CekBookingController
    participant BM as Booking Model
    participant DB as Database

    P->>V: Buka halaman cek booking
    V-->>P: Tampilkan form input kode booking

    P->>V: Masukkan kode booking → Submit
    V->>CC: POST /cek-booking (CekBookingRequest)
    CC-->>V: Redirect ke GET /cek-booking?kode_booking=XXX

    V->>CC: GET /cek-booking?kode_booking=XXX
    CC->>BM: Booking::with([pelanggan, jadwal.rute, pembayaran, detailTrips.trip.driver])
    BM->>DB: SELECT booking + relasi
    DB-->>BM: Booking record

    alt Booking tidak ditemukan
        CC-->>V: Redirect + error "Kode booking tidak ditemukan"
    end

    CC-->>V: Tampilkan detail booking + status + payment
    V-->>P: Tampilkan timeline status booking
```

---

## 18. Pengunjung — Lihat Landing Page

**Controller:** `HomeController::index()`
**View:** `public.home`

```mermaid
sequenceDiagram
    actor P as Pengunjung
    participant V as View<br/>(public/home)
    participant HC as HomeController
    participant JM as Jadwal Model
    participant DM as Driver Model
    participant RM as Rating Model
    participant BM as Booking Model
    participant TM as Trip Model
    participant DB as Database

    P->>V: Buka halaman utama (/)
    V->>HC: GET /

    HC->>JM: Jadwal::aktif()->without(trip on_trip/completed)
    JM->>DB: SELECT jadwal aktif + rute + booked_seats
    DB-->>JM: Daftar jadwal tersedia

    HC->>DM: Driver::where(aktif)->take(4)
    DM->>DB: SELECT 4 driver aktif + armada
    DB-->>DM: Daftar driver

    HC->>RM: Rating::where(published)->latest()->take(6)
    RM->>DB: SELECT 6 ulasan published + pelanggan
    DB-->>RM: Daftar rating

    HC->>BM: Booking::where(completed)->sum('jumlah_penumpang')
    BM->>DB: SELECT SUM(jumlah_penumpang) FROM bookings WHERE status=completed
    DB-->>BM: Total penumpang

    HC->>RM: Rating::where(published) → avg(rating)
    RM->>DB: SELECT AVG(rating) FROM ratings WHERE status=published
    DB-->>RM: Rata-rata rating

    HC->>TM: Trip::where(completed) → hitung on-time percentage
    TM->>DB: SELECT completed trips + jadwal
    DB-->>TM: Completed trips

    HC-->>V: compact(schedules, drivers, ratings, stats)
    V-->>P: Tampilkan landing page (jadwal, driver, rating, statistik)
```

---

## 19. Admin — Kelola Master Data (Rute, Armada, Driver, Jadwal)

**Controller:** `Admin\RuteController`, `Admin\ArmadaController`, `Admin\DriverController`, `Admin\JadwalController`

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View<br/>(admin/*)
    participant C as Admin Controller<br/>(CRUD)
    participant M as Model<br/>(Rute/Armada/Driver/Jadwal)
    participant DB as Database

    Note over A,DB: CRUD Rute (asal, tujuan, tarif)
    A->>V: Buka daftar rute
    V->>C: GET /admin/rute
    C->>M: Rute::latest()->paginate()
    M->>DB: SELECT rute
    DB-->>M: Daftar rute
    C-->>V: Tampilkan daftar

    A->>V: Tambah/Edit/Hapus rute
    V->>C: POST/PUT/DELETE /admin/rute/{id}
    C->>M: create/update/delete
    M->>DB: INSERT/UPDATE/DELETE rute
    C-->>V: Redirect + success

    Note over A,DB: CRUD Armada (nama_mobil, nomor_plat, kapasitas, status)
    A->>C: CRUD /admin/armada
    C->>M: Armada::create/update/delete
    M->>DB: INSERT/UPDATE/DELETE armada

    Note over A,DB: CRUD Driver (nama_driver, no_hp, armada_id, status + create User account)
    A->>C: POST /admin/drivers
    C->>M: User::create(role=driver) + Driver::create(user_id, armada_id)
    M->>DB: INSERT users + INSERT drivers

    Note over A,DB: CRUD Jadwal (rute_id, tanggal, shift, jam, kuota, status)
    A->>C: CRUD /admin/jadwal
    C->>M: Jadwal::create/update/delete
    M->>DB: INSERT/UPDATE/DELETE jadwal
```

---

## Observer Pattern Summary

| Observer | Event | Aksi |
|---|---|---|
| `BookingObserver::saved()` | Setiap kali booking disimpan | `jadwal->checkAndUpdateStatus()` — Update status jadwal (aktif/penuh) |
| `BookingObserver::deleted()` | Booking dihapus | `jadwal->checkAndUpdateStatus()` — Kembalikan kuota |
| `DetailTripObserver::created()` | Booking di-assign ke trip | `booking->update(status: assigned_to_trip)` |
| `DetailTripObserver::deleted()` | Booking dikeluarkan dari trip | `booking->update(status: dikonfirmasi)` |
| `TripObserver::updated()` | Status trip berubah | Cascade update status semua booking di trip + `jadwal->checkAndUpdateStatus()` |
| `TripObserver::deleting()` | Trip dihapus | Semua booking dikembalikan ke status `dikonfirmasi` |
| `TripObserver::deleted()` | Setelah trip dihapus | `jadwal->checkAndUpdateStatus()` |

---

## Ringkasan Route

### Publik (Tanpa Login)
| Method | URL | Controller | Fungsi |
|---|---|---|---|
| GET | `/` | `HomeController::index` | Landing page |
| GET | `/jadwal` | `JadwalPublicController::index` | Lihat jadwal |
| GET | `/cek-booking` | `CekBookingController::index` | Cek status booking |
| POST | `/cek-booking` | `CekBookingController::show` | Submit kode booking |

### Pelanggan (role: pelanggan)
| Method | URL | Controller | Fungsi |
|---|---|---|---|
| GET | `/booking/create` | `BookingController::create` | Form booking |
| POST | `/booking` | `BookingController::store` | Simpan booking |
| GET | `/booking/{kode}/review` | `BookingController::review` | Review booking |
| GET | `/booking/{kode}/pembayaran` | `PembayaranController::show` | Form upload DP |
| POST | `/booking/{kode}/pembayaran` | `PembayaranController::store` | Upload bukti DP |
| GET | `/booking/{kode}/edit` | `BookingController::edit` | Edit lokasi |
| PUT | `/booking/{kode}` | `BookingController::update` | Update lokasi |
| PUT | `/booking/{kode}/cancel` | `BookingController::cancel` | Batalkan booking |
| GET | `/booking-saya` | `BookingController::index` | Daftar booking saya |
| GET | `/booking/{kode}` | `BookingController::show` | Detail booking |
| POST | `/booking/{kode}/rating` | `RatingController::store` | Beri rating |

### Admin (role: admin, prefix: /admin)
| Method | URL | Controller | Fungsi |
|---|---|---|---|
| GET | `/admin/dashboard` | `Admin\DashboardController::index` | Dashboard admin |
| Resource | `/admin/rute` | `Admin\RuteController` | CRUD Rute |
| Resource | `/admin/armada` | `Admin\ArmadaController` | CRUD Armada |
| Resource | `/admin/jadwal` | `Admin\JadwalController` | CRUD Jadwal |
| Resource | `/admin/drivers` | `Admin\DriverController` | CRUD Driver |
| Resource | `/admin/trips` | `Admin\TripController` | CRUD Trip |
| POST | `/admin/trips/{trip}/assign` | `Admin\TripController::assignBooking` | Assign booking ke trip |
| DELETE | `/admin/trips/{trip}/remove/{dt}` | `Admin\TripController::removeBooking` | Keluarkan dari trip |
| PUT | `/admin/pembayaran/{id}/verify` | `Admin\PembayaranController::verify` | Verifikasi DP |
| PUT | `/admin/pembayaran/{id}/reject` | `Admin\PembayaranController::reject` | Tolak DP |
| PUT | `/admin/rating/{id}/publish` | `Admin\RatingController::publish` | Publish ulasan |
| PUT | `/admin/rating/{id}/hide` | `Admin\RatingController::hide` | Sembunyikan ulasan |

### Driver (role: driver, prefix: /driver)
| Method | URL | Controller | Fungsi |
|---|---|---|---|
| GET | `/driver/dashboard` | `Driver\DashboardController::index` | Dashboard driver |
| GET | `/driver/trips` | `Driver\TripController::index` | Riwayat trip |
| GET | `/driver/trips/{trip}` | `Driver\TripController::show` | Detail trip |
| PUT | `/driver/trips/{trip}/start` | `Driver\TripController::start` | Mulai trip |
| PUT | `/driver/trips/{trip}/pickup/{dt}` | `Driver\TripController::pickup` | Jemput penumpang |
| PUT | `/driver/trips/{trip}/dropoff/{dt}` | `Driver\TripController::dropoff` | Antar penumpang |
| PUT | `/driver/trips/{trip}/complete` | `Driver\TripController::complete` | Selesaikan trip |
| PUT | `/driver/trips/{trip}/confirm-payment/{dt}` | `Driver\TripController::confirmPayment` | Konfirmasi pelunasan |
