# Singgalang Jaya Travel — AI Agent Rules

> Dokumen ini berisi aturan yang WAJIB diikuti oleh semua AI agent (Copilot, Cursor, Antigravity, dll) saat mengerjakan project ini. Tujuannya agar seluruh development konsisten antar anggota tim.

---

## 0. Tech Stack Yang Sudah Terpasang

> ⚠️ JANGAN install ulang atau ganti package berikut. Sudah terpasang dan terkonfigurasi.

| Package | Versi | Status |
|---------|-------|--------|
| Laravel | 13.x | ✅ |
| Livewire | 4.3 | ✅ |
| Laravel Breeze (Blade) | 2.4 | ✅ |
| Alpine.js | 3.x | ✅ |
| TailwindCSS | 3.x | ✅ |
| @tailwindcss/forms | 0.5.x | ✅ |
| Vite | 8.x | ✅ |
| Axios | 1.16.x | ✅ |

---

## 1. Bahasa

| Aspek | Bahasa | Contoh |
|-------|--------|--------|
| Kode (variabel, function, class) | **English** | `$totalPrice`, `getAvailableSchedules()` |
| Nama tabel & kolom database | **Bahasa Indonesia** (sesuai ERD) | `pelanggan`, `jadwal`, `kode_booking`, `armada` |
| Nama kolom tabel users | **English** (Breeze default) | `name`, `email`, `password`, `role` |
| Route URI | **Bahasa Indonesia** untuk resource utama | `/admin/jadwal`, `/admin/armada`, `/admin/pembayaran` |
| Route name | **English pattern** | `admin.jadwal.index`, `admin.armada.index`, `driver.trips.show` |
| UI Label & Text | **Bahasa Indonesia** | "Nama Lengkap", "Kode Booking" |
| Komentar kode | **English** | `// Check if schedule has available quota` |
| Commit message | **English** | `feat: add booking form with map picker` |
| Validation message | **Bahasa Indonesia** | `'nama.required' => 'Nama wajib diisi.'` |

---

## 2. Struktur File & Folder

### Controllers

```
app/Http/Controllers/
├── Auth/                              ← ✅ SUDAH ADA (Breeze)
│   ├── AuthenticatedSessionController.php
│   ├── ConfirmablePasswordController.php
│   ├── EmailVerificationNotificationController.php
│   ├── EmailVerificationPromptController.php
│   ├── NewPasswordController.php
│   ├── PasswordController.php
│   ├── PasswordResetLinkController.php
│   ├── RegisteredUserController.php
│   └── VerifyEmailController.php
├── Admin/                             ← ✅ SUDAH ADA
│   ├── DashboardController.php        ← ✅ SUDAH ADA
│   ├── RuteController.php             ← ✅ SUDAH ADA
│   ├── ArmadaController.php           ← ✅ SUDAH ADA
│   ├── JadwalController.php           ← ✅ SUDAH ADA
│   ├── BookingController.php          ← ✅ SUDAH ADA
│   ├── PembayaranController.php       ← ✅ SUDAH ADA
│   ├── DriverController.php           ← ✅ SUDAH ADA
│   ├── TripController.php             ← ✅ SUDAH ADA
│   └── LaporanController.php          ← 🔲 BUAT BARU
├── Driver/                            ← 🔲 BUAT BARU
│   ├── DashboardController.php        ← 🔲 BUAT BARU
│   └── TripController.php             ← 🔲 BUAT BARU

├── Controller.php                     ← ✅ SUDAH ADA
├── ProfileController.php              ← ✅ SUDAH ADA (Disesuaikan Per Role)
├── HomeController.php                 ← ✅ SUDAH ADA
├── BookingController.php              ← ✅ SUDAH ADA
├── PembayaranController.php           ← ✅ SUDAH ADA
├── CekBookingController.php           ← ✅ SUDAH ADA
└── JadwalPublicController.php         ← ✅ SUDAH ADA
```

### Services

```
app/Services/
├── BookingService.php                  ← ✅ SUDAH ADA
├── FonnteService.php                   ← ✅ SUDAH ADA (WhatsApp API)
├── BookingWhatsappNotificationService.php ← ✅ SUDAH ADA
├── PaymentVerificationService.php      ← 🔲 BUAT BARU
├── TripAssignmentService.php           ← 🔲 BUAT BARU
└── DriverTripService.php               ← 🔲 BUAT BARU
```

### Console Commands

```
app/Console/Commands/
└── SendDepartureConfirmation.php       ← 🔲 BUAT BARU (WA konfirmasi pagi hari)
```

### Livewire Components

```
app/Livewire/                          ← ✅ DIRECTORY ADA
├── BookingForm.php                    ← ✅ SUDAH ADA
├── Admin/
│   ├── BookingTable.php               ← ✅ SUDAH ADA
│   ├── PembayaranTable.php            ← ✅ SUDAH ADA
│   ├── ArmadaTable.php                ← 🔲 Optional: tabel dengan search/filter
│   ├── JadwalTable.php                ← 🔲 Optional
│   ├── DriverTable.php                ← 🔲 Optional
│   └── TripTable.php                  ← 🔲 Optional
└── Driver/
    └── TripManifest.php               ← 🔲 Optional: manifest interaktif
```

### Views (Blade)

