## 4.2.2 Implementasi Modul Authentication

### 1. Nama Modul
Modul Authentication (Autentikasi & Otorisasi Pengguna)

### 2. Fungsi Utama
Mengelola akses keamanan sistem melalui proses pendaftaran akun (registrasi), login, logout, dan manajemen sesi pengguna. Sistem membedakan arah (redirect) dan hak akses pengguna berdasarkan *role* (Admin, Pelanggan, atau Driver) setelah login berhasil.

### 3. Cara Kerja
- **Route:** `POST /register`, `POST /login`, `POST /logout` (Bawaan Laravel Breeze).
- **Business Logic:** Menggunakan *starter kit* Laravel Breeze. Saat login, request akan divalidasi. Jika kredensial cocok, sesi akan di-*regenerate*. Sistem lalu mengecek nilai enum `role` pada tabel `users` untuk menentukan *intended redirect* (Admin ke `/admin/dashboard`, Driver ke `/driver/dashboard`, Pelanggan ke halaman utama `/`).
- **Validasi:** `LoginRequest` memastikan format email valid dan sistem membatasi percobaan login yang gagal (*Rate Limiting*).
- **Model yang Digunakan:** `User`
- **Middleware:** `auth`, `guest`, `role:admin`, `role:pelanggan`, `role:driver`.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-01 Registrasi Pelanggan**
- **UC-02 Login Pelanggan**
- **UC-03 Login Admin**
- **UC-04 Login Driver**
- **UC-05 Logout**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = $request->user();

    // Role-based redirection
    if ($user->role === 'admin') {
        return redirect()->intended(route('admin.dashboard', absolute: false));
    } elseif ($user->role === 'driver') {
        return redirect()->intended(route('driver.dashboard', absolute: false));
    }

    return redirect()->intended(route('home', absolute: false));
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Memproses percobaan login dari pengguna, meregenerasi ID sesi untuk keamanan (mencegah *session fixation*), dan merutekan pengguna ke dashboard yang sesuai dengan rolenya.
- **Alasan Penting:** Ini adalah pintu gerbang keamanan aplikasi (*Role-Based Access Control* / RBAC).
- **Hubungan Bisnis:** Memastikan staf internal (Admin/Driver) dan pihak luar (Pelanggan) tidak masuk ke antarmuka yang salah.

### 7. Screenshot yang Disarankan
- Halaman Login (Form input email & password).
- Halaman Register (Form pendaftaran akun baru untuk pelanggan).

### 8. Lokasi File
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `routes/auth.php`
- `resources/views/auth/login.blade.php`

### 9. Tingkat Kepentingan
★★★★★ Sangat penting

---

## 4.2.3 Implementasi Modul Pelanggan

### 1. Nama Modul
Modul Pelanggan (Manajemen Profil)

### 2. Fungsi Utama
Menangani penyimpanan dan pembaruan data profil spesifik pelanggan (seperti nama lengkap, nomor WhatsApp). Modul ini bekerja berdampingan dengan modul autentikasi; saat akun `User` dibuat, profil `Pelanggan` otomatis dibentuk di tabel terpisah (implementasi relasi).

### 3. Cara Kerja
- **Route:** `GET /profile`, `PATCH /profile`
- **Business Logic:** Diatur melalui `ProfileController`. Selain itu, saat user melakukan registrasi, relasi pelanggan otomatis dibentuk. Dan saat membuat booking, `BookingService` akan melakukan pengecekan `updateOrCreate` pada data pelanggan agar nomor WhatsApp pelanggan selalu *up-to-date*.
- **Model yang Digunakan:** `Pelanggan`, `User`.
- **Database:** Tabel `pelanggan` berelasi `belongsTo` dengan `users`.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-31 Mengelola Profil** (Fungsionalitas turunan dari registrasi dan booking).

