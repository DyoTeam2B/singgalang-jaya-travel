# Singgalang Jaya Travel — Daftar Fitur & Route Lengkap

## Tim Pengembang

| Nama | Peran | Area Tanggung Jawab |
|------|-------|---------------------|
| **Rayhan** | Developer | Customer Interface (Landing, Booking, Payment, Booking Saya) |
| **Rayfo** | Developer | Admin Core (Dashboard, Rute, Armada, Jadwal, Laporan) |
| **Nayasha** | Developer | Auth + Admin Operasional (Booking Mgmt, Payment Mgmt, Driver Mgmt) |
| **Kevin** | Developer | Trip & Driver Operations (Admin Trip, Driver Panel, Maps) |

---

## Tech Stack Context

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 13 |
| Frontend | Blade + **Livewire 4.3** |
| Interaktif | Alpine.js 3.x |
| Auth | Laravel Breeze (Blade stack) |
| CSS | TailwindCSS 3.x + @tailwindcss/forms |
| Build | Vite 8.x |

> **Livewire** digunakan untuk komponen interaktif (tabel dinamis, filter, modal, form multi-step, dll).
> **Blade biasa** tetap digunakan untuk halaman statis dan layout.

---

## Fitur Per Aktor

### 👤 Pelanggan

#### Navbar Guest (Belum Login)

```text
Home | Jadwal | Armada & Driver | Charter | Kontak | Login | Register
```

#### Navbar Setelah Login

```text
Home | Jadwal | Booking Saya | Charter | Kontak | Profil
```

#### Dropdown Profil

```text
Profil Saya | Booking Saya | Logout
```

#### Fitur Pelanggan

* Register (✅ Selesai)
* Login (✅ Selesai)
* Profil Saya (✅ Selesai)
* Edit Profil (✅ Selesai)
* Lihat Jadwal (✅ Selesai)
* Booking Travel (✅ Selesai)
* Upload Bukti DP (✅ Selesai)
* Booking Saya (list) (✅ Selesai)
* Detail Booking (✅ Selesai)
* Status Booking (✅ Selesai)
* Informasi Driver (setelah assigned ke trip) (✅ Selesai)
* Riwayat Booking (✅ Selesai)
* Logout (✅ Selesai)

---

### 🛠️ Admin

#### Dashboard

* Total Booking
* Total Trip
* Total Pendapatan
* Total Driver
* Total Armada

#### Master Data

* CRUD Rute
* CRUD Armada
* CRUD Driver (link ke armada)
* CRUD Jadwal

#### Operasional

* Kelola Booking
* Verifikasi DP
* Kelola Trip
* Assign Driver & Armada ke Trip
* Lihat Manifest

#### Laporan

* Laporan Booking
* Laporan Trip
* Laporan Pendapatan

---

### 🚗 Driver

#### Dashboard

* Trip Hari Ini
* Total Penumpang

#### Operasional

* Lihat Trip
* Lihat Manifest (daftar penumpang, alamat jemput, titik maps, sisa pembayaran)
* Lihat Maps
* Hubungi Penumpang
* Update Status Trip (Mulai → Menjemput → Berangkat → Tiba → Selesai)
* Checklist Penumpang (jemput/antar)
* Konfirmasi Pelunasan

#### Riwayat

* Riwayat Trip

---

## Daftar Route Lengkap

### A. PUBLIC / CUSTOMER ROUTES — `Rayhan`

> ⚠️ Booking routes memerlukan middleware `auth` karena pelanggan WAJIB login.

#### A1. Landing Page

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 1 | GET | `/` | `home` | `HomeController@index` | `public.home` |

#### A2. Jadwal Keberangkatan (Public)

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 2 | GET | `/jadwal` | `jadwal.index` | `JadwalPublicController@index` | `public.jadwal.index` |

#### A3. Booking Travel

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 3 | GET | `/booking/create` | `booking.create` | `BookingController@create` | `public.booking.create` |
| 4 | POST | `/booking` | `booking.store` | `BookingController@store` | — (redirect) |
| 5 | GET | `/booking/{kode}/review` | `booking.review` | `BookingController@review` | `public.booking.review` |