```
resources/views/
├── layouts/
│   ├── public.blade.php               ← ✅ SUDAH ADA (Layout utama Public)
│   ├── admin.blade.php                ← ✅ SUDAH ADA (Layout utama Admin)
│   ├── driver.blade.php               ← ✅ SUDAH ADA (Layout utama Driver)
│   ├── guest.blade.php                ← ✅ SUDAH ADA (Breeze default guest layout)
│   ├── app.blade.php                  ← ⚠️ LEGACY / BREEZE DEFAULT (JANGAN GUNAKAN)
│   ├── navigation.blade.php           ← ⚠️ LEGACY / BREEZE DEFAULT (JANGAN GUNAKAN)
│   └── partials/                      ← 🔲 Rencana dipindahkan dari layouts/public
│       ├── public-navbar.blade.php
│       └── public-footer.blade.php
├── components/                        ← ✅ Reusable UI elements
│   ├── sidebar-admin.blade.php        ← ✅ SUDAH ADA
│   ├── sidebar-driver.blade.php       ← ✅ SUDAH ADA
│   ├── status-badge.blade.php         ← ✅ SUDAH ADA
│   ├── alert.blade.php                ← ✅ SUDAH ADA
│   ├── card.blade.php                 ← ✅ SUDAH ADA
│   ├── map-picker.blade.php           ← ✅ SUDAH ADA (Leaflet picker)
│   ├── map-viewer.blade.php           ← 🔲 BUAT BARU (Leaflet viewer)
│   └── [Breeze Components...]         ← ✅ 13 komponen Breeze
├── livewire/                          ← ✅ DIRECTORY ADA
├── auth/                              ← ✅ SUDAH ADA (6 views Breeze)
├── profile/                           ← ✅ SUDAH ADA (Custom role views)
│   ├── admin-edit.blade.php           ← ✅ SUDAH ADA
│   ├── driver-edit.blade.php          ← ✅ SUDAH ADA
│   ├── edit.blade.php                 ← ✅ SUDAH ADA
│   ├── public-edit.blade.php          ← ✅ SUDAH ADA
│   └── partials/                      ← ✅ SUDAH ADA
│       ├── delete-user-form.blade.php
│       ├── profile-page-content.blade.php
│       ├── update-password-form.blade.php
│       └── update-profile-information-form.blade.php
├── public/                            ← Halaman Pelanggan (Guest)
│   ├── home.blade.php                 ← ✅ SUDAH ADA (Landing Page)
│   ├── jadwal/
│   │   └── index.blade.php            ← ✅ SUDAH ADA (Daftar jadwal)
│   ├── booking/
│   │   ├── create.blade.php           ← ✅ SUDAH ADA
│   │   ├── review.blade.php           ← ✅ SUDAH ADA
│   │   ├── index.blade.php            ← ✅ SUDAH ADA (Booking Saya)
│   │   └── show.blade.php             ← ✅ SUDAH ADA (Detail Booking)
│   ├── pembayaran/
│   │   └── show.blade.php             ← ✅ SUDAH ADA
│   └── cek-booking/
│       ├── index.blade.php            ← ✅ SUDAH ADA
│       └── show.blade.php             ← ✅ SUDAH ADA
├── admin/                             ← Halaman Admin
│   ├── dashboard.blade.php            ← ✅ SUDAH ADA (Dashboard panel)
│   ├── rute/
│   │   ├── index.blade.php            ← ✅ SUDAH ADA
│   │   ├── create.blade.php           ← ✅ SUDAH ADA
│   │   └── edit.blade.php             ← ✅ SUDAH ADA
│   ├── armada/
│   │   └── index.blade.php            ← ✅ SUDAH ADA (modal CRUD)
│   ├── jadwal/
│   │   ├── index.blade.php            ← ✅ SUDAH ADA
│   │   ├── create.blade.php           ← ✅ SUDAH ADA
│   │   └── edit.blade.php             ← ✅ SUDAH ADA
│   ├── bookings/
│   │   ├── index.blade.php            ← ✅ SUDAH ADA
│   │   └── show.blade.php             ← ✅ SUDAH ADA
│   ├── pembayaran/
│   │   ├── index.blade.php            ← ✅ SUDAH ADA
│   │   └── show.blade.php             ← 🔲 BUAT BARU
│   ├── drivers/
│   │   └── index.blade.php            ← ✅ SUDAH ADA (modal CRUD)
│   ├── trips/
│   │   ├── index.blade.php            ← ✅ SUDAH ADA
│   │   ├── create.blade.php           ← ✅ SUDAH ADA
│   │   └── show.blade.php             ← ✅ SUDAH ADA
│   └── laporan/
│       └── index.blade.php            ← 🔲 BUAT BARU
├── driver/                            ← Halaman Driver
│   ├── dashboard.blade.php            ← ✅ SUDAH ADA (Dashboard panel)
│   └── trips/
│       ├── index.blade.php            ← 🔲 BUAT BARU
│       └── show.blade.php             ← 🔲 BUAT BARU
├── dashboard.blade.php                ← ❌ AKAN DIHAPUS (Breeze default)
└── welcome.blade.php                  ← ❌ AKAN DIHAPUS (Laravel default)
```

### Models

