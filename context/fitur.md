# Singgalang Jaya Travel — Daftar Fitur & Route Lengkap

## Tim Pengembang

| Nama | Peran | Area Tanggung Jawab |
|------|-------|---------------------|
| **Rayhan** | Developer | Customer Interface (Landing, Booking, Payment, Cek Status) |
| **Rayfo** | Developer | Admin Core (Dashboard, Rute, Jadwal, Laporan) |
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

## Daftar Route Lengkap

### A. PUBLIC / CUSTOMER ROUTES — `Rayhan`

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

> **Livewire option**: Form booking bisa pakai Livewire component `BookingForm` untuk reactivity (hitung tarif otomatis, map picker).

#### A4. Pembayaran DP (Customer)

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 6 | GET | `/booking/{kode}/pembayaran` | `booking.pembayaran` | `PembayaranController@show` | `public.pembayaran.show` |
| 7 | POST | `/booking/{kode}/pembayaran` | `booking.pembayaran.store` | `PembayaranController@store` | — (redirect) |

#### A5. Cek Status Booking

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 8 | GET | `/cek-booking` | `cek-booking.index` | `CekBookingController@index` | `public.cek-booking.index` |
| 9 | POST | `/cek-booking` | `cek-booking.show` | `CekBookingController@show` | `public.cek-booking.show` |

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

#### C3. Admin Jadwal Management — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 28 | GET | `/admin/jadwal` | `admin.jadwal.index` | `Admin\JadwalController@index` | `admin.jadwal.index` |
| 29 | GET | `/admin/jadwal/create` | `admin.jadwal.create` | `Admin\JadwalController@create` | `admin.jadwal.create` |
| 30 | POST | `/admin/jadwal` | `admin.jadwal.store` | `Admin\JadwalController@store` | — (redirect) |
| 31 | GET | `/admin/jadwal/{id}/edit` | `admin.jadwal.edit` | `Admin\JadwalController@edit` | `admin.jadwal.edit` |
| 32 | PUT | `/admin/jadwal/{id}` | `admin.jadwal.update` | `Admin\JadwalController@update` | — (redirect) |
| 33 | DELETE | `/admin/jadwal/{id}` | `admin.jadwal.destroy` | `Admin\JadwalController@destroy` | — (redirect) |
| 34 | PUT | `/admin/jadwal/{id}/toggle` | `admin.jadwal.toggle` | `Admin\JadwalController@toggleStatus` | — (redirect) |

#### C4. Admin Booking Management — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 35 | GET | `/admin/bookings` | `admin.bookings.index` | `Admin\BookingController@index` | `admin.bookings.index` |
| 36 | GET | `/admin/bookings/{id}` | `admin.bookings.show` | `Admin\BookingController@show` | `admin.bookings.show` |
| 37 | PUT | `/admin/bookings/{id}/cancel` | `admin.bookings.cancel` | `Admin\BookingController@cancel` | — (redirect) |

> **Livewire option**: Tabel booking bisa pakai Livewire `BookingTable` untuk search/filter/pagination realtime.

#### C5. Admin Pembayaran Verification — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 38 | GET | `/admin/pembayaran` | `admin.pembayaran.index` | `Admin\PembayaranController@index` | `admin.pembayaran.index` |
| 39 | GET | `/admin/pembayaran/{id}` | `admin.pembayaran.show` | `Admin\PembayaranController@show` | `admin.pembayaran.show` |
| 40 | PUT | `/admin/pembayaran/{id}/verify` | `admin.pembayaran.verify` | `Admin\PembayaranController@verify` | — (redirect) |
| 41 | PUT | `/admin/pembayaran/{id}/reject` | `admin.pembayaran.reject` | `Admin\PembayaranController@reject` | — (redirect) |

