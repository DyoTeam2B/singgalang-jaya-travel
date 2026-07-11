# Pembagian Tugas Perbaikan & Fitur Tambahan

> Pembagian dikelompokkan berdasarkan **area kode** agar tidak terjadi conflict antar anggota.

---

## 👤 Rayfo — Area: Landing Page & General (10 tugas)

Fokus: `resources/views/public/home.blade.php`, `HomeController`, notifikasi WhatsApp

| No | No. Ref | Tugas | Area File |
|----|---------|-------|-----------|
| 1 | #2 | Ubah susunan kalimat perjalanan "Padang Panjang ke Pekanbaru" | `views/public/home.blade.php` |✅
| 2 | #3 | Jumlah penumpang diambil dari database | `HomeController`, `home.blade.php` |✅
| 3 | #4 | Rating diambil dari database (rumus: Total  Bintang ÷ Jumlah Ulasan) | `HomeController`, `home.blade.php` |✅
| 4 | #5 | Ubah text "2 Jam" jadi "2 Jam -+" | `home.blade.php` | ✅
| 5 | #6 | Ubah logo di bagian "Kenapa Memilih Singgalang Jaya Travel" | `home.blade.php` |
| 6 | #7 | Sesuaikan jumlah penumpang (statistik) | `HomeController`, `home.blade.php` |
| 7 | #8 | Sesuaikan jumlah rating (statistik) | `HomeController`, `home.blade.php` |✅
| 8 | #9 | Sesuaikan jumlah rute (statistik) | `HomeController`, `home.blade.php` |✅
| 9 | #10 | Sesuaikan "Tepat Waktu" (statistik) | `HomeController`, `home.blade.php` |✅
| 10 | #28 | Perbaiki format notifikasi WhatsApp agar terbaca rapi (pelanggan & driver) | `Notifications/`, services WA |✅

---

## 👤 Nayasha — Area: Halaman Pelanggan (Booking, Profil, Jadwal di Landing) (10 tugas)

Fokus: `resources/views/public/booking/`, `resources/views/public/jadwal/`, `resources/views/profile/`, `BookingController`

| No | No. Ref | Tugas | Area File |
|----|---------|-------|-----------|
| 1 | #11 | Perbaiki bug logo malam terpotong di jadwal keberangkatan shift malam | `home.blade.php` (section jadwal) |
| 2 | #12 | Hilangkan tulisan "Full AC" pada jadwal keberangkatan terkini | `home.blade.php` (section jadwal) |
| 3 | #13 | Warna component shift malam ganti hitam, tulisan putih | `home.blade.php` (section jadwal) |
| 4 | #14 | Bagian armada: hapus tulisan "Padang Panjang ↔️ Pekanbaru" | `home.blade.php` (section armada) |
| 5 | #16 | Ganti tulisan "Kendaraan ber-AC..." (jangan pakai "kendaraan ber-AC") | `home.blade.php` (section armada) |
| 6 | #17 | Di card armada tampilkan status armada terkini | `home.blade.php`, `HomeController` |
| 7 | #18 | Perbaiki logika booking aktif — booking harus tidak aktif jika DP belum dibayar & sudah lewat tenggat | `BookingController`, Model `Booking` |
| 8 | #19 | Tambah fitur upload/edit foto profil pelanggan (opsional tapi fitur tetap ada) | `views/profile/`, `ProfileController` |
| 9 | #20 | Isi titik jemput: input alamat → markup peta otomatis menandai sesuai alamat | `views/public/booking/`, `BookingController` |
| 10 | #21 | Di detail booking, tonjolkan sisa bayar agar lebih terlihat oleh pelanggan | `views/public/booking/show.blade.php` |

---

## 👤 Kevin — Area: Halaman Admin & Driver (11 tugas)

Fokus: `resources/views/admin/`, `resources/views/driver/`, `Admin Controllers`, `Driver Controllers`

| No | No. Ref | Tugas | Area File |
|----|---------|-------|-----------|
| 1 | #15 | Foto armada sesuaikan dengan data armada di database | `home.blade.php` (section armada), `HomeController` |
| 2 | #21 | Bagian armada admin: tambahkan foto armada saat CRUD (opsional saat create, bisa ditambah saat edit) | `views/admin/armada/`, `ArmadaController` |
| 3 | #22 | Foto armada admin terhubung dengan foto armada di landing page | `ArmadaController`, `home.blade.php` |
| 4 | #23 | Pada jadwal edit: hapus opsi pilihan "penuh" (biarkan sistem yang mengatur) | `views/admin/jadwal/`, `JadwalController` |
| 5 | #24 | Perbaiki bug jadwal penuh tidak muncul di create trip (terhubung dgn #23) | `TripController`, `views/admin/trips/` |
| 6 | #25 | Pada detail trip admin: hanya bisa setujui, tidak bisa mulai trip (tugas driver) | `views/admin/trips/show.blade.php`, `TripController` |
| 7 | #26 | Export laporan: perbaiki format CSV atau ganti ke PDF agar rapi | `LaporanController`, `views/admin/laporan/` |
| 8 | #27 | Buat bell notifikasi di dashboard admin (booking baru, batal, driver mulai perjalanan, dll) | `views/admin/dashboard`, `DashboardController`, Notifications |
| 9 | #29 | Driver: buat quick button status driver (pending/istirahat/tersedia) | `views/driver/`, `DriverController` |
| 10 | #30 | Driver: peta rute ganti ke alamat pelanggan | `views/driver/trips/`, `TripController` |
| 11 | #31 | Driver: filter riwayat trip & pendapatan (hari ini, 7 hari lalu, bulan ini) | `views/driver/trips/`, `TripController` |

---

## 📊 Ringkasan Pembagian

| Anggota | Jumlah Tugas | Area Utama |
|---------|-------------|------------|
| **Rayfo** | 10 | Landing Page (statistik, konten, logo) + Notifikasi WA |
| **Nayasha** | 10 | Pelanggan (jadwal section, armada section, booking, profil) |
| **Kevin** | 11 | Admin (armada CRUD, jadwal, trip, laporan, notifikasi) + Driver |

> **Catatan:** Kevin mendapat 11 tugas karena area admin & driver memiliki banyak item yang saling terkait (misal #23 & #24, #21 & #22), sehingga lebih efisien dikerjakan oleh satu orang. Secara kompleksitas, beban kerja tetap seimbang.

---

## ⚠️ Potensi Overlap & Koordinasi

| File | Siapa yang edit | Koordinasi |
|------|----------------|------------|
| `home.blade.php` | Rayfo (section hero/stats), Nayasha (section jadwal/armada), Kevin (#15 foto armada) | **Kerjakan section berbeda**, commit per section |
| `HomeController.php` | Rayfo (data stats), Nayasha (status armada), Kevin (foto armada) | **Pisahkan method/variabel**, komunikasikan perubahan |
| Notifications/WA | Rayfo (format WA), Kevin (bell notifikasi admin) | **File berbeda**, tidak akan conflict |

> **Tips Menghindari Conflict:**
> 1. Setiap orang kerjakan di **branch terpisah** (contoh: `rayfo/landing-stats`, `nayasha/pelanggan-fix`, `kevin/admin-driver`)
> 2. Commit sesering mungkin dan **pull sebelum push**
> 3. Untuk `home.blade.php`, kerjakan **section yang berbeda** dan merge satu per satu