```
app/Models/
├── User.php                           ← ✅ SUDAH ADA
├── Armada.php                         ← ✅ SUDAH ADA
├── Driver.php                         ← ✅ SUDAH ADA
├── Rute.php                           ← ✅ SUDAH ADA
├── Pelanggan.php                      ← ✅ SUDAH ADA
├── Jadwal.php                         ← ✅ SUDAH ADA
├── Booking.php                        ← ✅ SUDAH ADA
├── Pembayaran.php                     ← ✅ SUDAH ADA
├── Trip.php                           ← ✅ SUDAH ADA
├── DetailTrip.php                     ← ✅ SUDAH ADA
└── WhatsappNotification.php            ← ✅ SUDAH ADA
```

### Middleware

```
app/Http/Middleware/
└── RoleMiddleware.php                 ← ✅ SUDAH ADA
```

---

## 3. Existing Code — JANGAN UBAH

File berikut sudah ada dan berfungsi. **Jangan modify kecuali ada alasan kuat:**

| File | Alasan |
|------|--------|
| `bootstrap/app.php` | RoleMiddleware sudah terdaftar: `'role' => RoleMiddleware::class` |
| `app/Http/Middleware/RoleMiddleware.php` | Cek `...$roles` dari `in_array($request->user()->role, $roles)` |
| `app/Models/User.php` | Pakai `#[Fillable]` attribute. Field = `name`, `email`, `password`, `role`. Role = `admin`, `driver`, `pelanggan` |
| `routes/auth.php` | Breeze auth routes lengkap |
| `app/Http/Controllers/Auth/*` | 9 controller Breeze |
| `app/Http/Controllers/ProfileController.php` | Profile edit/update/destroy |
| `resources/views/auth/*` | 6 auth views |
| `resources/views/layouts/app.blade.php` | Layout slot-based `{{ $slot }}` |
| `resources/views/layouts/guest.blade.php` | Layout guest |
| `resources/views/components/*` | 13 Breeze components |
| `database/seeders/UserSeeder.php` | Admin + Driver seed |

---

## 4. Auth Pattern (Sudah Implementasi)

### Login Redirect by Role

```php
// Di AuthenticatedSessionController@store:
if ($user->role === 'admin') {
    return redirect()->intended(route('admin.dashboard'));
}
if ($user->role === 'driver') {
    return redirect()->intended(route('driver.dashboard'));
}
// Pelanggan (default) → redirect ke home atau booking
return redirect()->intended(route('home'));
```

### Route Group Pattern

```php
// Admin routes — sudah ada di web.php
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function() {
         // tambah route di sini
     });

// Driver routes — sudah ada di web.php
Route::middleware(['auth', 'role:driver'])
     ->prefix('driver')
     ->name('driver.')
     ->group(function() {
         // tambah route di sini
     });

// Pelanggan routes — booking memerlukan auth
Route::middleware(['auth', 'role:pelanggan'])
     ->group(function() {
         // Route booking, pembayaran, booking-saya di sini
     });
```

> ⚠️ Saat menambah route admin/driver, tambahkan di dalam group yang sudah ada. **Jangan buat group baru.**
> ⚠️ Booking routes memerlukan middleware `auth` karena pelanggan wajib login.

---

## 5. Naming Conventions

### Route Naming

```php
// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal', [JadwalPublicController::class, 'index'])->name('jadwal.index');

// Admin — pakai resource atau manual di dalam group yang sudah ada:
Route::resource('jadwal', Admin\JadwalController::class);
// → admin.jadwal.index, admin.jadwal.create, admin.jadwal.store, dll

Route::resource('armada', Admin\ArmadaController::class);
// → admin.armada.index, admin.armada.create, admin.armada.store, dll

// Driver — di dalam group yang sudah ada:
Route::get('trips', [Driver\TripController::class, 'index'])->name('trips.index');
```

### Controller Method Naming

| Method | Kegunaan | HTTP |
|--------|----------|------|
| `index` | List semua data | GET |
| `create` | Form tambah | GET |
| `store` | Simpan data baru | POST |
| `show` | Detail satu data | GET |
| `edit` | Form edit | GET |
| `update` | Update data | PUT/PATCH |
| `destroy` | Hapus data | DELETE |

Custom methods:

| Method | Kegunaan | HTTP |
|--------|----------|------|
| `verify` | Verifikasi pembayaran | PUT |
| `reject` | Tolak pembayaran | PUT |
| `cancel` | Batalkan booking | PUT |
| `toggleStatus` | Toggle jadwal aktif/nonaktif | PUT |
| `assignBooking` | Masukkan booking ke trip | POST |
| `removeBooking` | Keluarkan booking dari trip | DELETE |
| `start` | Mulai trip | PUT |
| `pickup` | Tandai dijemput | PUT |
| `dropoff` | Tandai diantar | PUT |
| `complete` | Selesaikan trip | PUT |
| `confirmPayment` | Konfirmasi pelunasan dari pelanggan | PUT |
| `export` | Export laporan | GET |

### Model Naming