> **Livewire option**: Form booking bisa pakai Livewire component `BookingForm` untuk reactivity (hitung tarif = harga rute × jumlah penumpang, map picker).
> **Auth**: Route booking memerlukan middleware `auth, role:pelanggan`.

#### A4. Pembayaran DP (Customer)

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 6 | GET | `/booking/{kode}/pembayaran` | `booking.pembayaran` | `PembayaranController@show` | `public.pembayaran.show` |
| 7 | POST | `/booking/{kode}/pembayaran` | `booking.pembayaran.store` | `PembayaranController@store` | — (redirect) |

#### A5. Booking Saya

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 8 | GET | `/booking-saya` | `booking.index` | `BookingController@index` | `public.booking.index` |
| 9 | GET | `/booking/{kode}` | `booking.show` | `BookingController@show` | `public.booking.show` |

#### A6. AJAX Support (via JadwalPublicController)

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 10 | GET | `/jadwal/available` | `jadwal.available` | `JadwalPublicController@available` | — (JSON) |
| 11 | GET | `/jadwal/{id}/check-kuota` | `jadwal.checkKuota` | `JadwalPublicController@checkKuota` | — (JSON) |

> **Total Rayhan: 11 routes**

---

### B. AUTH ROUTES — `Nayasha` (SUDAH ADA via Breeze)

> ⚠️ Auth sudah di-scaffold oleh Laravel Breeze. File ada di `routes/auth.php`.

#### B1. Authentication (Sudah Ada)

| # | Method | URI | Name | Controller | Status |
|---|--------|-----|------|------------|--------|
| 12 | GET | `/login` | `login` | `Auth\AuthenticatedSessionController@create` | ✅ |
| 13 | POST | `/login` | — | `Auth\AuthenticatedSessionController@store` | ✅ |
| 14 | POST | `/logout` | `logout` | `Auth\AuthenticatedSessionController@destroy` | ✅ |
| 15 | GET | `/register` | `register` | `Auth\RegisteredUserController@create` | ✅ |
| 16 | POST | `/register` | — | `Auth\RegisteredUserController@store` | ✅ |

#### B2. Profile & Password (Sudah Ada)

| # | Method | URI | Name | Controller | Status |
|---|--------|-----|------|------------|--------|
| 17 | GET | `/profile` | `profile.edit` | `ProfileController@edit` | ✅ |
| 18 | PATCH | `/profile` | `profile.update` | `ProfileController@update` | ✅ |
| 19 | DELETE | `/profile` | `profile.destroy` | `ProfileController@destroy` | ✅ |
| 20 | PUT | `/password` | `password.update` | `Auth\PasswordController@update` | ✅ |

---

### C. ADMIN ROUTES — Prefix: `/admin` — Middleware: `auth`, `role:admin`

#### C1. Admin Dashboard — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 21 | GET | `/admin/dashboard` | `admin.dashboard` | `Admin\DashboardController@index` | `admin.dashboard` |

> **Status**: Route ada ✅, view placeholder ⚠️

#### C2. Admin Rute Management — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 22 | GET | `/admin/rute` | `admin.rute.index` | `Admin\RuteController@index` | `admin.rute.index` |
| 23 | GET | `/admin/rute/create` | `admin.rute.create` | `Admin\RuteController@create` | `admin.rute.create` |
| 24 | POST | `/admin/rute` | `admin.rute.store` | `Admin\RuteController@store` | — (redirect) |
| 25 | GET | `/admin/rute/{id}/edit` | `admin.rute.edit` | `Admin\RuteController@edit` | `admin.rute.edit` |
| 26 | PUT | `/admin/rute/{id}` | `admin.rute.update` | `Admin\RuteController@update` | — (redirect) |
| 27 | DELETE | `/admin/rute/{id}` | `admin.rute.destroy` | `Admin\RuteController@destroy` | — (redirect) |

