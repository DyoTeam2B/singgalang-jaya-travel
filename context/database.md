# Singgalang Jaya Travel — Database Schema

## ERD Diagram

```mermaid
erDiagram
    USERS ||--o| DRIVERS : "has"
    RUTE ||--|{ JADWAL : "has"
    JADWAL ||--|{ BOOKINGS : "has"
    JADWAL ||--|{ TRIPS : "has"
    PELANGGAN ||--|{ BOOKINGS : "has"
    BOOKINGS ||--|{ PEMBAYARAN : "has"
    BOOKINGS ||--|{ DETAIL_TRIP : "assigned"
    TRIPS ||--|{ DETAIL_TRIP : "contains"
    DRIVERS ||--|{ TRIPS : "assigned"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        timestamps timestamps
    }

    DRIVERS {
        bigint id PK
        bigint user_id FK
        string nama_driver
        string no_hp
        string nama_mobil
        string nomor_plat
        int kapasitas_mobil
        enum status_driver
        timestamps timestamps
    }

    RUTE {
        bigint id PK
        string asal
        string tujuan
        int tarif
        timestamps timestamps
    }

    PELANGGAN {
        bigint id PK
        string nama
        string no_hp
        timestamps timestamps
    }

    JADWAL {
        bigint id PK
        bigint rute_id FK
        date tanggal_keberangkatan
        enum shift
        time jam_berangkat
        int kuota
        enum status_jadwal
        timestamps timestamps
    }

    BOOKINGS {
        bigint id PK
        bigint pelanggan_id FK
        bigint jadwal_id FK
        string kode_booking UK
        string alamat_jemput
        decimal latitude_jemput
        decimal longitude_jemput
        string alamat_tujuan
        decimal latitude_tujuan
        decimal longitude_tujuan
        int jumlah_penumpang
        int total_harga
        enum status_booking
        timestamps timestamps
    }

    PEMBAYARAN {
        bigint id PK
        bigint booking_id FK
        enum jenis_pembayaran
        int jumlah_bayar
        string metode_pembayaran
        string bukti_pembayaran
        enum status_pembayaran
        text catatan
        timestamps timestamps
    }

    TRIPS {
        bigint id PK
        bigint jadwal_id FK
        bigint driver_id FK
        enum status_trip
        datetime started_at
        datetime completed_at
        timestamps timestamps
    }

    DETAIL_TRIP {
        bigint id PK
        bigint trip_id FK
        bigint booking_id FK
        enum status_jemput
        enum status_antar
        datetime picked_up_at
        datetime dropped_off_at
        timestamps timestamps
    }
```

---

## Detail Tabel

### 1. `users` — ✅ SUDAH ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `name` | VARCHAR(255) | NOT NULL | Nama user (Breeze default: `name` bukan `nama`) |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | Email login |
| `email_verified_at` | TIMESTAMP | NULLABLE | |
| `password` | VARCHAR(255) | NOT NULL | Hashed password |
| `role` | ENUM('admin', 'driver') | NOT NULL, DEFAULT 'driver' | Role user |
| `remember_token` | VARCHAR(100) | NULLABLE | |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: 
- `0001_01_01_000000_create_users_table.php` ✅
- `2026_05_10_134752_add_role_to_users_table.php` ✅

**Tabel terkait yang sudah ada**:
- `password_reset_tokens` ✅
- `sessions` ✅
- `cache` + `cache_locks` ✅
- `jobs` + `job_batches` + `failed_jobs` ✅

**Model**: `App\Models\User` ✅
```php
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
```

**Seeder**: `UserSeeder` ✅
- Admin: `admin@gmail.com` / `admin12345`
- Driver: `driver@gmail.com` / `driver12345`

> ⚠️ **PENTING**: User model pakai `name` (bukan `nama`). Ini default Breeze. Jangan ubah.

---

### 2. `drivers` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `user_id` | BIGINT UNSIGNED | FK → users.id, UNIQUE | Relasi 1:1 ke user |
| `nama_driver` | VARCHAR(255) | NOT NULL | Nama lengkap driver |
| `no_hp` | VARCHAR(20) | NOT NULL | Nomor HP |
| `nama_mobil` | VARCHAR(100) | NOT NULL | Merk/tipe kendaraan |
| `nomor_plat` | VARCHAR(20) | NOT NULL | Plat nomor kendaraan |
| `kapasitas_mobil` | INT UNSIGNED | NOT NULL, DEFAULT 5 | Kapasitas penumpang |
| `status_driver` | ENUM('aktif', 'nonaktif') | NOT NULL, DEFAULT 'aktif' | Status ketersediaan |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_drivers_table`

**Index**: `user_id` (UNIQUE)

**Catatan**: Data kendaraan melekat pada driver. Tidak ada tabel armada terpisah.

---

### 3. `rute` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `asal` | VARCHAR(255) | NOT NULL | Kota asal |
| `tujuan` | VARCHAR(255) | NOT NULL | Kota tujuan |
| `tarif` | INT UNSIGNED | NOT NULL | Tarif per penumpang (Rp) |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_rute_table`