| Model | Tabel | Override `$table` |
|-------|-------|:------------------:|
| `User` | `users` | Tidak perlu |
| `Armada` | `armada` | ✅ `$table = 'armada'` |
| `Driver` | `drivers` | Tidak perlu |
| `Rute` | `rute` | ✅ `$table = 'rute'` |
| `Pelanggan` | `pelanggan` | ✅ `$table = 'pelanggan'` |
| `Jadwal` | `jadwal` | ✅ `$table = 'jadwal'` |
| `Booking` | `bookings` | Tidak perlu |
| `Pembayaran` | `pembayaran` | ✅ `$table = 'pembayaran'` |
| `Trip` | `trips` | Tidak perlu |
| `DetailTrip` | `detail_trip` | ✅ `$table = 'detail_trip'` |

---

## 6. Livewire Rules

### Kapan Pakai Livewire vs Blade Biasa

| Gunakan Livewire | Gunakan Blade Biasa |
|------------------|---------------------|
| Tabel dengan search/filter/pagination realtime | Halaman statis (landing, detail view) |
| Form multi-step | Form CRUD sederhana |
| Auto-calculate (hitung tarif otomatis) | Form yang cuma submit + redirect |
| Interaksi tanpa full page reload | Halaman yang tidak butuh reactivity |
| Map picker dengan update alamat | Halaman info/read-only |

### Livewire Component Pattern

```php
// app/Livewire/Admin/BookingTable.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class BookingTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function render()
    {
        $bookings = Booking::query()
            ->when($this->search, fn($q) => $q->where('kode_booking', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status_booking', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.booking-table', compact('bookings'));
    }
}
```

### Livewire View Pattern

```blade
{{-- resources/views/livewire/admin/booking-table.blade.php --}}
<div>
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode booking...">
    {{-- table here --}}
</div>
```

### Embed Livewire di Blade Page

```blade
{{-- di views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <livewire:admin.booking-table />
@endsection
```

---

## 7. Blade Layout Standard

Project Singgalang Jaya Travel menggunakan Blade Template Inheritance sebagai standar utama.

Gunakan:

```blade
@extends('layouts.public')

@section('content')
@endsection
```

dan

```blade
@yield('content')
```

untuk layout halaman.

### Layout yang digunakan

```text
resources/views/layouts/public.blade.php
resources/views/layouts/admin.blade.php
resources/views/layouts/driver.blade.php
```

### Aturan

Untuk halaman utama:

Gunakan:

```blade
@extends(...)
@section(...)
@endsection
```

Jangan gunakan:

```blade
<x-public-layout>
    ...
</x-public-layout>
```

untuk layout utama halaman.

### Blade Component

Blade Component hanya digunakan untuk elemen reusable seperti:

* Navbar
* Footer
* Button
* Card
* Badge
* Modal
* Alert
* Table

Contoh:

```blade
<x-button />
<x-card />
```

Diperbolehkan.

Namun:

```blade
<x-public-layout />
<x-admin-layout />
<x-driver-layout />
```

Tidak digunakan pada project ini.

### Alasan

* Lebih mudah dipahami mahasiswa.
* Lebih umum digunakan pada tutorial Laravel.
* Lebih mudah dijelaskan saat sidang.
* Konsisten dengan Laravel Breeze.
* Mengurangi kompleksitas project.

### Saat AI membuat view baru

Gunakan pola:

```blade
@extends('layouts.public')

@section('content')

@endsection
```

atau

```blade
@extends('layouts.admin')

@section('content')

@endsection
```

atau

```blade
@extends('layouts.driver')

@section('content')

@endsection
```

sesuai kebutuhan.

### Custom Layout Reference with Blade Template Inheritance

#### Admin Layout (`layouts/admin.blade.php`):
```blade
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <div class="flex min-h-screen">
        <x-sidebar-admin />
        <main class="flex-1">
            @yield('content')
        </main>
    </div>
    @livewireScripts
</body>
</html>
```

#### Public Layout (`layouts/public.blade.php`):
Tidak perlu auth, tidak perlu sidebar.
```blade
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    @yield('content')
    @livewireScripts
</body>
</html>
```

### Penting: @livewireStyles & @livewireScripts

Semua layout HARUS include Livewire directives jika halaman menggunakan Livewire:

```blade
<head>
    @livewireStyles
</head>
<body>
    ...
    @livewireScripts
</body>
```

---

## 8. Controller Rules

```php
// WAJIB: Satu controller per resource
// WAJIB: Gunakan Form Request untuk validasi
// WAJIB: Gunakan Eloquent, bukan raw query
// WAJIB: Return redirect dengan flash message untuk POST/PUT/DELETE

// Contoh pattern:
public function store(StoreJadwalRequest $request)
{
    Jadwal::create($request->validated());
    return redirect()
        ->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil ditambahkan.');
}
```

### Form Request Location

```
app/Http/Requests/
├── Auth/
│   └── LoginRequest.php               ← ✅ SUDAH ADA (Breeze)
├── Admin/
│   ├── StoreRuteRequest.php
│   ├── UpdateRuteRequest.php
│   ├── StoreArmadaRequest.php
│   ├── UpdateArmadaRequest.php
│   ├── StoreJadwalRequest.php
│   ├── UpdateJadwalRequest.php
│   ├── StoreDriverRequest.php
│   ├── UpdateDriverRequest.php
│   ├── StoreTripRequest.php
│   └── AssignBookingRequest.php
├── StoreBookingRequest.php
└── StorePembayaranRequest.php
```

---