#### C3. Admin Armada Management — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 28 | GET | `/admin/armada` | `admin.armada.index` | `Admin\ArmadaController@index` | `admin.armada.index` |
| 29 | GET | `/admin/armada/create` | `admin.armada.create` | `Admin\ArmadaController@create` | `admin.armada.create` |
| 30 | POST | `/admin/armada` | `admin.armada.store` | `Admin\ArmadaController@store` | — (redirect) |
| 31 | GET | `/admin/armada/{id}/edit` | `admin.armada.edit` | `Admin\ArmadaController@edit` | `admin.armada.edit` |
| 32 | PUT | `/admin/armada/{id}` | `admin.armada.update` | `Admin\ArmadaController@update` | — (redirect) |
| 33 | DELETE | `/admin/armada/{id}` | `admin.armada.destroy` | `Admin\ArmadaController@destroy` | — (redirect) |

#### C4. Admin Jadwal Management — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 34 | GET | `/admin/jadwal` | `admin.jadwal.index` | `Admin\JadwalController@index` | `admin.jadwal.index` |
| 35 | GET | `/admin/jadwal/create` | `admin.jadwal.create` | `Admin\JadwalController@create` | `admin.jadwal.create` |
| 36 | POST | `/admin/jadwal` | `admin.jadwal.store` | `Admin\JadwalController@store` | — (redirect) |
| 37 | GET | `/admin/jadwal/{id}/edit` | `admin.jadwal.edit` | `Admin\JadwalController@edit` | `admin.jadwal.edit` |
| 38 | PUT | `/admin/jadwal/{id}` | `admin.jadwal.update` | `Admin\JadwalController@update` | — (redirect) |
| 39 | DELETE | `/admin/jadwal/{id}` | `admin.jadwal.destroy` | `Admin\JadwalController@destroy` | — (redirect) |
| 40 | PUT | `/admin/jadwal/{id}/toggle` | `admin.jadwal.toggle` | `Admin\JadwalController@toggleStatus` | — (redirect) |

#### C5. Admin Booking Management — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 41 | GET | `/admin/bookings` | `admin.bookings.index` | `Admin\BookingController@index` | `admin.bookings.index` |
| 42 | GET | `/admin/bookings/{id}` | `admin.bookings.show` | `Admin\BookingController@show` | `admin.bookings.show` |
| 43 | PUT | `/admin/bookings/{id}/cancel` | `admin.bookings.cancel` | `Admin\BookingController@cancel` | — (redirect) |

> **Livewire option**: Tabel booking bisa pakai Livewire `BookingTable` untuk search/filter/pagination realtime.

#### C6. Admin Pembayaran Verification — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 44 | GET | `/admin/pembayaran` | `admin.pembayaran.index` | `Admin\PembayaranController@index` | `admin.pembayaran.index` |
| 45 | GET | `/admin/pembayaran/{id}` | `admin.pembayaran.show` | `Admin\PembayaranController@show` | `admin.pembayaran.show` |
| 46 | PUT | `/admin/pembayaran/{id}/verify` | `admin.pembayaran.verify` | `Admin\PembayaranController@verify` | — (redirect) |
| 47 | PUT | `/admin/pembayaran/{id}/reject` | `admin.pembayaran.reject` | `Admin\PembayaranController@reject` | — (redirect) |

#### C7. Admin Driver Management — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 48 | GET | `/admin/drivers` | `admin.drivers.index` | `Admin\DriverController@index` | `admin.drivers.index` |
| 49 | GET | `/admin/drivers/create` | `admin.drivers.create` | `Admin\DriverController@create` | `admin.drivers.create` |
| 50 | POST | `/admin/drivers` | `admin.drivers.store` | `Admin\DriverController@store` | — (redirect) |
| 51 | GET | `/admin/drivers/{id}` | `admin.drivers.show` | `Admin\DriverController@show` | `admin.drivers.show` |
| 52 | GET | `/admin/drivers/{id}/edit` | `admin.drivers.edit` | `Admin\DriverController@edit` | `admin.drivers.edit` |
| 53 | PUT | `/admin/drivers/{id}` | `admin.drivers.update` | `Admin\DriverController@update` | — (redirect) |
| 54 | DELETE | `/admin/drivers/{id}` | `admin.drivers.destroy` | `Admin\DriverController@destroy` | — (redirect) |