**Data Awal (Seeder)**:
| Asal | Tujuan | Tarif |
|------|--------|-------|
| Padang Panjang | Pekanbaru | 150000 |
| Pekanbaru | Padang Panjang | 150000 |

---

### 4. `pelanggan` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `nama` | VARCHAR(255) | NOT NULL | Nama lengkap pelanggan |
| `no_hp` | VARCHAR(20) | NOT NULL | Nomor HP |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_pelanggan_table`

**Catatan**: Pelanggan tidak perlu login. Data dibuat otomatis saat booking.

---

### 5. `jadwal` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `rute_id` | BIGINT UNSIGNED | FK → rute.id | Relasi ke rute |
| `tanggal_keberangkatan` | DATE | NOT NULL | Tanggal berangkat |
| `shift` | ENUM('pagi', 'malam') | NOT NULL | Shift keberangkatan |
| `jam_berangkat` | TIME | NOT NULL | Jam berangkat |
| `kuota` | INT UNSIGNED | NOT NULL | Jumlah max penumpang per jadwal |
| `status_jadwal` | ENUM('aktif', 'nonaktif', 'penuh') | NOT NULL, DEFAULT 'aktif' | Status jadwal |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_jadwal_table`

**Index**: `rute_id`, `tanggal_keberangkatan`, `shift`

---

### 6. `bookings` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `pelanggan_id` | BIGINT UNSIGNED | FK → pelanggan.id | Relasi ke pelanggan |
| `jadwal_id` | BIGINT UNSIGNED | FK → jadwal.id | Relasi ke jadwal |
| `kode_booking` | VARCHAR(20) | NOT NULL, UNIQUE | Kode unik booking (auto-generated) |
| `alamat_jemput` | VARCHAR(500) | NOT NULL | Alamat penjemputan |
| `latitude_jemput` | DECIMAL(10,8) | NULLABLE | Lat penjemputan |
| `longitude_jemput` | DECIMAL(11,8) | NULLABLE | Lng penjemputan |
| `alamat_tujuan` | VARCHAR(500) | NOT NULL | Alamat tujuan |
| `latitude_tujuan` | DECIMAL(10,8) | NULLABLE | Lat tujuan |
| `longitude_tujuan` | DECIMAL(11,8) | NULLABLE | Lng tujuan |
| `jumlah_penumpang` | INT UNSIGNED | NOT NULL, DEFAULT 1 | Jumlah penumpang |
| `total_harga` | INT UNSIGNED | NOT NULL | Total harga (tarif × jumlah) |
| `status_booking` | ENUM(...) | NOT NULL, DEFAULT 'menunggu_pembayaran' | Status booking |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_bookings_table`

**Index**: `kode_booking` (UNIQUE), `pelanggan_id`, `jadwal_id`, `status_booking`

**Status Booking Values**:

| Value | Keterangan |
|-------|------------|
| `menunggu_pembayaran` | Booking baru, belum bayar DP |
| `menunggu_verifikasi` | DP sudah diupload, menunggu verifikasi admin |
| `dikonfirmasi` | DP terverifikasi oleh admin |
| `masuk_trip` | Booking sudah dimasukkan ke trip |
| `dalam_perjalanan` | Trip sedang berjalan |
| `selesai` | Trip selesai |
| `dibatalkan` | Booking dibatalkan |

**Format Kode Booking**: `SJT-{YYYYMMDD}-{RANDOM5}` contoh: `SJT-20260605-A3X7K`

---

### 7. `pembayaran` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `booking_id` | BIGINT UNSIGNED | FK → bookings.id | Relasi ke booking |
| `jenis_pembayaran` | ENUM('dp', 'pelunasan') | NOT NULL | Jenis pembayaran |
| `jumlah_bayar` | INT UNSIGNED | NOT NULL | Nominal pembayaran (Rp) |
| `metode_pembayaran` | VARCHAR(50) | NULLABLE | Transfer Bank / Cash |
| `bukti_pembayaran` | VARCHAR(255) | NULLABLE | Path file bukti upload |
| `status_pembayaran` | ENUM('menunggu', 'terverifikasi', 'ditolak') | NOT NULL, DEFAULT 'menunggu' | Status verifikasi |
| `catatan` | TEXT | NULLABLE | Catatan admin (alasan tolak, dll) |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_pembayaran_table`

**Index**: `booking_id`, `status_pembayaran`

**Catatan**: Satu booking bisa punya banyak pembayaran (jika DP ditolak → upload ulang).

---