## 9. UI Design Rules

### Font

```css
font-family: 'Poppins', sans-serif;
```

> ⚠️ Breeze default pakai Figtree. Ganti ke Poppins di layout baru (admin, driver, public). Layout Breeze (`app.blade.php`, `guest.blade.php`) boleh tetap Figtree atau diganti — konsisten saja.

Load via Google Fonts di layout:

```html
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### Color Palette

```css
:root {
    --primary: #1E3A8A;
    --secondary: #2563EB;
    --background: #FFFFFF;
    --surface: #F8FAFC;
    --border: #E2E8F0;
    --success: #16A34A;
    --warning: #F59E0B;
    --danger: #DC2626;
}
```

### Tailwind Classes Standard

| Element | Classes |
|---------|---------|
| Card | `bg-white rounded-2xl border border-gray-200 shadow-sm p-6` |
| Primary Button | `bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl` |
| Secondary Button | `bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-xl` |
| Danger Button | `bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl` |
| Input | `w-full border border-gray-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500` |
| Label | `block text-sm font-medium text-gray-700 mb-1` |
| Table Header | `bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider` |
| Badge Success | `inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800` |
| Badge Warning | `inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800` |
| Badge Danger | `inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800` |
| Badge Info | `inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800` |

### DILARANG

- ❌ Glassmorphism
- ❌ Neon effects
- ❌ Heavy/dramatic shadows
- ❌ Gradient background berlebihan
- ❌ Redesign dari scratch
- ❌ Ubah urutan section landing page
- ❌ Skip step di booking flow

---

## 10. Status Values (ENUM)

### Booking Status

| Value | Label (ID) |
|-------|------------|
| `booking_dibuat` | Booking Dibuat |
| `menunggu_verifikasi` | Menunggu Verifikasi |
| `dikonfirmasi` | Dikonfirmasi |
| `assigned_to_trip` | Assigned To Trip |
| `on_trip` | On Trip |
| `completed` | Completed |
| `cancelled` | Cancelled |
| `expired` | Expired |

> ⚠️ **PERUBAHAN**: Status `menunggu_pembayaran` dihapus. Booking langsung berstatus `booking_dibuat` saat dibuat, pelanggan upload bukti DP untuk masuk ke `menunggu_verifikasi`.

### Payment Status

| Value | Label (ID) |
|-------|------------|
| `menunggu` | Menunggu |
| `terverifikasi` | Terverifikasi |
| `ditolak` | Ditolak |

### Trip Status

| Value | Label (ID) |
|-------|------------|
| `new` | New |
| `ready` | Ready |
| `on_trip` | On Trip |
| `completed` | Completed |
| `cancelled` | Cancelled |

### Schedule Status

| Value | Label (ID) |
|-------|------------|
| `aktif` | Aktif |
| `nonaktif` | Nonaktif |
| `penuh` | Penuh |

### Driver Status

| Value | Label (ID) |
|-------|------------|
| `aktif` | Aktif |
| `nonaktif` | Nonaktif |

### Armada Status

| Value | Label (ID) |
|-------|------------|
| `aktif` | Aktif |
| `nonaktif` | Nonaktif |

### Pickup Status

| Value | Label (ID) |
|-------|------------|
| `belum` | Belum Dijemput |
| `sudah_dijemput` | Sudah Dijemput |

### Dropoff Status

| Value | Label (ID) |
|-------|------------|
| `belum` | Belum Diantar |
| `sudah_diantar` | Sudah Diantar |

---

## 11. Kode Booking Format

```
SJT-{YYYYMMDD}-{RANDOM5}
```

Contoh: `SJT-20260605-A3X7K`

```php
$kode = 'SJT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
```

---

## 12. File Upload Rules

| Aspek | Aturan |
|-------|--------|
| Disk | `public` |
| Path | `bukti-pembayaran/` |
| Naming | `{kode_booking}_{timestamp}.{ext}` |
| Max Size | 2MB |
| Allowed Types | `jpg, jpeg, png, pdf` |
| Access | Via `Storage::url()` |

---

## 13. Flash Message Convention

```php
return redirect()->back()->with('success', 'Data berhasil disimpan.');
return redirect()->back()->with('error', 'Terjadi kesalahan.');
return redirect()->back()->with('warning', 'Perhatian: kuota hampir penuh.');
return redirect()->back()->with('info', 'Booking telah dimasukkan ke trip.');
```

---

## 14. Validation Messages (Bahasa Indonesia)

```php
public function messages(): array
{
    return [
        'nama.required' => 'Nama wajib diisi.',
        'no_hp.required' => 'Nomor HP wajib diisi.',
        'jadwal_id.required' => 'Jadwal keberangkatan wajib dipilih.',
        'armada_id.required' => 'Armada wajib dipilih.',
        'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
        'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB.',
    ];
}
```

---

## 15. Maps Integration (Leaflet)

```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

Default center: Padang Panjang `[-0.4669, 100.3986]`

**Keputusan Desain Map Picker**:
- Peta Leaflet hanya menampilkan dan menentukan pin **lokasi Jemput** (pickup) pelanggan.
- **Lokasi Antar/Tujuan** berupa input teks (tanpa interaksi peta).
- Koordinat Latitude dan Longitude disembunyikan (`hidden`) pada form input untuk kemudahan penggunaan.

