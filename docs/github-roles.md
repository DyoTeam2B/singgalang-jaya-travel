# Pembagian Peran GitHub

Dokumen ini menjelaskan pembagian peran tim pada GitHub untuk proyek Singgalang Jaya Travel System. Pembagian dibuat berdasarkan modul yang sudah ada pada repository dan catatan sprint proyek.

## Struktur Tim

| Anggota | Fokus modul | Tanggung jawab GitHub | Path utama |
| --- | --- | --- | --- |
| Rayhan Ramadhan | Customer interface, booking, pembayaran pelanggan, integrasi WhatsApp | Membuat issue/branch fitur pelanggan, menjaga flow booking, update feature/changelog terkait pelanggan | `app/Http/Controllers/BookingController.php`, `app/Http/Controllers/PembayaranController.php`, `app/Services/BookingService.php`, `app/Services/FonnteService.php`, `resources/views/public/` |
| Rayfo Huda | Admin core, rute, jadwal, armada, laporan | Membuat issue/branch admin core, menjaga migration/master data, update laporan dan dependency | `app/Http/Controllers/Admin/RuteController.php`, `app/Http/Controllers/Admin/JadwalController.php`, `app/Http/Controllers/Admin/ArmadaController.php`, `app/Http/Controllers/Admin/LaporanController.php`, `resources/views/admin/` |
| Nayasha Ananda Risdi | Auth, admin booking, pembayaran admin, observer, kualitas fitur | Review validasi, status booking, verifikasi pembayaran, test admin operasional | `app/Http/Controllers/Admin/BookingController.php`, `app/Http/Controllers/Admin/PembayaranController.php`, `app/Observers/`, `app/Livewire/Admin/`, `tests/Feature/Admin/` |
| Kevin Maulana | Trip management dan driver operations | Menjaga modul trip, manifest driver, status perjalanan, test driver/trip | `app/Http/Controllers/Admin/TripController.php`, `app/Http/Controllers/Driver/`, `resources/views/driver/`, `tests/Feature/DriverTripTest.php` |

## Role Repository yang Disarankan

| GitHub role | Pemegang | Hak |
| --- | --- | --- |
| Owner/Maintainer | Ketua tim atau akun organisasi | Mengatur repository, branch protection, secret, merge final |
| Write | Semua developer | Push branch fitur, membuat pull request, review PR |
| Triage | Dosen/asisten bila diperlukan | Melihat issue, memberi label, memberi komentar review |
| Read | Penguji/auditor | Melihat source code dan dokumentasi |

## Branch Strategy

Branch utama:

| Branch | Fungsi | Aturan |
| --- | --- | --- |
| `main` | Versi stabil untuk demo/pengumpulan | Merge hanya lewat pull request yang lulus CI |
| `develop` | Integrasi fitur sebelum stabil | Opsional bila tim memakai staging branch |

Format branch fitur:

```text
feature/<kode>-<nama-fitur>
fix/<kode>-<nama-bug>
refactor/<kode>-<nama-refactor>
docs/<kode>-<nama-dokumen>
```

Contoh:

```text
feature/ryh-booking-flow
feature/ryf-admin-jadwal
fix/nys-payment-verification
refactor/kvn-trip-status
docs/uas-documentation
```

Catatan: gunakan huruf kecil, tanda hubung, dan nama fitur yang singkat.

## Commit Convention

Format commit yang disarankan:

```text
<type>(<scope>): <pesan singkat>
```

Contoh:

```text
feat(booking): add booking review page
fix(payment): prevent duplicate dp verification notification
refactor(trip): move booking status sync to observer
docs(uas): add github action documentation
test(driver): cover trip pickup and completion flow
```

Tipe commit:

| Type | Keterangan |
| --- | --- |
| `feat` | Penambahan fitur |
| `fix` | Perbaikan bug |
| `refactor` | Perubahan struktur kode tanpa mengubah perilaku |
| `docs` | Perubahan dokumentasi |
| `test` | Penambahan/perubahan test |
| `chore` | Perubahan konfigurasi/dependency |

## Pull Request Workflow

1. Ambil task dari GitHub Issue atau Project Board.
2. Buat branch sesuai format.
3. Commit perubahan secara bertahap.
4. Update dokumentasi terkait jika fitur berubah.
5. Update `docs/CHANGELOG.MD`.
6. Push branch dan buat Pull Request.
7. Pastikan GitHub Action lulus.
8. Minta review minimal satu anggota yang bukan pembuat PR.
9. Perbaiki feedback review.
10. Merge ke `main` setelah disetujui.

## Review Matrix

| Pembuat PR | Reviewer utama | Fokus review |
| --- | --- | --- |
| Rayhan | Nayasha atau Kevin | Validasi booking, pembayaran pelanggan, notifikasi |
| Rayfo | Rayhan atau Nayasha | Query admin, data master, laporan |
| Nayasha | Rayfo atau Kevin | Status booking, verifikasi, observer, test |
| Kevin | Rayhan atau Rayfo | Trip, driver flow, kapasitas armada, manifest |

## Issue Label

| Label | Fungsi |
| --- | --- |
| `feature` | Fitur baru |
| `bug` | Perbaikan bug |
| `refactor` | Perbaikan struktur kode |
| `documentation` | Dokumentasi |
| `test` | Pengujian |
| `admin` | Modul admin |
| `customer` | Modul pelanggan |
| `driver` | Modul driver |
| `integration` | API eksternal seperti Fonnte/Leaflet |
| `priority-high` | Perlu dikerjakan segera |

## Project Board

Kolom board yang disarankan:

| Kolom | Makna |
| --- | --- |
| Backlog | Ide/task belum dipilih |
| Todo | Task siap dikerjakan |
| In Progress | Sedang dikerjakan pada branch |
| Review | Pull Request menunggu review |
| Testing | Perlu validasi manual/otomatis |
| Done | Sudah merge dan terdokumentasi |

## Definition of Done

Satu task dianggap selesai bila:

- Kode sudah berada di branch fitur.
- Test terkait sudah dibuat atau test regresi yang relevan sudah dijalankan.
- `npm run build` dan `php artisan test` tidak gagal untuk perubahan yang memengaruhi aplikasi.
- Dokumentasi fitur/dependency/instalasi diperbarui bila ada perubahan perilaku atau konfigurasi.
- Changelog diperbarui.
- Pull request sudah direview dan lulus GitHub Action.