### 8. `trips` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `jadwal_id` | BIGINT UNSIGNED | FK → jadwal.id | Relasi ke jadwal |
| `driver_id` | BIGINT UNSIGNED | FK → drivers.id | Relasi ke driver |
| `status_trip` | ENUM('ready', 'berjalan', 'selesai') | NOT NULL, DEFAULT 'ready' | Status trip |
| `started_at` | DATETIME | NULLABLE | Waktu mulai trip |
| `completed_at` | DATETIME | NULLABLE | Waktu selesai trip |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_trips_table`

**Index**: `jadwal_id`, `driver_id`, `status_trip`

---

### 9. `detail_trip` — 🔲 BELUM ADA

| Column | Type | Constraint | Keterangan |
|--------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| `trip_id` | BIGINT UNSIGNED | FK → trips.id, ON DELETE CASCADE | Relasi ke trip |
| `booking_id` | BIGINT UNSIGNED | FK → bookings.id | Relasi ke booking |
| `status_jemput` | ENUM('belum', 'sudah_dijemput') | NOT NULL, DEFAULT 'belum' | Status penjemputan |
| `status_antar` | ENUM('belum', 'sudah_diantar') | NOT NULL, DEFAULT 'belum' | Status pengantaran |
| `picked_up_at` | DATETIME | NULLABLE | Waktu dijemput |
| `dropped_off_at` | DATETIME | NULLABLE | Waktu diantar |
| `created_at` | TIMESTAMP | NULLABLE | |
| `updated_at` | TIMESTAMP | NULLABLE | |

**Migration**: `create_detail_trip_table`

**Index**: `trip_id`, `booking_id`

**Unique Constraint**: `(trip_id, booking_id)` — satu booking hanya bisa masuk satu trip sekali.

---

## Relasi Antar Tabel

| Relasi | Tipe | Keterangan |
|--------|------|------------|
| `users` → `drivers` | 1:1 | Satu user (role driver) punya satu data driver |
| `rute` → `jadwal` | 1:N | Satu rute punya banyak jadwal |
| `jadwal` → `bookings` | 1:N | Satu jadwal punya banyak booking |
| `jadwal` → `trips` | 1:N | Satu jadwal bisa punya banyak trip |
| `pelanggan` → `bookings` | 1:N | Satu pelanggan bisa punya banyak booking |
| `bookings` → `pembayaran` | 1:N | Satu booking bisa punya banyak pembayaran |
| `bookings` → `detail_trip` | 1:N | Satu booking bisa masuk banyak detail trip |
| `trips` → `detail_trip` | 1:N | Satu trip punya banyak detail trip |
| `drivers` → `trips` | 1:N | Satu driver bisa handle banyak trip |

---

## Migration Order

| Urutan | Migration | Tabel | Status |
|:------:|-----------|-------|--------|
| 1 | `create_users_table` | `users` | ✅ Sudah ada |
| 2 | `create_cache_table` | `cache`, `cache_locks` | ✅ Sudah ada |
| 3 | `create_jobs_table` | `jobs`, `job_batches`, `failed_jobs` | ✅ Sudah ada |
| 4 | `add_role_to_users_table` | `users` (alter) | ✅ Sudah ada |
| 5 | `create_drivers_table` | `drivers` | 🔲 |
| 6 | `create_rute_table` | `rute` | 🔲 |
| 7 | `create_pelanggan_table` | `pelanggan` | 🔲 |
| 8 | `create_jadwal_table` | `jadwal` | 🔲 |
| 9 | `create_bookings_table` | `bookings` | 🔲 |
| 10 | `create_pembayaran_table` | `pembayaran` | 🔲 |
| 11 | `create_trips_table` | `trips` | 🔲 |
| 12 | `create_detail_trip_table` | `detail_trip` | 🔲 |

---

## Seeder

| Seeder | Status | Keterangan |
|--------|--------|------------|
| `UserSeeder` | ✅ Sudah ada | Admin + Driver default |
| `RuteSeeder` | 🔲 | 2 rute utama (PP↔PKU) |
| `DriverSeeder` | 🔲 | 2-3 sample driver + update user seeder |
| `JadwalSeeder` | 🔲 | Sample jadwal untuk testing |
| `BookingSeeder` | 🔲 | Sample booking (optional, for dev) |

---

## Model Relationships (Eloquent)

```
User        → hasOne(Driver)                              ✅ Model ada, relationship belum
Driver      → belongsTo(User), hasMany(Trip)              🔲
Rute        → hasMany(Jadwal)                             🔲
Jadwal      → belongsTo(Rute), hasMany(Booking), hasMany(Trip)  🔲
Pelanggan   → hasMany(Booking)                            🔲
Booking     → belongsTo(Pelanggan), belongsTo(Jadwal), hasMany(Pembayaran), hasMany(DetailTrip)  🔲
Pembayaran  → belongsTo(Booking)                          🔲
Trip        → belongsTo(Jadwal), belongsTo(Driver), hasMany(DetailTrip)  🔲
DetailTrip  → belongsTo(Trip), belongsTo(Booking)         🔲
```