---

## 16. WhatsApp Integration (FonnteAPI)

> ⚠️ WhatsApp link `wa.me` diganti dengan **FonnteAPI** untuk pengiriman pesan otomatis.

### Konfigurasi

```php
// .env
FONNTE_TOKEN=your_fonnte_api_token
FONNTE_COUNTRY_CODE=62
FONNTE_CONNECT_ONLY=false

// config/services.php
'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    'url' => env('FONNTE_URL', 'https://api.fonnte.com/send'),
    'country_code' => env('FONNTE_COUNTRY_CODE', '62'),
    'connect_only' => env('FONNTE_CONNECT_ONLY', false),
],
```

### FonnteService

```php
// app/Services/FonnteService.php
namespace App\Services;

use App\Models\WhatsappNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(string $target, string $message, string $type, ?int $bookingId = null): bool
    {
        // Normalisasi target (awalan 08 / +62 menjadi 628)
        // Kirim request ke FonnteAPI dengan form-data
        // Log notifikasi ke tabel whatsapp_notifications
    }
}
```

### WhatsApp Booking Notification Service

```php
// app/Services/BookingWhatsappNotificationService.php
namespace App\Services;

class BookingWhatsappNotificationService
{
    // Mengirim pesan template WhatsApp via FonnteService:
    // - sendDpVerifiedToCustomer (saat DP diverifikasi admin)
    // - sendTripAssignedToCustomer (saat booking dimasukkan ke trip)
    // - sendTripAssignedToDriver (notifikasi ke driver tentang penumpang baru)
}
```

### Use Cases

| Trigger | Pesan | Target |
|---------|-------|--------|
| Booking dibatalkan pelanggan | "Booking {kode} telah dibatalkan oleh pelanggan" | Admin + Driver (jika assigned) |
| Pagi hari sebelum keberangkatan | "Konfirmasi keberangkatan Anda hari ini..." | Pelanggan |

### Scheduler (Laravel)

```php
// app/Console/Kernel.php atau routes/console.php
// Konfirmasi ulang pagi hari sebelum keberangkatan
Schedule::command('booking:send-confirmation')->dailyAt('06:00');
```

---

## 17. Git Rules

### Branch Naming

```
feature/{modul}-{deskripsi}
```

### Commit Message

```
{type}: {description}
type = feat | fix | refactor | style | docs | test | chore
```

---

## 18. Responsive Breakpoints

| Breakpoint | Behavior |
|------------|----------|
| Mobile (< 768px) | Sidebar → hamburger drawer. Tables → horizontal scroll. |
| Tablet (768-1023px) | Sidebar → collapsed. 2-column grid. |
| Desktop (≥ 1024px) | Full sidebar. Multi-column layout. |

---

## 19. Task Isolation Rules (Pembagian Tim)

> ⚠️ **CRITICAL**: Saat user mengatakan "kerjakan sprint X bagian [NAMA]", AI **HANYA BOLEH** mengerjakan file milik orang tersebut. **JANGAN sentuh file milik anggota lain**.

### Cara Membaca Perintah

| Perintah User | Yang Dikerjakan |
|---------------|-----------------|
| "kerjakan sprint 0 bagian Rayhan" | HANYA file milik Rayhan di Sprint 0 |
| "kerjakan sprint 1 bagian Rayfo" | HANYA file milik Rayfo di Sprint 1 |
| "kerjakan sprint 0 semua" | Semua file Sprint 0 (semua anggota) |
| "kerjakan bagian Nayasha" | Semua task Nayasha di sprint yang sedang aktif |

### Ownership Per Anggota

#### Rayhan (RYH) — Customer Interface

