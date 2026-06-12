# Singgalang Jaya Travel — UI Design Rules

> Aturan desain UI yang WAJIB diikuti. Referensi: Traveloka-inspired, clean, modern, professional.
> Tech: **TailwindCSS 3.x** + **@tailwindcss/forms** + **Poppins** font.

---

## Typography

| Elemen | Font Weight | Tailwind Class |
|--------|:-----------:|----------------|
| Heading (h1-h2) | 700 | `font-bold` |
| Section Title (h3-h4) | 600 | `font-semibold` |
| Body Text | 400-500 | `font-normal` / `font-medium` |
| Table Text | 400 | `font-normal` |
| Small/Caption | 400 | `text-sm font-normal` |

Font family: **Poppins** (semua layout baru: admin, driver, public)

```html
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

```css
font-family: 'Poppins', sans-serif;
```

---

## Color Palette

| Nama | Hex | Tailwind | Kegunaan |
|------|-----|----------|----------|
| Primary | `#1E3A8A` | `blue-800` | Button utama, sidebar, navbar |
| Secondary | `#2563EB` | `blue-600` | Link, accent, hover |
| Background | `#FFFFFF` | `white` | Body background |
| Surface | `#F8FAFC` | `slate-50` | Card bg, section bg |
| Border | `#E2E8F0` | `slate-200` | Card border, divider |
| Text Primary | `#1E293B` | `slate-800` | Body text |
| Text Secondary | `#64748B` | `slate-500` | Helper text, caption |
| Success | `#16A34A` | `green-600` | Verified, Active, Ready |
| Warning | `#F59E0B` | `amber-500` | Pending, Waiting |
| Danger | `#DC2626` | `red-600` | Rejected, Cancelled, Delete |
| Info | `#2563EB` | `blue-600` | Assigned, On Trip |

---

## Layout

| Property | Value | Tailwind Class |
|----------|-------|----------------|
| Max Width | 1280px | `max-w-7xl` |
| Container | Centered + padding | `mx-auto px-6 lg:px-8` |
| Section Spacing | 80px | `py-20` |
| Card Padding | 24px | `p-6` |
| Gap | 24px | `gap-6` |

---

## Card Design

```html
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <!-- Card content -->
</div>
```

| Property | Class |
|----------|-------|
| Background | `bg-white` |
| Border Radius | `rounded-2xl` |
| Border | `border border-slate-200` |
| Shadow | `shadow-sm` |
| Padding | `p-6` |

### ❌ DILARANG pada Card

- Glassmorphism (`backdrop-blur`, `bg-opacity`)
- Neon effects / glow
- Heavy shadows (`shadow-lg`, `shadow-xl`)
- Dark gradient backgrounds

---

## Button Design

### Primary Button

```html
<button class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
    Simpan
</button>
```

Digunakan untuk: **Booking, Save, Verify, Create, Confirm**

### Secondary Button

```html
<button class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-6 py-3 rounded-xl transition-colors">
    Kembali
</button>
```

Digunakan untuk: **Detail, Back, Cancel, Filter**

### Danger Button

```html
<button class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
    Hapus
</button>
```

Digunakan untuk: **Delete, Reject, Cancel Trip, Batalkan**

### Button with Icon

```html
<button class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl">
    <svg>...</svg>
    Tambah Jadwal
</button>
```

---

## Form Design

### Input Field

```html
<div class="mb-4">
    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
    <input type="text" class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    <p class="text-xs text-slate-500 mt-1">Masukkan nama sesuai KTP</p>
    @error('nama')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
```

| Property | Class |
|----------|-------|
| Label | `block text-sm font-medium text-slate-700 mb-1` |
| Input | `w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500` |
| Helper Text | `text-xs text-slate-500 mt-1` |
| Error Message | `text-red-500 text-sm mt-1` |

### Select

```html
<select class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500">
    <option value="">Pilih Jadwal</option>
</select>
```

### Textarea

```html
<textarea class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 resize-none" rows="3"></textarea>
```

### File Upload

```html
<input type="file" class="w-full border border-slate-300 rounded-xl px-4 py-3 file:mr-4 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium">
```

---

## Table Design

```html
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Search & Filter Bar -->
    <div class="p-4 border-b border-slate-200 flex gap-4">
        <input type="text" placeholder="Cari..." class="...">
        <select class="...">...</select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-700">SJT-20260605-A3X7K</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

| Property | Class |
|----------|-------|
| Container | `bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden` |
| Header Row | `bg-slate-50 border-b border-slate-200` |
| Header Cell | `px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider` |
| Body Divider | `divide-y divide-slate-100` |
| Body Row Hover | `hover:bg-slate-50 transition-colors` |
| Body Cell | `px-6 py-4 text-sm text-slate-700` |

### Livewire Table (Search/Filter)

```blade
<div>
    <input type="text" wire:model.live.debounce.300ms="search" class="...">
    <select wire:model.live="statusFilter" class="...">...</select>
</div>
```

---

## Status Badge

```html
<!-- Blade Component: x-status-badge -->
<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $classes }}">
    {{ $label }}
</span>
```

| Tipe | Values | Classes |
|------|--------|---------|
| **Success** | Terverifikasi, Aktif, Ready, Completed, Sudah Dijemput, Sudah Diantar | `bg-green-100 text-green-800` |
| **Warning** | Booking Dibuat, Menunggu Pembayaran, Menunggu Verifikasi, Menunggu | `bg-yellow-100 text-yellow-800` |
| **Danger** | Ditolak, Cancelled, Expired, Nonaktif | `bg-red-100 text-red-800` |
| **Info** | Dikonfirmasi, Assigned To Trip, On Trip | `bg-blue-100 text-blue-800` |
| **Neutral** | New, Belum (jemput/antar) | `bg-slate-100 text-slate-600` |

---

## Alert / Flash Message

```html
<!-- Success -->
<div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
    <svg>✓</svg>
    <p class="text-sm font-medium">{{ session('success') }}</p>