#### C8. Admin Trip Management — `Kevin`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 55 | GET | `/admin/trips` | `admin.trips.index` | `Admin\TripController@index` | `admin.trips.index` |
| 56 | GET | `/admin/trips/create` | `admin.trips.create` | `Admin\TripController@create` | `admin.trips.create` |
| 57 | POST | `/admin/trips` | `admin.trips.store` | `Admin\TripController@store` | — (redirect) |
| 58 | GET | `/admin/trips/{id}` | `admin.trips.show` | `Admin\TripController@show` | `admin.trips.show` |
| 59 | PUT | `/admin/trips/{id}` | `admin.trips.update` | `Admin\TripController@update` | — (redirect) |
| 60 | POST | `/admin/trips/{id}/assign` | `admin.trips.assign` | `Admin\TripController@assignBooking` | — (redirect) |
| 61 | DELETE | `/admin/trips/{id}/remove/{detailId}` | `admin.trips.remove` | `Admin\TripController@removeBooking` | — (redirect) |
| 62 | DELETE | `/admin/trips/{id}` | `admin.trips.destroy` | `Admin\TripController@destroy` | — (redirect) |

#### C9. Admin Laporan — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 63 | GET | `/admin/laporan` | `admin.laporan.index` | `Admin\LaporanController@index` | `admin.laporan.index` |
| 64 | GET | `/admin/laporan/export` | `admin.laporan.export` | `Admin\LaporanController@export` | — (download) |

> **Total Rayfo: 22 routes** (Dashboard + Rute + Armada + Jadwal + Laporan)
> **Total Nayasha: 16 routes** (Auth✅done + Booking Mgmt + Pembayaran + Driver)

---

### D. DRIVER ROUTES — Prefix: `/driver` — Middleware: `auth`, `role:driver` — `Kevin`

#### D1. Driver Dashboard

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 65 | GET | `/driver/dashboard` | `driver.dashboard` | `Driver\DashboardController@index` | `driver.dashboard` |

> **Status**: Route ada ✅, view placeholder ⚠️

#### D2. Driver Trip & Manifest

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 66 | GET | `/driver/trips` | `driver.trips.index` | `Driver\TripController@index` | `driver.trips.index` |
| 67 | GET | `/driver/trips/{id}` | `driver.trips.show` | `Driver\TripController@show` | `driver.trips.show` |

#### D3. Driver Trip Operations

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 68 | PUT | `/driver/trips/{id}/start` | `driver.trips.start` | `Driver\TripController@start` | — (redirect) |
| 69 | PUT | `/driver/trips/{id}/pickup/{detailId}` | `driver.trips.pickup` | `Driver\TripController@pickup` | — (redirect) |
| 70 | PUT | `/driver/trips/{id}/dropoff/{detailId}` | `driver.trips.dropoff` | `Driver\TripController@dropoff` | — (redirect) |
| 71 | PUT | `/driver/trips/{id}/complete` | `driver.trips.complete` | `Driver\TripController@complete` | — (redirect) |
| 72 | PUT | `/driver/trips/{id}/confirm-payment/{detailId}` | `driver.trips.confirmPayment` | `Driver\TripController@confirmPayment` | — (redirect) |

> **Total Kevin: 16 routes** (Admin Trip + Driver Panel + Confirm Payment)

---

### E. WHATSAPP NOTIFICATION (FonnteAPI) — Shared

> Integrasi WhatsApp via **FonnteAPI** (bukan `wa.me` link).