**Controllers milik Rayhan:**
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/BookingController.php`
- `app/Http/Controllers/PembayaranController.php`
- `app/Http/Controllers/JadwalPublicController.php`


**Views milik Rayhan:**
- `resources/views/layouts/public.blade.php`
- `resources/views/public/**` (semua file di folder public)
- `resources/views/components/map-picker.blade.php`
- `resources/views/profile/public-edit.blade.php`

**Services milik Rayhan:**
- `app/Services/BookingService.php`
- `app/Services/FonnteService.php`
- `app/Services/BookingWhatsappNotificationService.php`

**Livewire milik Rayhan:**
- `app/Livewire/BookingForm.php`
- `resources/views/livewire/booking-form.blade.php`

**Migrations milik Rayhan:**
- `create_pelanggan_table`
- `create_bookings_table`
- `create_pembayaran_table`

**Models milik Rayhan:**
- `app/Models/Pelanggan.php`
- `app/Models/Booking.php`
- `app/Models/Pembayaran.php`

**Form Requests milik Rayhan:**
- `app/Http/Requests/StoreBookingRequest.php`
- `app/Http/Requests/StorePembayaranRequest.php`

**Routes milik Rayhan** (di `routes/web.php` — public section):
- `GET /` → `home`
- `GET /jadwal` → `jadwal.index`
- `GET /booking/create` → `booking.create`
- `POST /booking` → `booking.store`
- `GET /booking/{kode}/review` → `booking.review`
- `GET /booking/{kode}/pembayaran` → `booking.pembayaran`
- `POST /booking/{kode}/pembayaran` → `booking.pembayaran.store`
- `GET /booking-saya` → `booking.index`
- `GET /booking/{kode}` → `booking.show`
- `GET /jadwal/available` → `jadwal.available`
- `GET /jadwal/{id}/check-kuota` → `jadwal.checkKuota`

---

#### Rayfo (RYF) — Admin Core

**Controllers milik Rayfo:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/RuteController.php`
- `app/Http/Controllers/Admin/ArmadaController.php`
- `app/Http/Controllers/Admin/JadwalController.php`
- `app/Http/Controllers/Admin/LaporanController.php`

**Views milik Rayfo:**
- `resources/views/layouts/admin.blade.php`
- `resources/views/components/sidebar-admin.blade.php`
- `resources/views/components/card.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/rute/**`
- `resources/views/admin/armada/**`
- `resources/views/admin/jadwal/**`
- `resources/views/admin/laporan/**`

**Livewire milik Rayfo:**
- `app/Livewire/Admin/JadwalTable.php`
- `app/Livewire/Admin/ArmadaTable.php`
- `resources/views/livewire/admin/jadwal-table.blade.php`
- `resources/views/livewire/admin/armada-table.blade.php`

**Migrations milik Rayfo:**
- `create_armada_table`
- `create_rute_table`
- `create_jadwal_table`

**Models milik Rayfo:**
- `app/Models/Armada.php`
- `app/Models/Rute.php`
- `app/Models/Jadwal.php`

**Seeders milik Rayfo:**
- `database/seeders/RuteSeeder.php`
- `database/seeders/ArmadaSeeder.php`

**Form Requests milik Rayfo:**
- `app/Http/Requests/Admin/StoreRuteRequest.php`
- `app/Http/Requests/Admin/UpdateRuteRequest.php`
- `app/Http/Requests/Admin/StoreArmadaRequest.php`
- `app/Http/Requests/Admin/UpdateArmadaRequest.php`
- `app/Http/Requests/Admin/StoreJadwalRequest.php`
- `app/Http/Requests/Admin/UpdateJadwalRequest.php`

**Routes milik Rayfo** (di `routes/web.php` — admin group):
- `GET /admin/dashboard` → `admin.dashboard`
- Resource `admin/rute` → `admin.rute.*`
- Resource `admin/armada` → `admin.armada.*`
- Resource `admin/jadwal` → `admin.jadwal.*`
- `PUT /admin/jadwal/{id}/toggle` → `admin.jadwal.toggle`
- `GET /admin/laporan` → `admin.laporan.index`
- `GET /admin/laporan/export` → `admin.laporan.export`

---

#### Nayasha (NYS) — Auth + Admin Operasional

**Controllers milik Nayasha:**
- `app/Http/Controllers/Admin/BookingController.php`
- `app/Http/Controllers/Admin/PembayaranController.php`
- `app/Http/Controllers/Admin/DriverController.php`

**Views milik Nayasha:**
- `resources/views/components/status-badge.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/admin/bookings/**`
- `resources/views/admin/pembayaran/**`
- `resources/views/admin/drivers/**`
- `resources/views/profile/admin-edit.blade.php`
- `resources/views/profile/driver-edit.blade.php`
- `resources/views/profile/partials/profile-page-content.blade.php`

**Livewire milik Nayasha:**
- `app/Livewire/Admin/BookingTable.php`
- `app/Livewire/Admin/PembayaranTable.php`
- `app/Livewire/Admin/DriverTable.php`
- `resources/views/livewire/admin/booking-table.blade.php`
- `resources/views/livewire/admin/pembayaran-table.blade.php`
- `resources/views/livewire/admin/driver-table.blade.php`

**Migrations milik Nayasha:**
- `create_drivers_table`

**Models milik Nayasha:**
- `app/Models/Driver.php`

**Seeders milik Nayasha:**
- `database/seeders/DriverSeeder.php`

**Form Requests milik Nayasha:**
- `app/Http/Requests/Admin/StoreDriverRequest.php`
- `app/Http/Requests/Admin/UpdateDriverRequest.php`

**Routes milik Nayasha** (di `routes/web.php` — admin group):
- `GET /admin/bookings` → `admin.bookings.index`
- `GET /admin/bookings/{id}` → `admin.bookings.show`
- `PUT /admin/bookings/{id}/cancel` → `admin.bookings.cancel`
- `GET /admin/pembayaran` → `admin.pembayaran.index`
- `GET /admin/pembayaran/{id}` → `admin.pembayaran.show`
- `PUT /admin/pembayaran/{id}/verify` → `admin.pembayaran.verify`
- `PUT /admin/pembayaran/{id}/reject` → `admin.pembayaran.reject`
- Resource `admin/drivers` → `admin.drivers.*`

> Auth sudah selesai (Breeze). Nayasha **TIDAK perlu** sentuh file di `Auth/`, `routes/auth.php`, `resources/views/auth/`.

---

#### Kevin (KVN) — Trip & Driver Operations

**Controllers milik Kevin:**
- `app/Http/Controllers/Admin/TripController.php`
- `app/Http/Controllers/Driver/DashboardController.php`
- `app/Http/Controllers/Driver/TripController.php`

**Views milik Kevin:**
- `resources/views/layouts/driver.blade.php`
- `resources/views/components/sidebar-driver.blade.php`
- `resources/views/components/map-viewer.blade.php`
- `resources/views/admin/trips/**`
- `resources/views/driver/**` (semua file di folder driver)

**Livewire milik Kevin:**
- `app/Livewire/Admin/TripTable.php`
- `app/Livewire/Driver/TripManifest.php`
- `resources/views/livewire/admin/trip-table.blade.php`
- `resources/views/livewire/driver/trip-manifest.blade.php`

**Migrations milik Kevin:**
- `create_trips_table`
- `create_detail_trip_table`

**Models milik Kevin:**
- `app/Models/Trip.php`
- `app/Models/DetailTrip.php`

**Form Requests milik Kevin:**
- `app/Http/Requests/Admin/StoreTripRequest.php`
- `app/Http/Requests/Admin/AssignBookingRequest.php`

**Routes milik Kevin** (di `routes/web.php`):

Admin group:
- `GET /admin/trips` → `admin.trips.index`
- `GET /admin/trips/create` → `admin.trips.create`
- `POST /admin/trips` → `admin.trips.store`
- `GET /admin/trips/{id}` → `admin.trips.show`
- `PUT /admin/trips/{id}` → `admin.trips.update`
- `POST /admin/trips/{id}/assign` → `admin.trips.assign`
- `DELETE /admin/trips/{id}/remove/{detailId}` → `admin.trips.remove`
- `DELETE /admin/trips/{id}` → `admin.trips.destroy`

Driver group:
- `GET /driver/dashboard` → `driver.dashboard`
- `GET /driver/trips` → `driver.trips.index`
- `GET /driver/trips/{id}` → `driver.trips.show`
- `PUT /driver/trips/{id}/start` → `driver.trips.start`
- `PUT /driver/trips/{id}/pickup/{detailId}` → `driver.trips.pickup`
- `PUT /driver/trips/{id}/dropoff/{detailId}` → `driver.trips.dropoff`
- `PUT /driver/trips/{id}/complete` → `driver.trips.complete`
- `PUT /driver/trips/{id}/confirm-payment/{detailId}` → `driver.trips.confirmPayment`

---

### Shared Files (Semua Boleh Edit)

File berikut boleh diedit oleh **siapa saja** karena bersifat shared:

| File | Keterangan |
|------|------------|
| `routes/web.php` | Tambah route di section masing-masing |
| `database/seeders/DatabaseSeeder.php` | Tambah seeder call |
| `app/Models/User.php` | Tambah relationship (hasOne Driver, hasOne Pelanggan) |
| `resources/css/app.css` | Custom CSS shared |
| `tailwind.config.js` | Config Tailwind shared |

> ⚠️ Saat edit shared file, **HANYA tambah bagian milik sendiri**. Jangan hapus/ubah bagian orang lain.

### routes/web.php — Section Markers

Gunakan comment markers untuk memisahkan route per anggota:

```php
// ============================================
// PUBLIC ROUTES — Rayhan
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
// ... route Rayhan lainnya

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function() {

    // --- Dashboard & Core (Rayfo) ---
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    // ... route Rayfo lainnya (Rute, Armada, Jadwal, Laporan)

    // --- Booking & Payment Management (Nayasha) ---
    // ... route Nayasha

    // --- Driver Management (Nayasha) ---
    // ... route Nayasha

    // --- Trip Management (Kevin) ---
    // ... route Kevin
});

// ============================================
// DRIVER ROUTES — Kevin
// ============================================
Route::middleware(['auth', 'role:driver'])
     ->prefix('driver')
     ->name('driver.')
     ->group(function() {
    // ... route Kevin
});
```

---

### Isolation Checklist

Sebelum commit, pastikan:

- [ ] Hanya file milik saya yang berubah
- [ ] Tidak menghapus/mengubah code milik anggota lain
- [ ] Route ditambah di section marker yang benar
- [ ] Shared files hanya ditambah, bukan diubah
- [ ] Model relationship yang ditambah tidak conflict

---

## 20. DO NOT

- ❌ Mengubah business flow yang sudah ditentukan
- ❌ Menambah fitur di luar scope tanpa persetujuan
- ❌ Menggunakan raw SQL query (gunakan Eloquent)
- ❌ Hardcode value (gunakan config/constant)
- ❌ Skip form validation
- ❌ Mengubah struktur tabel tanpa update `context/database.md`
- ❌ Membuat route tanpa name
- ❌ Menggunakan inline style (gunakan Tailwind)
- ❌ Menghapus komentar/docstring tanpa alasan
- ❌ Mengubah urutan section landing page
- ❌ Skip step di booking flow
- ❌ Mengubah format kode booking
- ❌ Mengubah status enum values
- ❌ Membuat controller untuk multiple resources
- ❌ Modify file Breeze yang sudah ada tanpa alasan kuat
- ❌ Buat route group admin/driver baru (pakai yang sudah ada di `web.php`)
- ❌ Install package baru tanpa diskusi tim
- ❌ Ganti User model field `name` jadi `nama`
- ❌ **Mengerjakan file milik anggota lain saat ditugaskan untuk satu anggota**
- ❌ **Menghapus/mengubah route section milik anggota lain di `web.php`**
- ❌ **Mengubah migration/model/controller milik anggota lain**
