# Class Diagram - Sistem Informasi Singgalang Jaya Travel

Diagram kelas (Class Diagram) berikut dihasilkan berdasarkan implementasi nyata struktur *Database* dan *Model Relations* (Eloquent) pada proyek Laravel Singgalang Jaya Travel.

```mermaid
classDiagram
    %% Definisi Kelas (Model) dan Atribut (Kolom)
    
    class User {
        +BigInt id
        +String name
        +String email
        +String password
        +Enum role
        +String remember_token
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Pelanggan {
        +BigInt id
        +BigInt user_id
        +String nama
        +String no_hp
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Driver {
        +BigInt id
        +BigInt user_id
        +BigInt armada_id
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Armada {
        +BigInt id
        +String nama_mobil
        +String nomor_plat
        +Integer kapasitas
        +Enum status_armada
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Rute {
        +BigInt id
        +String asal
        +String tujuan
        +Decimal tarif
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Jadwal {
        +BigInt id
        +BigInt rute_id
        +Date tanggal_keberangkatan
        +Enum shift
        +Time jam_berangkat
        +Integer kuota
        +Enum status_jadwal
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Booking {
        +BigInt id
        +BigInt pelanggan_id
        +BigInt jadwal_id
        +String kode_booking
        +String alamat_jemput
        +Decimal latitude_jemput
        +Decimal longitude_jemput
        +String alamat_tujuan
        +Decimal latitude_tujuan
        +Decimal longitude_tujuan
        +Integer jumlah_penumpang
        +Decimal total_harga
        +Enum status_booking
        +Text alasan_pembatalan
        +Timestamp expired_at
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Pembayaran {
        +BigInt id
        +BigInt booking_id
        +Enum jenis_pembayaran
        +Decimal jumlah_bayar
        +Enum metode_pembayaran
        +String bukti_pembayaran
        +Enum status_pembayaran
        +Text catatan
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Trip {
        +BigInt id
        +BigInt jadwal_id
        +BigInt driver_id
        +BigInt armada_id
        +Enum status_trip
        +Timestamp started_at
        +Timestamp completed_at
        +Timestamp created_at
        +Timestamp updated_at
    }

    class DetailTrip {
        +BigInt id
        +BigInt trip_id
        +BigInt booking_id
        +Enum status_jemput
        +Enum status_antar
        +Timestamp picked_up_at
        +Timestamp dropped_off_at
        +Timestamp created_at
        +Timestamp updated_at
    }

    class Rating {
        +BigInt id
        +BigInt booking_id
        +BigInt pelanggan_id
        +Integer rating
        +Text ulasan
        +Enum status
        +Timestamp created_at
        +Timestamp updated_at
    }

    class WhatsappNotification {
        +BigInt id
        +BigInt booking_id
        +String target
        +Text message
        +String type
        +Enum status
        +Text response
        +Timestamp created_at
        +Timestamp updated_at
    }

    %% Relasi Antar Kelas (Relationships)
    
    User "1" -- "0..1" Pelanggan : hasOne
    User "1" -- "0..1" Driver : hasOne
    Armada "1" -- "0..*" Driver : hasMany
    Rute "1" -- "0..*" Jadwal : hasMany
    
    Jadwal "1" -- "0..*" Booking : hasMany
    Jadwal "1" -- "0..*" Trip : hasMany
    Pelanggan "1" -- "0..*" Booking : hasMany
    
    Booking "1" -- "0..*" Pembayaran : hasMany
    Booking "1" -- "0..*" WhatsappNotification : hasMany
    Booking "1" -- "0..1" Rating : hasOne
    Pelanggan "1" -- "0..*" Rating : hasMany
    
    Driver "1" -- "0..*" Trip : hasMany
    Armada "1" -- "0..*" Trip : hasMany
    
    Trip "1" -- "0..*" DetailTrip : hasMany
    Booking "1" -- "0..*" DetailTrip : hasMany
```