| # | Trigger | Target | Pesan | Mekanisme |
|---|---------|--------|-------|-----------|
| N1 | Booking dibatalkan pelanggan | Admin + Driver (jika assigned) | "Booking {kode} dibatalkan" | Event + FonnteService |
| N2 | Pagi hari sebelum keberangkatan | Pelanggan | "Konfirmasi keberangkatan Anda hari ini" | Scheduler `06:00` |

**Scheduler Commands**:
- `booking:send-confirmation` — Kirim WA konfirmasi ulang pagi hari (run jam 06:00)

---

## Ringkasan Pembagian Route

| Nama | Modul | Jumlah Route |
|------|-------|:------------:|
| **Rayhan** | Landing, Jadwal Public, Booking, Payment (Customer), Booking Saya, API | **11** |
| **Rayfo** | Admin Dashboard, Admin Rute, Admin Armada, Admin Jadwal, Admin Laporan | **22** |
| **Nayasha** | Auth (✅done), Admin Booking, Admin Pembayaran, Admin Driver | **16** |
| **Kevin** | Admin Trip, Driver Dashboard, Driver Trip & Operations | **16** |
| | **TOTAL** | **65** |

---

## Daftar View (Blade) Lengkap

### Layouts (Existing dari Breeze)

| View | Status | Keterangan |
|------|--------|------------|
| `layouts.app` | ✅ Sudah ada | Layout utama (slot-based, `{{ $slot }}`) |
| `layouts.guest` | ✅ Sudah ada | Layout halaman guest (login/register) |
| `layouts.navigation` | ✅ Sudah ada | Navbar navigasi Breeze |

### Layouts (Perlu Dibuat)

| View | PIC | Keterangan |
|------|-----|------------|
| `layouts.public` | Rayhan | Layout halaman publik/customer |
| `layouts.admin` | Rayfo | Layout admin dengan sidebar |
| `layouts.driver` | Kevin | Layout driver dengan sidebar |

### Components (Existing dari Breeze)

| Component | Status |
|-----------|--------|
| `components.primary-button` | ✅ |
| `components.secondary-button` | ✅ |
| `components.danger-button` | ✅ |
| `components.text-input` | ✅ |
| `components.input-label` | ✅ |
| `components.input-error` | ✅ |
| `components.dropdown` | ✅ |
| `components.dropdown-link` | ✅ |
| `components.modal` | ✅ |
| `components.nav-link` | ✅ |
| `components.responsive-nav-link` | ✅ |
| `components.auth-session-status` | ✅ |
| `components.application-logo` | ✅ |

### Components (Perlu Dibuat)

| Component | PIC | Keterangan |
|-----------|-----|------------|
| `components.sidebar-admin` | Rayfo | Sidebar admin navigation |
| `components.sidebar-driver` | Kevin | Sidebar driver navigation |
| `components.status-badge` | Nayasha | Badge status (booking/trip/payment) |
| `components.map-picker` | Rayhan | Leaflet map picker |
| `components.map-viewer` | Kevin | Leaflet map viewer |
| `components.alert` | Nayasha | Flash message alert |
| `components.card` | Rayfo | Card component |

### Livewire Components

| Component | PIC | Keterangan | Status |
|-----------|-----|------------|:------:|
| `Livewire\BookingForm` | Rayhan | Form booking interaktif (hitung tarif, map) | ✅ |
| `Livewire\Admin\BookingTable` | Nayasha | Tabel booking dengan search/filter | ✅ |
| `Livewire\Admin\PembayaranTable` | Nayasha | Tabel pembayaran dengan filter | ✅ |
| `Livewire\Admin\ArmadaTable` | Rayfo | Tabel armada dengan search | ❌ (CRUD modal) |
| `Livewire\Admin\JadwalTable` | Rayfo | Tabel jadwal dengan filter | ❌ (Tidak perlu) |
| `Livewire\Admin\DriverTable` | Nayasha | Tabel driver dengan search | ❌ (Tidak perlu) |
| `Livewire\Admin\TripTable` | Kevin | Tabel trip dengan filter | 🔲 |
| `Livewire\Driver\TripManifest` | Kevin | Manifest penumpang interaktif | 🔲 |

