# Dokumentasi UAS Konstruksi Evolusi Perangkat Lunak

## Identitas Proyek

| Item | Keterangan |
| --- | --- |
| Nama proyek | Singgalang Jaya Travel System |
| Jenis aplikasi | Sistem informasi travel berbasis web |
| Framework | Laravel 13 |
| Database | MySQL |
| Frontend | Blade, Livewire, Alpine.js, Tailwind CSS, Vite |
| Integrasi | Fonnte API, Leaflet/OpenStreetMap, Chart.js |
| Tujuan | Digitalisasi booking, pembayaran DP, pengelolaan jadwal, trip, driver, armada, dan laporan operasional travel |

## Pemenuhan Luaran UAS

| Ketentuan UAS | Luaran di repository | Status |
| --- | --- | --- |
| Dokumentasi | `README.md`, seluruh file pada `docs/` | Selesai |
| Pembagian peran di GitHub | `docs/github-roles.md` | Selesai |
| GitHub Action | `.github/workflows/laravel-ci.yml` | Selesai |
| Refactoring | `docs/refactoring.md` | Selesai |
| Dependency | `docs/dependencies.md` | Selesai |
| Installation doc | `docs/INSTALLATION.MD` | Selesai |
| Feature doc | `docs/features.md` | Selesai |
| Changelog | `docs/CHANGELOG.MD` | Selesai |
| GitHub Action doc | `docs/github-actions.md` | Selesai |

## Ringkasan Sistem

Singgalang Jaya Travel System dibangun untuk mengganti proses booking travel yang sebelumnya manual melalui WhatsApp/telepon menjadi sistem terstruktur. Pelanggan dapat memilih jadwal, membuat booking, mengunggah bukti DP, dan melihat status. Admin mengelola master data, verifikasi pembayaran, membuat trip, dan menyusun manifest. Driver menggunakan dashboard khusus untuk melihat trip, menjemput penumpang, mencatat pelunasan, dan menyelesaikan perjalanan.

## Scope Fitur Berdasarkan Aktor

| Aktor | Fitur utama |
| --- | --- |
| Pelanggan | Register/login, lihat jadwal, booking, upload bukti DP, booking saya, cek booking, edit lokasi, cancel booking |
| Admin | Dashboard, CRUD rute, armada, jadwal, driver, kelola booking, verifikasi pembayaran, manajemen trip, laporan |
| Driver | Dashboard driver, daftar trip, detail manifest, start trip, pickup, dropoff, konfirmasi pembayaran, complete trip |
| Sistem | Generate kode booking, sinkronisasi status booking/trip, log notifikasi WhatsApp, build/test CI |

## Artefak Proyek

| Kategori | Artefak |
| --- | --- |
| Source code backend | `app/`, `routes/`, `database/`, `config/` |
| Source code frontend | `resources/views/`, `resources/css/`, `resources/js/` |
| Test | `tests/Feature/`, `tests/Unit/` |
| Dokumentasi | `README.md`, `docs/` |
| CI/CD | `.github/workflows/laravel-ci.yml` |
| Dependency lock | `composer.lock`, `package-lock.json` |

## Alur Demo UAS

1. Buka `README.md` untuk memperlihatkan ringkasan proyek dan indeks dokumen.
2. Buka `docs/INSTALLATION.MD` untuk cara instalasi.
3. Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

4. Jalankan aplikasi:

```bash
php artisan serve
npm run dev
```

5. Login menggunakan akun seeder:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@gmail.com` | `admin12345` |
| Driver | `driver@gmail.com` | `driver12345` |
| Pelanggan | `pelanggan@gmail.com` | `pelanggan12345` |

6. Tunjukkan alur utama:

| Demo | Langkah |
| --- | --- |
| Pelanggan booking | Login pelanggan -> buka jadwal -> booking -> upload bukti DP |
| Admin verifikasi | Login admin -> pembayaran -> verifikasi DP -> booking dikonfirmasi |
| Admin trip | Admin buat trip -> pilih driver -> assign booking ke trip |
| Driver manifest | Login driver -> dashboard/trip -> pickup/dropoff -> complete trip |
| Laporan | Admin buka laporan untuk melihat ringkasan pendapatan |

7. Tunjukkan GitHub Action:

- File workflow: `.github/workflows/laravel-ci.yml`
- Dokumentasi workflow: `docs/github-actions.md`

8. Tunjukkan refactoring:

- File dokumentasi: `docs/refactoring.md`
- Contoh kode: `BookingService`, `BookingWhatsappNotificationService`, `TripObserver`, `DetailTripObserver`, Form Request classes.

## Quality Gate

Quality gate yang disiapkan:

| Gate | Command |
| --- | --- |
| Install backend | `composer install` |
| Install frontend | `npm ci` |
| Build frontend | `npm run build` |
| Migration test DB | `php artisan migrate --force` |
| Automated test | `php artisan test` |

Quality gate tersebut dijalankan otomatis oleh GitHub Action saat push atau pull request ke branch utama.

## Catatan Evolusi Perangkat Lunak

Bukti evolusi proyek dapat dilihat dari:

- `docs/CHANGELOG.MD` untuk riwayat perubahan per sprint dan anggota.
- `docs/refactoring.md` untuk perubahan struktur kode agar lebih maintainable.
- `docs/github-roles.md` untuk pembagian ownership modul.
- Test suite di `tests/Feature/` untuk menjaga regresi fitur utama.
- GitHub Action CI untuk memastikan build dan test berjalan sebelum merge.