#### C6. Admin Driver Management — `Nayasha`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 42 | GET | `/admin/drivers` | `admin.drivers.index` | `Admin\DriverController@index` | `admin.drivers.index` |
| 43 | GET | `/admin/drivers/create` | `admin.drivers.create` | `Admin\DriverController@create` | `admin.drivers.create` |
| 44 | POST | `/admin/drivers` | `admin.drivers.store` | `Admin\DriverController@store` | — (redirect) |
| 45 | GET | `/admin/drivers/{id}` | `admin.drivers.show` | `Admin\DriverController@show` | `admin.drivers.show` |
| 46 | GET | `/admin/drivers/{id}/edit` | `admin.drivers.edit` | `Admin\DriverController@edit` | `admin.drivers.edit` |
| 47 | PUT | `/admin/drivers/{id}` | `admin.drivers.update` | `Admin\DriverController@update` | — (redirect) |
| 48 | DELETE | `/admin/drivers/{id}` | `admin.drivers.destroy` | `Admin\DriverController@destroy` | — (redirect) |

#### C7. Admin Trip Management — `Kevin`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 49 | GET | `/admin/trips` | `admin.trips.index` | `Admin\TripController@index` | `admin.trips.index` |
| 50 | GET | `/admin/trips/create` | `admin.trips.create` | `Admin\TripController@create` | `admin.trips.create` |
| 51 | POST | `/admin/trips` | `admin.trips.store` | `Admin\TripController@store` | — (redirect) |
| 52 | GET | `/admin/trips/{id}` | `admin.trips.show` | `Admin\TripController@show` | `admin.trips.show` |
| 53 | PUT | `/admin/trips/{id}` | `admin.trips.update` | `Admin\TripController@update` | — (redirect) |
| 54 | POST | `/admin/trips/{id}/assign` | `admin.trips.assign` | `Admin\TripController@assignBooking` | — (redirect) |
| 55 | DELETE | `/admin/trips/{id}/remove/{detailId}` | `admin.trips.remove` | `Admin\TripController@removeBooking` | — (redirect) |
| 56 | DELETE | `/admin/trips/{id}` | `admin.trips.destroy` | `Admin\TripController@destroy` | — (redirect) |

#### C8. Admin Laporan — `Rayfo`

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 57 | GET | `/admin/laporan` | `admin.laporan.index` | `Admin\LaporanController@index` | `admin.laporan.index` |
| 58 | GET | `/admin/laporan/export` | `admin.laporan.export` | `Admin\LaporanController@export` | — (download) |

> **Total Rayfo: 16 routes** (Dashboard + Rute + Jadwal + Laporan)
> **Total Nayasha: 16 routes** (Auth✅ + Booking Mgmt + Pembayaran + Driver)

---

### D. DRIVER ROUTES — Prefix: `/driver` — Middleware: `auth`, `role:driver` — `Kevin`

#### D1. Driver Dashboard

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 59 | GET | `/driver/dashboard` | `driver.dashboard` | `Driver\DashboardController@index` | `driver.dashboard` |

> **Status**: Route ada ✅, view placeholder ⚠️

#### D2. Driver Trip & Manifest

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 60 | GET | `/driver/trips` | `driver.trips.index` | `Driver\TripController@index` | `driver.trips.index` |
| 61 | GET | `/driver/trips/{id}` | `driver.trips.show` | `Driver\TripController@show` | `driver.trips.show` |

#### D3. Driver Trip Operations

| # | Method | URI | Name | Controller/Livewire | View |
|---|--------|-----|------|---------------------|------|
| 62 | PUT | `/driver/trips/{id}/start` | `driver.trips.start` | `Driver\TripController@start` | — (redirect) |
| 63 | PUT | `/driver/trips/{id}/pickup/{detailId}` | `driver.trips.pickup` | `Driver\TripController@pickup` | — (redirect) |
| 64 | PUT | `/driver/trips/{id}/dropoff/{detailId}` | `driver.trips.dropoff` | `Driver\TripController@dropoff` | — (redirect) |
| 65 | PUT | `/driver/trips/{id}/complete` | `driver.trips.complete` | `Driver\TripController@complete` | — (redirect) |

> **Total Kevin: 15 routes** (Admin Trip + Driver Panel)

---