### 5. Potongan Kode Penting
```php
// app/Services/BookingService.php (Potongan Sync Pelanggan)
public function createBooking(array $data, User $user): Booking
{
    return DB::transaction(function () use ($data, $user) {
        // Update or Create data pelanggan terkait User ini
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $data['nama'],
                'no_hp' => $data['no_hp']
            ]
        );
        // ... (lanjut proses insert tabel bookings)
    });
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Mengamankan data profil Pelanggan. Jika profil belum ada, maka akan dibuat baru (`create`). Jika sudah ada, data nama dan nomor HP akan di-update sesuai dengan form booking terakhir.
- **Alasan Penting:** Memastikan nomor WhatsApp (no_hp) yang tersimpan di sistem adalah nomor yang paling aktif, karena sangat vital untuk notifikasi.

### 7. Screenshot yang Disarankan
- Halaman Edit Profil Pengguna (Edit Nama, Email, Password).

### 8. Lokasi File
- `app/Http/Controllers/ProfileController.php`
- `app/Models/Pelanggan.php`

### 9. Tingkat Kepentingan
★★★ Pendukung

---

## 4.2.4 Implementasi Modul Booking Travel

### 1. Nama Modul
Modul Booking Travel

### 2. Fungsi Utama
Melayani pemesanan tiket travel dari pelanggan secara end-to-end. Termasuk pemilihan jadwal, penentuan titik jemput/antar menggunakan peta interaktif, perhitungan harga dinamis, verifikasi kuota, pencegahan booking ganda, serta penjadwalan pembatalan otomatis (*auto-expire*).

### 3. Cara Kerja
- **Route:** `GET /booking/create`, `POST /booking`, `GET /booking/{kode}`
- **Livewire Component:** `app/Livewire/BookingForm.php` digunakan pada frontend agar pelanggan dapat memilih jadwal dan titik jemput tanpa *page reload*.
- **Business Logic:** 
  - Saat form dikirim, `StoreBookingRequest` memvalidasi ketersediaan jadwal.
  - `BookingService::createBooking()` dijalankan di dalam *Database Transaction*.
  - Generate kode unik `SJT-YYYYMMDD-XXXXX`.
  - Hitung total harga: tarif rute × jumlah penumpang.
  - Atur `expired_at` menjadi +30 menit dari sekarang.
  - Scheduler (`app/Console/Commands`) secara berkala menghapus booking yang melewati `expired_at` jika belum ada pembayaran.
- **API Eksternal:** OpenStreetMap via Leaflet.js untuk mendapatkan koordinat (Latitude/Longitude).
- **Model yang Digunakan:** `Booking`, `Jadwal`, `Rute`.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-07 Melakukan Booking Travel**
- **UC-08 Menentukan Titik Jemput dan Tujuan**
- **UC-16 Mengelola Booking** (Batal booking mandiri)
- **UC-26 Melihat Riwayat Booking**

### 5. Potongan Kode Penting
```php
// app/Services/BookingService.php
public function createBooking(array $data, User $user): Booking
{
    return DB::transaction(function () use ($data, $user) {
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            ['nama' => $data['nama'], 'no_hp' => $data['no_hp']]
        );

        $jadwal = Jadwal::with('rute')->findOrFail($data['jadwal_id']);
        $totalHarga = $jadwal->rute->tarif * $data['jumlah_penumpang'];

        $booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $jadwal->id,
            'kode_booking' => $this->generateKodeBooking(),
            'alamat_jemput' => $data['alamat_jemput'],
            'latitude_jemput' => $data['latitude_jemput'],
            'longitude_jemput' => $data['longitude_jemput'],
            'alamat_tujuan' => $data['alamat_tujuan'],
            'latitude_tujuan' => $data['latitude_tujuan'],
            'longitude_tujuan' => $data['longitude_tujuan'],
            'jumlah_penumpang' => $data['jumlah_penumpang'],
            'total_harga' => $totalHarga,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->addMinutes(30),
        ]);

        return $booking;
    });
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Logika utama pembuatan pesanan. Menggunakan `DB::transaction` agar proses insert profil Pelanggan dan insert tabel Booking terjadi secara atomik (jika salah satu gagal, semua di-*rollback*). Menghitung `total_harga` di backend agar aman dari manipulasi sisi klien (inspect element).
- **Alasan Penting:** Mencegah kebocoran pendapatan travel (menghindari *fraud* total harga) dan menetapkan batas waktu booking 30 menit.

### 7. Screenshot yang Disarankan
- Form Livewire Booking (terlihat pilihan jadwal dan perhitungan otomatis).
- Pemilihan titik jemput di peta interaktif Leaflet.
- Halaman Daftar "Booking Saya".

### 8. Lokasi File
- `app/Services/BookingService.php`
- `app/Http/Controllers/BookingController.php`
- `app/Livewire/BookingForm.php`
- `resources/views/livewire/booking-form.blade.php`

### 9. Tingkat Kepentingan
★★★★★ Sangat penting

---

## 4.2.5 Implementasi Modul Pembayaran DP

