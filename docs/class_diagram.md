# Class Diagram - Sistem Informasi Singgalang Jaya Travel

Diagram kelas (Class Diagram) berikut menampilkan seluruh kelengkapan entitas sistem:
1. **Atribut Lengkap** (Semua field database termasuk Primary Key, Foreign Key, dan Timestamps).
2. **Operasi (Method)** menampilkan perilaku model dan fungsi pemanggilan relasi antar kelas.
3. **Relasi** menggunakan notasi UML yang sesuai (**Composition**, **Aggregation**, dan **Association**).

```mermaid
classDiagram
    %% ==========================================
    %% Definisi Kelas, Atribut, dan Operasi (Method)
    %% ==========================================
    
    class User {
        +BigInt id
        +String name
        +String email
        +Timestamp email_verified_at
        +String password
        +Enum role
        +String remember_token
        +Timestamp created_at
        +Timestamp updated_at
        +driver()
        +pelanggan()
    }

    class Pelanggan {
        +BigInt id
        +BigInt user_id
        +String nama
        +String no_hp
        +Timestamp created_at
        +Timestamp updated_at
        +user()
        +bookings()
    }

    class Driver {
        +BigInt id
        +BigInt user_id
        +String nama_driver
        +String no_hp
        +BigInt armada_id
        +Enum status_driver
        +Timestamp created_at
        +Timestamp updated_at
        +user()
        +armada()
        +trips()
        +getDynamicStatusAttribute()
    }

    class Armada {
        +BigInt id
        +String nama_mobil
        +String nomor_plat
        +Integer kapasitas
        +Enum status_armada
        +Timestamp created_at
        +Timestamp updated_at
        +driver()
        +trips()
    }

    class Rute {
        +BigInt id
        +String asal
        +String tujuan
        +Decimal tarif
        +Timestamp created_at
        +Timestamp updated_at
        +jadwal()
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
        +checkAndUpdateStatus()
        +getIsExpiredAttribute()
        +rute()
        +bookings()
        +trips()
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
        +DateTime expired_at
        +Timestamp created_at
        +Timestamp updated_at
        +isMenungguVerifikasi()
        +isDikonfirmasi()
        +isDibatalkan()
        +isExpired()
        +pelanggan()
        +jadwal()
        +pembayaran()
        +detailTrips()
        +whatsappNotifications()
        +rating()
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
        +isTerverifikasi()
        +booking()
    }

    class Trip {
        +BigInt id
        +BigInt jadwal_id
        +BigInt driver_id
        +BigInt armada_id
        +Enum status_trip
        +DateTime started_at
        +DateTime completed_at
        +Timestamp created_at
        +Timestamp updated_at
        +driver()
        +armada()
        +jadwal()
        +detailTrips()
    }

    class DetailTrip {
        +BigInt id
        +BigInt trip_id
        +BigInt booking_id
        +Enum status_jemput
        +Enum status_antar
        +DateTime picked_up_at
        +DateTime dropped_off_at
        +Timestamp created_at
        +Timestamp updated_at
        +trip()
        +booking()
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
        +booking()
        +pelanggan()
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
        +booking()
    }

    %% ==========================================
    %% Relasi (Association, Aggregation, Composition)
    %% ==========================================
    
    %% Composition: Pelanggan & Driver adalah bagian dari eksistensi entitas User. Jika User dihapus, mereka juga terhapus.
    User *-- Pelanggan : memiliki
    User *-- Driver : memiliki

    %% Aggregation: Armada dikemudikan oleh Driver, namun keduanya bisa berdiri sendiri jika tidak ada jadwal.
    Armada "1" o-- "0..*" Driver : ditugaskan ke

    %% Composition: Jadwal keberangkatan bergantung secara eksistensial pada master data Rute.
    Rute "1" *-- "0..*" Jadwal : memiliki rute

    %% Association & Aggregation: Booking melibatkan Pelanggan dan mengisi kapasitas Jadwal.
    Pelanggan "1" --> "0..*" Booking : melakukan
    Jadwal "1" o-- "0..*" Booking : memuat pesanan

    %% Composition: Transaksi Booking membawahi Pembayaran, Rating, dan log Whatsapp (siklus hidupnya terikat pada Booking).
    Booking "1" *-- "0..*" Pembayaran : memiliki rincian bayar
    Booking "1" *-- "0..*" WhatsappNotification : memicu log
    Booking "1" *-- "0..1" Rating : menerima
    Pelanggan "1" --> "0..*" Rating : memberikan umpan balik

    %% Aggregation: Trip terbentuk dengan menyatukan Jadwal, Driver, dan Armada yang ada.
    Jadwal "1" o-- "0..*" Trip : direalisasikan menjadi
    Driver "1" o-- "0..*" Trip : menjalankan
    Armada "1" o-- "0..*" Trip : digunakan dalam

    %% Composition: DetailTrip (Manifest Penumpang) hanya eksis sebagai penghubung fisik di dalam Trip dan Booking.
    Trip "1" *-- "0..*" DetailTrip : memuat manifest
    Booking "1" *-- "0..*" DetailTrip : terdaftar ke dalam kursi
```
