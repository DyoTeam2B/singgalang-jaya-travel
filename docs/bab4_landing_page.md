## 4.2.1 Implementasi Modul Landing Page

### 1. Nama Modul
Modul Landing Page (Beranda Publik)

### 2. Fungsi Utama
Modul ini berfungsi sebagai wajah utama (etalase) sistem informasi travel yang dapat diakses secara publik tanpa perlu login. Fungsi utamanya adalah menyajikan informasi real-time mengenai jadwal keberangkatan yang masih tersedia hari ini atau di masa depan, menampilkan profil armada dan driver, memamerkan ulasan positif pelanggan, serta menyajikan metrik statistik performa travel (seperti persentase ketepatan waktu dan total penumpang yang telah diberangkatkan). Modul ini juga menjadi titik masuk (*entry point*) bagi pelanggan untuk memulai proses booking.

### 3. Cara Kerja
Alur implementasi Modul Landing Page berjalan sebagai berikut:
- **Route:** Menggunakan HTTP `GET /` yang diarahkan ke method `index` pada `HomeController`.
- **Business Logic & Validasi:**
  1. **Filtrasi Jadwal Aktif:** Sistem mengambil data dari tabel `jadwal` yang berstatus `aktif`. Jadwal difilter secara ketat berdasarkan waktu (`tanggal_keberangkatan` dan `jam_berangkat`) agar hanya jadwal yang belum lewat waktu keberangkatannya (*future schedules*) yang ditampilkan. 
  2. **Pengecekan Trip:** Jadwal yang sudah masuk ke dalam fase operasional (tabel `trips` berstatus `on_trip` atau `completed`) otomatis disembunyikan.
  3. **Kalkulasi Sisa Kuota:** Sistem melakukan agregasi (`withSum`) terhadap jumlah penumpang dari relasi `bookings` (mengabaikan booking berstatus `cancelled` atau `expired`) untuk menentukan sisa kursi yang tersedia secara *real-time*.
  4. **Kalkulasi Statistik Otomatis:** Sistem membaca langsung dari database untuk menghitung total rute aktif, rata-rata rating (total bintang dibagi jumlah ulasan), total penumpang dari booking yang sukses, dan persentase ketepatan waktu berangkat (*on-time percentage*) dengan toleransi keterlambatan maksimal 3 jam dari jadwal.
- **Model yang Digunakan:** `Jadwal`, `Booking`, `Driver`, `Rating`, `Rute`, `Trip`.
- **View:** Controller me-return view `public.home` (dibangun menggunakan Blade, Tailwind CSS, dan Alpine.js).
- **Aksi Lanjutan:** Melalui komponen UI (*Call to Action*), pengguna dapat diarahkan ke halaman login, registrasi, cek status booking, atau langsung membuat booking (`BookingController@create`).

### 4. Keterkaitan Kebutuhan Fungsional
Modul ini secara langsung maupun tidak langsung memfasilitasi Use Case berikut:
- **UC-06 Melihat Jadwal Travel** (Fungsi utama modul, menampilkan jadwal beserta sisa kuota)
- **UC-01 Registrasi Pelanggan** (Akses via navigasi Header)
- **UC-02 Login Pelanggan** (Akses via navigasi Header)
- **UC-07 Melakukan Booking Travel** (Akses melalui tombol "Booking Sekarang")

### 5. Potongan Kode Penting

```php
// app/Http/Controllers/HomeController.php

public function index()
{
    $today = now()->toDateString();
    $currentTime = now()->toTimeString();

    // Mengambil jadwal aktif yang belum lewat waktu keberangkatannya
    $schedules = Jadwal::with('rute')
        ->withSum(['bookings as booked_seats' => function ($query) {
            $query->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
        }], 'jumlah_penumpang')
        ->aktif()
        ->whereDoesntHave('trips', function ($query) {
            $query->whereIn('status_trip', [\App\Models\Trip::STATUS_ON_TRIP, \App\Models\Trip::STATUS_COMPLETED]);
        })
        ->where(function ($q) use ($today, $currentTime) {
            $q->where('tanggal_keberangkatan', '>', $today)
              ->orWhere(function ($sq) use ($today, $currentTime) {
                  $sq->where('tanggal_keberangkatan', $today)
                     ->where('jam_berangkat', '>=', $currentTime);
              });
        })
        ->orderBy('tanggal_keberangkatan', 'asc')
        ->orderBy('jam_berangkat', 'asc')
        ->get();

    // ... (kode pengambilan rating dan kalkulasi statistik)

    return view('public.home', compact('schedules', /* ... */));
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Potongan kode di atas adalah *query builder* Eloquent yang sangat krusial. Fungsinya adalah mengambil daftar jadwal travel secara cerdas. Kode ini menggunakan `withSum` untuk menghitung total kursi yang sudah dibooking pada saat itu juga (*real-time availability*). 
- **Alasan Kode Penting:** Ini adalah "jantung" dari landing page. Query ini memastikan pelanggan tidak melihat jadwal yang sudah berangkat, jadwal yang kadaluarsa, atau jadwal yang sudah ditutup operasionalnya oleh admin (sudah dibuatkan *Trip*).
- **Hubungan dengan Proses Bisnis:** Mencegah terjadinya *overbooking* dan *miss-booking* (pesan tiket untuk travel yang sudah jalan). Menampilkan ketersediaan kursi secara langsung kepada publik meningkatkan transparansi layanan travel.

### 7. Screenshot yang Disarankan
Untuk melengkapi Bab Implementasi Laporan PBL, disarankan untuk melampirkan *screenshot* berikut:
1. **Hero Section Landing Page** (Menunjukkan antarmuka utama, headline, dan tombol Booking).
2. **Bagian Daftar Jadwal Keberangkatan** (Menunjukkan kartu-kartu jadwal travel beserta sisa kuota kursi yang dinamis).
3. **Bagian Metrik / Statistik Travel** (Menunjukkan angka persentase ketepatan waktu dan rata-rata rating).

### 8. Lokasi File
- `routes/web.php`
- `app/Http/Controllers/HomeController.php`
- `resources/views/public/home.blade.php`

### 9. Tingkat Kepentingan
★★★★ Penting 
*(Sangat esensial sebagai gerbang utama aplikasi bagi pelanggan dan publik, menampilkan informasi inti ketersediaan layanan travel).*