### 1. Nama Modul
Modul Pembayaran DP (Down Payment)

### 2. Fungsi Utama
Memfasilitasi pelanggan untuk mengunggah bukti transfer DP flat Rp50.000, serta menyediakan antarmuka bagi Admin untuk memverifikasi atau menolak bukti pembayaran tersebut.

### 3. Cara Kerja
- **Route:** `POST /booking/{kode}/pembayaran` (Pelanggan), `PATCH /admin/pembayaran/{id}/verifikasi` (Admin).
- **Business Logic Pelanggan:** Saat bukti diupload, file akan disimpan ke disk `public`. Tabel `pembayaran` di-insert, lalu `status_booking` berubah dari `booking_dibuat` menjadi `menunggu_verifikasi`.
- **Business Logic Admin:** Admin menekan tombol konfirmasi. Terjadi `DB::transaction`: `status_pembayaran` menjadi `terverifikasi`, dan `status_booking` naik menjadi `dikonfirmasi`. 
- **Notification:** Setelah sukses diverifikasi, observer / controller memanggil `FonnteService` untuk mengirim WhatsApp resi ke Pelanggan.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-09 Mengunggah Bukti Pembayaran DP**
- **UC-10 Memverifikasi Pembayaran DP**
- **UC-11 Mengunggah Ulang Bukti Pembayaran DP**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Admin/PembayaranController.php
public function verifikasi(Request $request, Pembayaran $pembayaran)
{
    DB::transaction(function () use ($pembayaran) {
        // 1. Ubah status tabel pembayaran
        $pembayaran->update([
            'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
        ]);

        // 2. Cascade ubah status tabel booking
        $pembayaran->booking->update([
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);
    });

    // 3. Kirim notifikasi via WhatsApp
    $booking = $pembayaran->booking()->with(['pelanggan', 'jadwal.rute'])->first();
    app(BookingWhatsappNotificationService::class)->sendDpVerifiedNotification($booking);

    return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Mengubah *state* pembayaran dan booking secara bersamaan dalam transaksi database, kemudian men-*trigger* notifikasi pengiriman pesan WhatsApp secara *asynchronous* atau sinkronous melalui Service.
- **Hubungan Bisnis:** Ini adalah titik penentu sah atau tidaknya sebuah pemesanan. Tiket resmi dinyatakan valid setelah *logic* ini dieksekusi.

### 7. Screenshot yang Disarankan
- Form unggah foto resi pembayaran (di sisi Pelanggan).
- Halaman kelola verifikasi pembayaran (di sisi Dashboard Admin).

### 8. Lokasi File
- `app/Http/Controllers/PembayaranController.php` (Pelanggan)
- `app/Http/Controllers/Admin/PembayaranController.php` (Admin)

### 9. Tingkat Kepentingan
★★★★★ Sangat penting

---

## 4.2.6 Implementasi Modul Master Data

### 1. Nama Modul
Modul Master Data (Rute, Armada, Driver, Jadwal)

### 2. Fungsi Utama
Mengelola fondasi *resource* travel. Admin dapat menambah, mengubah, atau menghapus daftar Rute, daftar Mobil (Armada), mendaftarkan akun untuk Driver, serta menjadwalkan trip.

### 3. Cara Kerja
- **Route:** Menggunakan `Route::resource()` di dalam namespace/prefix `admin` (misal: `/admin/rute`).
- **Business Logic:** Standard CRUD Eloquent. 
- **Keunikan Driver:** Saat membuat data Driver, sistem memanggil `DB::transaction()` untuk meng-*create* entitas di tabel `users` (dengan role driver dan password default), lalu menyambungkannya ke tabel `drivers` dengan ID *user* tersebut, serta me-*link* driver ke armada yang ditugaskan (relasi 1-to-1).
- **Keunikan Jadwal:** Saat mendaftarkan jadwal, jadwal terikat ke Rute (relasi 1-to-M). Terdapat kuota kursi dan sistem *Shift* (Pagi/Malam).

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-12 Mengelola Rute**
- **UC-13 Mengelola Armada**
- **UC-14 Mengelola Driver**
- **UC-15 Mengelola Jadwal Keberangkatan**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Admin/DriverController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'no_hp' => 'required|string|max:20',
        'armada_id' => 'required|exists:armada,id',
    ]);

    DB::transaction(function () use ($validated) {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        Driver::create([
            'user_id' => $user->id,
            'nama_driver' => $validated['name'],
            'no_hp' => $validated['no_hp'],
            'armada_id' => $validated['armada_id'],
            'status_driver' => 'aktif',
        ]);
    });

    return redirect()->route('admin.drivers.index')->with('success', 'Driver ditambahkan.');
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Menyimpan data staf driver baru dengan pendekatan *Double Table Insert*.
- **Alasan Penting:** Mencegah terjadinya data driver yang kehilangan akses login (*orphan records*). Jika terjadi error pada pembuatan relasi di tabel `drivers`, akun `user` yang setengah jadi akan ikut di-rollback.