## Ringkasan Pembagian Route

| Nama | Modul | Jumlah Route |
|------|-------|:------------:|
| **Rayhan** | Landing, Jadwal Public, Booking, Payment (Customer), Cek Status, API | **11** |
| **Rayfo** | Admin Dashboard, Admin Rute, Admin Jadwal, Admin Laporan | **16** |
| **Nayasha** | Auth (✅done), Admin Booking, Admin Pembayaran, Admin Driver | **16** |
| **Kevin** | Admin Trip, Driver Dashboard, Driver Trip & Operations | **15** |
| | **TOTAL** | **58** |

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

### Livewire Components (Perlu Dibuat)

| Component | PIC | Keterangan |
|-----------|-----|------------|
| `Livewire\BookingForm` | Rayhan | Form booking interaktif (hitung tarif, map) |
| `Livewire\Admin\BookingTable` | Nayasha | Tabel booking dengan search/filter |
| `Livewire\Admin\PembayaranTable` | Nayasha | Tabel pembayaran dengan filter |
| `Livewire\Admin\JadwalTable` | Rayfo | Tabel jadwal dengan filter |
| `Livewire\Admin\DriverTable` | Nayasha | Tabel driver dengan search |
| `Livewire\Admin\TripTable` | Kevin | Tabel trip dengan filter |
| `Livewire\Driver\TripManifest` | Kevin | Manifest penumpang interaktif |

> **Note**: Livewire components optional per fitur. Kalau fitur simple (CRUD form biasa), cukup pakai Blade + controller biasa. Gunakan Livewire untuk fitur yang butuh **reactivity tanpa full page reload** (search, filter, pagination, auto-calculate).

### Public Views — `Rayhan`

| View | Keterangan |
|------|------------|
| `public.home` | Landing page (Hero, Keunggulan, Jadwal, Armada, Charter, Kontak, CTA, Footer) |
| `public.jadwal.index` | Daftar jadwal keberangkatan |
| `public.booking.create` | Form booking |
| `public.booking.review` | Review booking sebelum submit |
| `public.pembayaran.show` | Halaman instruksi & upload DP |
| `public.cek-booking.index` | Form input kode booking |
| `public.cek-booking.show` | Detail status booking + timeline |

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

| View | PIC | Keterangan |
|------|-----|------------|
| `admin.dashboard` | Rayfo | Dashboard + widget statistik (placeholder ✅) |
| `admin.rute.index` | Rayfo | Tabel daftar rute |
| `admin.rute.create` | Rayfo | Form tambah rute |
| `admin.rute.edit` | Rayfo | Form edit rute |
| `admin.jadwal.index` | Rayfo | Tabel daftar jadwal |
| `admin.jadwal.create` | Rayfo | Form tambah jadwal |
| `admin.jadwal.edit` | Rayfo | Form edit jadwal |
| `admin.laporan.index` | Rayfo | Halaman laporan + filter |
| `admin.bookings.index` | Nayasha | Tabel daftar booking |
| `admin.bookings.show` | Nayasha | Detail booking |
| `admin.pembayaran.index` | Nayasha | Tabel daftar pembayaran |
| `admin.pembayaran.show` | Nayasha | Detail pembayaran + aksi verify/reject |
| `admin.drivers.index` | Nayasha | Tabel daftar driver |
| `admin.drivers.create` | Nayasha | Form tambah driver |
| `admin.drivers.show` | Nayasha | Detail driver |
| `admin.drivers.edit` | Nayasha | Form edit driver |
| `admin.trips.index` | Kevin | Tabel daftar trip |
| `admin.trips.create` | Kevin | Form buat trip + pilih driver |
| `admin.trips.show` | Kevin | Detail trip + manifest + assign booking |

### Driver Views — `Kevin`

| View | Keterangan |
|------|------------|
| `driver.dashboard` | Dashboard trip aktif + peta (placeholder ✅) |
| `driver.trips.index` | Daftar trip (history) |
| `driver.trips.show` | Detail trip + manifest + aksi pickup/dropoff |