</div>

<!-- Error -->
<div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
    <svg>✕</svg>
    <p class="text-sm font-medium">{{ session('error') }}</p>
</div>

<!-- Warning -->
<div class="flex items-center gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">...</div>

<!-- Info -->
<div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800">...</div>
```

---

## Modal

```html
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-2">Konfirmasi Hapus</h3>
        <p class="text-sm text-slate-600 mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-end gap-3">
            <button class="bg-white border border-slate-300 ...">Batal</button>
            <button class="bg-red-600 ...">Hapus</button>
        </div>
    </div>
</div>
```

---

# Aturan Per Panel

## CUSTOMER (Public)

### Landing Page

- **Style**: Traveloka-inspired, clean, modern, professional
- **Max Width**: `max-w-7xl`
- **Sections (urutan WAJIB tetap)**:
  1. Hero
  2. Keunggulan
  3. Jadwal
  4. Armada & Driver
  5. Charter
  6. Kontak
  7. CTA
  8. Footer

> ❌ Jangan ubah urutan section

### Booking Flow (urutan WAJIB tetap)

```
Booking Form → Review Booking → Payment (timer 30 menit) → Upload Proof → Status Booking
```

> ❌ Jangan skip step
> ⏱️ Timer countdown 30 menit ditampilkan di halaman pembayaran DP.
> ✏️ Pelanggan bisa edit lokasi jemput (sebelum assigned ke trip).
> ❌ Pelanggan bisa cancel booking (sebelum on_trip). DP hangus.

---

## ADMIN

### Sidebar Menu

| # | Menu Item | Route Name | Icon |
|---|-----------|------------|------|
| 1 | Dashboard | `admin.dashboard` | Grid/Home |
| 2 | Rute | `admin.rute.index` | Map |
| 3 | Booking | `admin.bookings.index` | Clipboard |
| 4 | Pembayaran | `admin.pembayaran.index` | CreditCard |
| 5 | Jadwal | `admin.jadwal.index` | Calendar |
| 6 | Trip | `admin.trips.index` | Truck |
| 7 | Driver | `admin.drivers.index` | Users |
| 8 | Laporan | `admin.laporan.index` | BarChart |

### Sidebar Style

```html
<!-- Desktop: Fixed sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 bg-blue-900 text-white min-h-screen">
    <!-- Logo -->
    <!-- Menu Items -->
</aside>

<!-- Mobile: Hamburger → Drawer overlay -->
```

### Navbar (Top)

| Position | Element |
|----------|---------|
| Left | Hamburger (mobile) / breadcrumb |
| Right | Notification bell + Profile dropdown |

### Profile Dropdown

- Profile
- Change Password
- Logout

### Dashboard Widgets

| Widget | Keterangan |
|--------|------------|
| Total Booking | Count semua booking |
| Pending Verification | Count `menunggu_verifikasi` |
| Trip Aktif | Count trip `on_trip` |
| Pendapatan | Sum `total_harga` booking `completed` |
| Booking Expired | Count booking `expired` (hari ini) |

Widget style:
```html
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Total Booking</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">128</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600">...</svg>
        </div>
    </div>
</div>
```

---

## DRIVER

### Sidebar Menu

| # | Menu Item | Route Name |
|---|-----------|------------|
| 1 | Dashboard | `driver.dashboard` |
| 2 | Trip History | `driver.trips.index` |

### Navbar (Top)

| Position | Element |
|----------|---------|
| Right | Profile + Change Password + Logout |

### Dashboard Layout

```
┌──────────────────────────────────────────────┐
│ Active Trip Info                               │
├─────────────────────┬────────────────────────┤
│ Passenger List      │ Leaflet Map            │
│ (left, scrollable)  │ (right, interactive)   │
└─────────────────────┴────────────────────────┘
```

- **Left**: Daftar penumpang (manifest) — status jemput/antar
- **Right**: Leaflet map dengan marker titik jemput/antar

### Driver Flow (urutan WAJIB tetap)

```
Trip Ready → Start Trip → Pickup Mode → Delivery Mode → Complete Trip
```

> ❌ Jangan ubah flow ini

---

# Responsive Rules

| Breakpoint | Width | Tailwind Prefix |
|------------|-------|-----------------|
| Mobile | < 768px | default |
| Tablet | 768px - 1023px | `md:` |
| Desktop | ≥ 1024px | `lg:` |

### Responsive Behavior

| Element | Mobile | Tablet | Desktop |
|---------|--------|--------|---------|
| Sidebar | Hamburger drawer | Collapsed/mini | Full sidebar |
| Tables | Horizontal scroll | Horizontal scroll | Full width |
| Cards | Stack vertical | 2-column grid | Multi-column |
| Forms | Full width | Full width | Max-width constrained |
| Dashboard widgets | Stack vertical | 2-column | 4-column |
| Driver manifest | Full width (tabs) | Side-by-side | Side-by-side |

---

# FINAL RULES

### ✅ WAJIB

- Poppins font
- Navy blue palette (`blue-800` / `blue-900`)
- Rounded cards (`rounded-2xl`)
- Soft shadows (`shadow-sm`)
- Traveloka-inspired styling
- Production-ready appearance
- Konsisten antar Customer, Admin, dan Driver
- `transition-colors` pada semua interactive elements

### ❌ DILARANG

- Redesign dari scratch
- Ubah business logic
- Ubah page flow / section order
- Hapus existing features
- Glassmorphism / neon / heavy shadows
- Inline styles (pakai Tailwind classes)
- Warna generik tanpa konsistensi