> **Note**: Livewire components optional per fitur. Kalau fitur simple (CRUD form biasa), cukup pakai Blade + controller biasa. Gunakan Livewire untuk fitur yang butuh **reactivity tanpa full page reload** (search, filter, pagination, auto-calculate).

### Public Views — `Rayhan`

| View | Keterangan | Status |
|------|------------|:------:|
| `public.home` | Landing page (Hero, Keunggulan, Jadwal, Armada & Driver, Charter, Kontak, CTA, Footer) | ✅ |
| `public.jadwal.index` | Daftar jadwal keberangkatan | ✅ |
| `public.booking.create` | Form booking | ✅ |
| `public.booking.review` | Review booking sebelum submit | ✅ |
| `public.booking.index` | Booking Saya (daftar booking pelanggan) | ✅ |
| `public.booking.show` | Detail Booking + Status + Informasi Driver | ✅ |
| `public.pembayaran.show` | Halaman instruksi & upload DP | ✅ |

### Auth Views — `Nayasha` (Sudah Ada)

| View | Status |
|------|--------|
| `auth.login` | ✅ |
| `auth.register` | ✅ |
| `auth.forgot-password` | ✅ |
| `auth.reset-password` | ✅ |
| `auth.confirm-password` | ✅ |
| `auth.verify-email` | ✅ |
| `profile.edit` | ✅ |

### Admin Views

| View | PIC | Keterangan | Status |
|------|-----|------------|:------:|
| `admin.dashboard` | Rayfo | Dashboard + widget statistik (Total Booking, Trip, Pendapatan, Driver, Armada) | ✅ |
| `admin.rute.index` | Rayfo | Tabel daftar rute | ✅ |
| `admin.rute.create` | Rayfo | Form tambah rute | ✅ |
| `admin.rute.edit` | Rayfo | Form edit rute | ✅ |
| `admin.armada.index` | Rayfo | Tabel daftar armada | ✅ |
| `admin.armada.create` | Rayfo | Form tambah armada (CRUD modal) | ✅ |
| `admin.armada.edit` | Rayfo | Form edit armada (CRUD modal) | ✅ |
| `admin.jadwal.index` | Rayfo | Tabel daftar jadwal | ✅ |
| `admin.jadwal.create` | Rayfo | Form tambah jadwal | ✅ |
| `admin.jadwal.edit` | Rayfo | Form edit jadwal | ✅ |
| `admin.laporan.index` | Rayfo | Halaman laporan + filter | 🔲 |
| `admin.bookings.index` | Nayasha | Tabel daftar booking | ✅ |
| `admin.bookings.show` | Nayasha | Detail booking | ✅ |
| `admin.pembayaran.index` | Nayasha | Tabel daftar pembayaran | ✅ |
| `admin.pembayaran.show` | Nayasha | Detail pembayaran + aksi verify/reject (split pane) | ✅ |
| `admin.drivers.index` | Nayasha | Tabel daftar driver | ✅ |
| `admin.drivers.create` | Nayasha | Form tambah driver (pilih armada - CRUD modal) | ✅ |
| `admin.drivers.show` | Nayasha | Detail driver (CRUD modal) | ✅ |
| `admin.drivers.edit` | Nayasha | Form edit driver (CRUD modal) | ✅ |
| `admin.trips.index` | Kevin | Tabel daftar trip | ✅ |
| `admin.trips.create` | Kevin | Form buat trip + pilih driver & armada | ✅ |
| `admin.trips.show` | Kevin | Detail trip + manifest + assign booking | ✅ |

### Driver Views — `Kevin`

| View | Keterangan | Status |
|------|------------|:------:|
| `driver.dashboard` | Dashboard trip hari ini + total penumpang | ⚠️ Placeholder |
| `driver.trips.index` | Riwayat trip | 🔲 |
| `driver.trips.show` | Detail trip + manifest + aksi pickup/dropoff + konfirmasi pelunasan | 🔲 |