### 7. Screenshot yang Disarankan
- Tabel daftar armada dan driver.
- Form penambahan jadwal baru.

### 8. Lokasi File
- `app/Http/Controllers/Admin/DriverController.php`
- `app/Http/Controllers/Admin/JadwalController.php`

### 9. Tingkat Kepentingan
★★★★ Penting

---

## 4.2.7 Implementasi Modul Operasional Trip

### 1. Nama Modul
Modul Operasional Trip (Kelola Trip & Manifest)

### 2. Fungsi Utama
Fasilitas bagi Admin untuk merealisasikan sebuah Jadwal yang memiliki Booking masuk menjadi sebuah perjalanan fisik (Trip). Admin membentuk Trip, memilih mobil/driver (otomatis dari relasi), lalu menyeleksi booking mana saja yang akan dinaikkan ke manifest Trip tersebut.

### 3. Cara Kerja
- **Business Logic:** 
  - Admin membuat Trip baru (status `new`).
  - Admin masuk ke halaman Detail Trip.
  - Admin mengeklik tombol "Masukkan" pada daftar booking yang tersedia. 
  - Aksi tersebut meng-insert record ke tabel pivot/jembatan `detail_trip` (Trip_id, Booking_id). 
  - Status tabel `bookings` otomatis berubah menjadi `assigned_to_trip`.
  - Observer mendeteksi perubahan ini dan memicu pengiriman notifikasi WhatsApp ke Pelanggan ("Anda mendapat supir X, mobil Y") dan notifikasi WhatsApp ke Driver ("Ada penumpang baru").

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-17 Membentuk Trip**
- **UC-18 Menugaskan Driver dan Armada ke Trip**
- **UC-19 Memasukkan Booking ke Trip**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Admin/TripController.php
public function assignBooking(Request $request, Trip $trip)
{
    $bookingId = $request->input('booking_id');
    $booking = Booking::findOrFail($bookingId);

    DB::transaction(function () use ($trip, $booking) {
        // Buat relasi di detail_trip (Manifest)
        DetailTrip::create([
            'trip_id' => $trip->id,
            'booking_id' => $booking->id,
            'status_jemput' => 'belum',
            'status_antar' => 'belum',
        ]);

        // Kunci booking agar tidak di-assign ke trip lain
        $booking->update([
            'status_booking' => Booking::STATUS_ASSIGNED_TO_TRIP
        ]);
    });

    // Kirim notifikasi manifest ke driver dan notifikasi armada ke pelanggan
    app(BookingWhatsappNotificationService::class)->sendAssignedToTripNotification($booking, $trip);

    return redirect()->back()->with('success', 'Booking berhasil dimasukkan ke trip.');
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Menggabungkan entitas `Booking` ke dalam entitas `Trip` dengan mencetak tiket fisik (diwakili tabel `DetailTrip`) sekaligus menyebarkan informasi ke pihak-pihak terkait.
- **Hubungan Bisnis:** Inilah tahap final operasional *back-office* sebelum roda mobil berputar; serah terima tanggung jawab dari Admin ke Driver di lapangan.

### 7. Screenshot yang Disarankan
- Halaman List Trip Admin (Tab status: New, Ready, On Trip, dll).
- Halaman Detail Trip / Drag & Drop Assign Penumpang.

### 8. Lokasi File
- `app/Http/Controllers/Admin/TripController.php`

### 9. Tingkat Kepentingan
★★★★★ Sangat penting

---

## 4.2.8 Implementasi Modul Dashboard Driver

### 1. Nama Modul
Modul Dashboard Driver

### 2. Fungsi Utama
Fasilitas mandiri khusus bagi pengemudi untuk melihat daftar manifest (penumpang yang harus dijemput), memulai perjalanan, memperbarui status *pickup* dan *dropoff*, serta menagih sisa pelunasan pembayaran secara tunai di lokasi.

### 3. Cara Kerja
- **Route:** `GET /driver/trips/{id}`, `PATCH /driver/detail-trip/{id}/status`
- **Business Logic:**
  - Driver login dan melihat trip yang terhubung dengan `auth()->user()->driver->id`.
  - Driver mengeklik "Mulai Perjalanan", status Trip menjadi `on_trip`.
  - Saat tiba di rumah pelanggan, driver klik "Sudah Dijemput" (mengubah `status_jemput` di `detail_trip` menjadi `sudah_dijemput` dan mencatat `picked_up_at`).
  - Saat di lokasi tujuan, driver menekan "Konfirmasi Pelunasan Tunai". Sistem mencatat `Pembayaran` baru bertipe `pelunasan`.
  - Jika selesai, trip ditutup (status `completed`). Semua relasi booking otomatis menjadi `completed`.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-20 Melihat Trip Hari Ini**
- **UC-21 Melihat Manifest Penumpang**
- **UC-22 Melihat Lokasi Jemput**
- **UC-23 Mengonfirmasi Pickup dan Drop-off**
- **UC-24 Memperbarui Status Trip**
- **UC-25 Mengonfirmasi Pelunasan Pembayaran**
- **UC-27 Melihat Riwayat Trip Driver**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Driver/TripController.php
public function updateDetailStatus(Request $request, DetailTrip $detailTrip)
{
    $type = $request->input('type'); // 'jemput' atau 'antar'
    
    if ($type === 'jemput') {
        $detailTrip->update([
            'status_jemput' => 'sudah_dijemput',
            'picked_up_at' => now(),
        ]);
        
        $detailTrip->booking->update(['status_booking' => Booking::STATUS_ON_TRIP]);
        
    } elseif ($type === 'antar') {
        $detailTrip->update([
            'status_antar' => 'sudah_diantar',
            'dropped_off_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Status berhasil diperbarui.');
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Memperbarui koordinasi logistik keberadaan pelanggan di lapangan secara sekuensial. 
- **Alasan Penting:** Merekam *timestamp* pasti kapan pelanggan dijemput dan diturunkan untuk keperluan audit, KPI, dan penentuan persentase *On-Time* pada Landing Page.

### 7. Screenshot yang Disarankan
- Tampilan Dashboard Driver (Statistik trip).
- Daftar manifest/penumpang di HP Driver beserta tombol Pickup/Dropoff.

### 8. Lokasi File
- `app/Http/Controllers/Driver/TripController.php`

### 9. Tingkat Kepentingan
★★★★★ Sangat penting

---

## 4.2.9 Implementasi Modul Laporan

### 1. Nama Modul
Modul Pelaporan (Analitik & Keuangan)

### 2. Fungsi Utama
Mengekstrak, mengolah, dan menyajikan rekap data jumlah booking, kinerja operasional (Trip), serta total pendapatan perusahaan yang difilter berdasar rentang waktu (hari/minggu/bulan) dan shift (pagi/malam) dalam bentuk grafik dan tabel.

### 3. Cara Kerja
- **Business Logic:**
  - Mengambil parameter request GET (period, shift).
  - Membuat *Query Scope/Builder* `baseBookingQuery` dan `baseTripQuery` yang diberi logika `whereBetween` pada kolom `created_at` (untuk booking) atau `started_at` (untuk trip).
  - Mengelompokkan data (`groupBy DATE`) dan men-sum `total_harga` dari Booking berstatus `completed`.
  - Data di-passing ke Chart.js pada file Blade untuk visualisasi diagram garis (Line Chart).

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-28 Melihat Laporan Booking**
- **UC-29 Melihat Laporan Trip**
- **UC-30 Melihat Laporan Pendapatan**

### 5. Potongan Kode Penting
```php
// app/Http/Controllers/Admin/LaporanController.php
$chartData = $this->baseBookingQuery($startDate, $endDate, $shift)
    ->where('status_booking', Booking::STATUS_COMPLETED)
    ->select(
        DB::raw('DATE(bookings.created_at) as date'),
        DB::raw('SUM(total_harga) as revenue')
    )
    ->groupBy('date')
    ->orderBy('date')
    ->get();

$chartLabels = $chartData->pluck('date')->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'))->toArray();
$chartValues = $chartData->pluck('revenue')->toArray();

return view('admin.laporan.index', compact('chartLabels', 'chartValues', 'totalRevenue', /* ... */));
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Logika agregasi SQL tingkat lanjut. Melakukan query agregat (`SUM` dan `GROUP BY`) dan membentuk dataset array agar dapat dibaca oleh library Chart.js di sisi frontend.
- **Hubungan Bisnis:** Vital bagi pemilik travel/admin tingkat atas untuk melihat tren pendapatan harian dan membuat keputusan bisnis.

### 7. Screenshot yang Disarankan
- Halaman Laporan Admin menampilkan grafik tren pendapatan.
- Hasil ekspor laporan (cetak PDF/Print).

### 8. Lokasi File
- `app/Http/Controllers/Admin/LaporanController.php`
- `resources/views/admin/laporan/index.blade.php`

### 9. Tingkat Kepentingan
★★★★ Penting

---

## 4.2.10 Implementasi Modul WhatsApp Notification

### 1. Nama Modul
Modul Notifikasi WhatsApp (Fonnte API Integration)

### 2. Fungsi Utama
Mengotomatiskan komunikasi dengan pelanggan menggunakan API pihak ketiga (Fonnte) untuk mengirim konfirmasi DP, pembatalan, informasi nomor pelat kendaraan dan kontak driver, serta mengirimkan notifikasi *reminder* keberangkatan secara massal (*batch*).

### 3. Cara Kerja
- **Integrasi API:** Menggunakan `Illuminate\Support\Facades\Http` untuk melakukan POST request HTTP ke `https://api.fonnte.com/send`.
- **Database Logging:** Setiap pesan yang akan dikirim dicatat di tabel `whatsapp_notifications` dengan status awal `pending`.
- **Error Handling (Graceful Degradation):** Jika token Fonnte kosong atau API gagal merespons, sistem tetap melanjutkan proses aplikasi tanpa melempar error (*500 Internal Server Error*). Status pada tabel diubah menjadi `failed` beserta alasan JSON-nya.
- **Scheduler (Cron Job):** Laravel Scheduler memicu `Console/Commands/SendDailyConfirmation` setiap jam 06:00 WIB pagi untuk menyapu seluruh booking hari tersebut dan menembakkan pesan WhatsApp ke pelanggan terkait.

### 4. Keterkaitan Kebutuhan Fungsional
- **UC-31 Mengirim Notifikasi WhatsApp**

### 5. Potongan Kode Penting
```php
// app/Services/FonnteService.php
public function send(string $target, string $message, string $type, ?int $bookingId = null): bool
{
    // 1. Simpan riwayat ke tabel (Log)
    $notification = WhatsappNotification::create([
        'booking_id' => $bookingId,
        'target' => $target,
        'message' => $message,
        'type' => $type,
        'status' => WhatsappNotification::STATUS_PENDING,
    ]);

    $token = config('services.fonnte.token');

    // 2. Eksekusi cURL HTTP POST ke API Fonnte
    try {
        $response = Http::withHeaders(['Authorization' => $token])
            ->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

        // 3. Update status berdasar balasan API
        if ($response->successful() && $response->json('status') === true) {
            $notification->update(['status' => WhatsappNotification::STATUS_SENT, 'response' => $response->body()]);
            return true;
        }

        $notification->update(['status' => WhatsappNotification::STATUS_FAILED, 'response' => $response->body()]);
        return false;

    } catch (\Exception $e) {
        $notification->update(['status' => WhatsappNotification::STATUS_FAILED, 'response' => $e->getMessage()]);
        return false;
    }
}
```

### 6. Penjelasan Potongan Kode
- **Fungsi Kode:** Kelas Service sentral (*Single Responsibility Principle*) yang bertugas menghubungkan sistem ke API Fonnte dengan membawa mekanisme *logging* dan penangkapan *Exception* (`try-catch`). 
- **Hubungan Bisnis:** Memastikan kenyamanan pelanggan karena tiket, rincian biaya, dan info supir dikirim instan tanpa Admin perlu menyalin (copy-paste) pesan manual ke WA pelanggan.

### 7. Screenshot yang Disarankan
- Bukti foto (*Screenshot*) isi percakapan WA dari sistem ke nomor pelanggan.
- Halaman riwayat log notifikasi WA di Dashboard Admin (jika ada).

### 8. Lokasi File
- `app/Services/FonnteService.php`
- `app/Services/BookingWhatsappNotificationService.php`

### 9. Tingkat Kepentingan
★★★★★ Sangat Penting
