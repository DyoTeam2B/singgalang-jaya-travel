# Development Safety Rules

## Tujuan

Prioritas utama selama sprint awal adalah memastikan aplikasi **selalu dapat berjalan (runnable)**, dapat didemokan ke dosen/tim, dan tidak memicu error halaman (seperti 500 error, Route Not Defined, dll) meskipun beberapa fitur di dalamnya masih dalam tahap pengerjaan.

## Route Safety

* **Jangan menggunakan route yang belum terdaftar** di `routes/web.php`.
* Sebelum menggunakan fungsi helper `route()`, pastikan Anda telah memeriksa `routes/web.php` terlebih dahulu untuk melihat apakah route name tersebut sudah didefinisikan.
* Jika route untuk suatu tombol atau link belum tersedia, gunakan placeholder sementara agar tidak memicu error *Route Not Defined*.

Contoh placeholder aman:
* `href="#"`
* `href="#jadwal"`
* `href="#kontak"`

## View Safety

* **Jangan memanggil view yang belum dibuat** dari controller.
* Pastikan string view path yang dipanggil pada method `view(...)` di controller benar-benar sesuai dengan letak file Blade di folder `resources/views`.
* Jika halaman view sesungguhnya belum selesai dibuat oleh rekan tim Anda, buat file view placeholder sederhana (minimal berisi judul dan keterangan pengerjaan modul) agar sistem tidak crash saat diakses.

## Component Safety

* **Jangan memanggil Blade Component yang belum ada** menggunakan tag `<x-component-name />` atau direktif `@component`.
* Pastikan seluruh component yang ingin Anda gunakan memiliki file fisiknya di folder `resources/views/components/` atau terdaftar di class component Laravel.

## Navigation Safety

Untuk kelancaran demo selama Sprint awal, navigasi menu berikut:

### Navbar Pelanggan (Guest — Belum Login):
* **Home**
* **Jadwal**
* **Armada & Driver**
* **Charter**
* **Kontak**
* **Login**
* **Register**

### Navbar Pelanggan (Setelah Login):
* **Home**
* **Jadwal**
* **Booking Saya**
* **Charter**
* **Kontak**
* **Profil** (Dropdown: Profil Saya, Booking Saya, Logout)

diperbolehkan menggunakan link anchor sementara:
* `href="#home"`
* `href="#jadwal"`
* `href="#booking"`
* `href="#kontak"`

Tautan anchor ini harus tetap dipertahankan sampai fitur sesungguhnya selesai diimplementasikan secara penuh.

## Form Safety

* **Jangan melakukan submit form ke route aksi (action) yang belum dibuat** di router dan controller.
* Jika backend controller untuk memproses data form belum siap, gunakan atribut `action="#"` atau `action=""` sementara waktu.
* Tambahkan komentar `// TODO: hubungkan ke route store/update` pada file Blade atau controller untuk menandai bagian yang perlu diintegrasikan nanti.

## Database Safety

* **Jangan membuat foreign key constraint ke tabel yang belum tersedia** atau belum dideklarasikan migrasinya di database.
* Jika Anda membutuhkan relasi data ke modul lain yang belum selesai, gunakan kolom tipe data biasa terlebih dahulu sementara waktu (tanpa strict constraint).
* Tuliskan catatan `TODO` di file migrasi atau model untuk mengingatkan tim agar melakukan integrasi foreign key jika modul tujuannya sudah selesai dibuat.

## Migration Safety

Sebelum melakukan commit migrasi database baru, periksa hal-hal berikut secara teliti:
1. **Nama Tabel**: Pastikan menggunakan penamaan konsisten (Bahasa Indonesia untuk tabel operasional).
2. **Foreign Key**: Pastikan tabel induk sudah dimigrasikan terlebih dahulu sebelum ditunjuk oleh foreign key di tabel anak.
3. **Urutan File Migrasi**: Periksa penomoran timestamp pada nama file migrasi untuk menjamin tabel induk dibuat lebih dahulu daripada tabel dependen.
4. **Tabel Armada**: Tabel `armada` HARUS dibuat sebelum `drivers` dan `trips` karena kedua tabel tersebut memiliki foreign key `armada_id`.

## Git Safety

Sebelum melakukan push atau merge ke branch utama:
1. Jalankan `git status` untuk memverifikasi file apa saja yang diubah dan pastikan tidak ada file sampah yang tidak sengaja masuk.
2. Periksa apakah terjadi merge conflict.
3. **Pastikan tidak ada file yang mengandung conflict marker** sisa git merge.

Jika ditemukan, perbaiki konfliknya terlebih dahulu secara manual dan bersihkan tanda tersebut sebelum commit/push.

## Sprint Safety

Sebelum menandai suatu task selesai (*completed*) di daftar progress:
* Pastikan halaman yang dikerjakan dapat dibuka secara penuh di browser lokal.
* Pastikan tidak ada route error yang pecah.
* Pastikan file view berhasil ter-render tanpa error kompilasi Blade.
* Pastikan seluruh component terpanggil dengan benar.

## Documentation Safety

Setelah berhasil mengimplementasikan fitur atau mengubah struktur berkas:
* Perbarui status pengerjaan di [context/sprint-planning.md](file:///d:/pbl/singgalang-jaya-travel/context/sprint-planning.md).
* Catat ringkasan perubahan terbaru di [docs/CHANGELOG.md](file:///d:/pbl/singgalang-jaya-travel/docs/CHANGELOG.md).

## Pre Commit Checklist

Sebelum melakukan commit kode Anda, pastikan Anda mencentang checklist internal berikut:

- [ ] **Route Valid**: Semua route yang dipanggil menggunakan helper `route()` sudah dideklarasikan di `routes/web.php`.
- [ ] **View Valid**: Semua view yang dipanggil dari controller memiliki berkas fisik `.blade.php` yang sesuai.
- [ ] **Component Valid**: Seluruh Blade Component yang dipanggil dalam tag `<x-... />` sudah dibuat.
- [ ] **Migration Valid**: Urutan migrasi aman dan tidak ada masalah constraint foreign key saat dideploy ulang.
- [ ] **Bebas Merge Conflict**: Tidak ada conflict marker git sisa merge di berkas manapun.
- [ ] **Dokumentasi Terupdate**: Berkas `context/sprint-planning.md` dan `docs/CHANGELOG.md` telah disesuaikan dengan progress terbaru.

## Scheduler Safety

Sistem memiliki **satu command scheduler** yang WAJIB berjalan di production:

| Command | Jadwal | Fungsi |
|---------|--------|--------|
| `booking:send-confirmation` | Setiap hari jam 06:00 | Kirim WA konfirmasi ulang ke pelanggan sebelum keberangkatan |

**Catatan**:
- Pastikan `php artisan schedule:run` sudah terdaftar di cron server.
- Pastikan `FONNTE_TOKEN` sudah diset di `.env` sebelum testing notifikasi WhatsApp.
- Jangan test command `booking:send-confirmation` tanpa memastikan token FonnteAPI valid.
- Gunakan `--dry-run` flag (jika tersedia) saat testing scheduler di local.
