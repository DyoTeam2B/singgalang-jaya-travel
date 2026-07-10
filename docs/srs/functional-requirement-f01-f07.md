# BAB 4 Spesifikasi Kebutuhan Fungsional

Dokumen ini berisi spesifikasi kebutuhan fungsional (Functional Requirement) untuk Sistem Informasi Singgalang Jaya Travel. Spesifikasi ini disusun berdasarkan implementasi aktual kode program (Laravel 13) dan memetakan interaksi aktor terhadap sistem menggunakan format standar Software Requirements Specification (SRS) IEEE 830.

---

# 4.2 F01 – Akses Publik & Autentikasi Pengguna

## 4.2.1 Deskripsi dan Prioritas

Fitur Akses Publik & Autentikasi Pengguna membatasi hak akses sistem agar hanya pengguna terdaftar dan terautentikasi yang dapat menggunakan fitur transaksional dan administratif travel sesuai peran (*role*) masing-masing. Sistem menggunakan alamat email dan kata sandi (*password*) yang terdaftar di tabel `users` untuk memproses autentikasi. Setelah login berhasil, sistem secara otomatis mengarahkan pengguna ke halaman kerja sesuai hak aksesnya (*role redirection*) menggunakan role middleware. Pelanggan baru juga dapat mendaftarkan diri secara mandiri melalui form registrasi.

*   **Tujuan Fitur**: Mengamankan rute transaksi travel dan membagi zona kerja pengguna berdasarkan peran pelanggan, administrator, pengemudi, atau tamu (guest).
*   **Deskripsi Fitur**: Meliputi pendaftaran akun pelanggan baru, otentikasi login multi-peran, pengalihan rute otomatis pasca-login, penolakan kredensial yang salah, pembatasan jumlah percobaan login gagal (*rate limiting*), dan penghancuran sesi (*logout*).
*   **Aktor**: Pelanggan, Admin, Driver, Guest (tamu)
*   **Hubungan dengan Proses Bisnis**: Menjamin keamanan akses data master bagi Admin, manifest tugas bagi Driver, serta riwayat reservasi pribadi bagi Pelanggan.
*   **Daftar Use Case**:
    *   UC-01 Registrasi Pelanggan
    *   UC-02 Login Pelanggan
    *   UC-03 Login Admin
    *   UC-04 Login Driver
    *   UC-05 Logout
*   **Prioritas**: Kritis (*Critical*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 9 | Sangat penting untuk mengamankan data pengguna, memisahkan dashboard admin/driver/pelanggan secara teratur. |
| Penalty | 9 | Tanpa otentikasi dan otorisasi yang aman, siapa saja bisa mengakses dan memanipulasi data operasional penting travel. |
| Cost | 4 | Laravel Breeze dan role middleware menyediakan struktur dasar yang stabil dan efisien untuk diimplementasikan. |
| Risk | 3 | Risiko keamanan sesi login atau SQL injection rendah karena dilindungi oleh security layer bawaan Laravel. |

## 4.2.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-01]** Guest membuka halaman registrasi. | Sistem menampilkan form registrasi akun pelanggan baru yang berisi input nama lengkap, email, password, konfirmasi password, dan nomor HP/WhatsApp. |
| **[UC-01]** Guest mengisi form registrasi dan menekan tombol "Register". | Sistem memvalidasi input: nama lengkap wajib diisi, email wajib diisi (berformat email, lowercase, unik di tabel users), password wajib diisi (minimal 8 karakter, harus cocok dengan konfirmasi password), dan no_hp wajib diisi (maksimal 20 karakter). Jika lolos validasi, sistem memulai transaksi database: 1) membuat data di tabel `users` dengan role `pelanggan`, 2) membuat profil pelanggan di tabel `pelanggan` yang terhubung ke `user_id`, 3) melakukan login otomatis ke akun baru, 4) mengarahkan pelanggan ke halaman utama publik dengan alert sukses. Jika gagal, sistem menampilkan pesan kesalahan validasi pada kolom terkait. |
| **[UC-02]** Guest/Pelanggan mengklik tautan "Login" di menu navigasi. | Sistem menampilkan form login dengan input email dan password. |
| **[UC-02]** Pelanggan memasukkan email dan password akun ber-role `pelanggan` yang valid, lalu menekan tombol "Log in". | Sistem memvalidasi input. Setelah sukses mencocokkan kredensial di database dan meregenerasi session ID baru, sistem mengarahkan pelanggan kembali ke halaman utama publik (`/`) dalam keadaan terautentikasi. |
| **[UC-03]** Admin memasukkan email dan password akun ber-role `admin` yang valid pada form login, lalu menekan tombol "Log in". | Sistem memvalidasi input. Setelah sukses memverifikasi kredensial dan meregenerasi session ID baru, sistem mendeteksi role `admin` dan mengarahkan admin ke halaman dashboard admin (`/admin/dashboard`). |
| **[UC-04]** Driver memasukkan email dan password akun ber-role `driver` yang valid pada form login, lalu menekan tombol "Log in". | Sistem memvalidasi input. Setelah sukses memverifikasi kredensial dan meregenerasi session ID baru, sistem mendeteksi role `driver` dan mengarahkan driver ke halaman dashboard driver (`/driver/dashboard`). |
| **[UC-02, UC-03, UC-04]** Pengguna memasukkan email atau password yang tidak terdaftar/salah, lalu menekan tombol "Log in". | Sistem mencatat satu kegagalan login (*rate limiting hit*), menolak otentikasi, lalu menampilkan kembali halaman login dengan pesan kesalahan *"Kredensial tidak cocok dengan data kami"* di bawah input email, serta mengosongkan input password. |
| **[UC-02, UC-03, UC-04]** Pengguna melakukan percobaan login yang gagal sebanyak lebih dari 5 kali berturut-turut. | Sistem mendeteksi percobaan login berlebihan (*rate limiting hit*), memicu event Lockout, memblokir alamat IP dan email pengguna sementara waktu, serta menampilkan pesan kesalahan *"Terlalu banyak percobaan masuk. Silakan coba lagi dalam X detik."* |
| **[UC-05]** Pengguna (Pelanggan/Admin/Driver) mengklik tombol "Log Out" di menu navigasi. | Sistem mengeluarkan akun pengguna dari state login, menghancurkan data sesi (*session*) aktif dari server, meregenerasi token perlindungan CSRF, dan mengarahkan kembali pengguna ke halaman utama publik (`/`). |

## 4.2.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.2.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.3 F02 – Manajemen Data Master

## 4.3.1 Deskripsi dan Prioritas

Fitur Manajemen Data Master memfasilitasi Administrator (Admin) untuk mengelola data operasional utama yang menjadi fondasi operasional travel. Data operasional utama tersebut mencakup Rute (kota asal, tujuan, tarif dasar), Armada (nama mobil, plat nomor, kapasitas kursi, status armada), Driver (profil pengemudi beserta pembuatan akun login sistem), dan Jadwal Keberangkatan (rute perjalanan, tanggal pergi, jam berangkat, pembagian shift pagi/malam, kuota penumpang, dan status keaktifan jadwal).

Manajemen Data Master berfungsi sebagai penyedia data acuan (*lookup data*) yang bersifat wajib sebelum proses transaksi pemesanan (*booking*) oleh pelanggan dan pembentukan lembar perjalanan (*trip*) oleh admin dapat dilakukan. Fitur ini dilengkapi dengan sistem validasi terpusat (menggunakan Laravel Form Request) dan proteksi integritas referensi data (*safety checks*) untuk mencegah penghapusan data master yang masih terikat pada transaksi aktif.

*   **Tujuan Fitur**: Menyediakan dan mengelola parameter operasional dasar travel (rute, tarif, armada, driver, jadwal) secara dinamis dan aman.
*   **Deskripsi Fitur**: Meliputi operasi Create, Read, Update, Delete (CRUD), validasi input form rute/armada/driver/jadwal, pengamanan integritas data referensial dari penghapusan ilegal, status toggle jadwal (aktif/nonaktif/penuh), dan pencarian/penyaringan data master.
*   **Aktor**: Admin
*   **Hubungan dengan Proses Bisnis**: Admin menggunakan data master untuk menetapkan armada, pengemudi, dan shift keberangkatan harian yang kemudian dipilih oleh pelanggan saat memesan tiket.
*   **Daftar Use Case**:
    *   UC-13 Mengelola Rute
    *   UC-14 Mengelola Armada
    *   UC-15 Mengelola Driver
    *   UC-16 Mengelola Jadwal Keberangkatan
*   **Prioritas**: Tinggi (*High*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 9 | Seluruh proses operasional bergantung pada data master yang valid dan konsisten. |
| Penalty | 8 | Kesalahan pada data master dapat menyebabkan kesalahan jadwal, penugasan driver, maupun proses booking pelanggan. |
| Cost | 5 | Implementasi melibatkan beberapa modul CRUD, validasi data, serta relasi antar tabel pada basis data. |
| Risk | 4 | Risiko relatif rendah karena dapat diminimalkan melalui validasi data, foreign key, dan pembatasan hak akses admin. |

## 4.3.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-13]** Admin mengakses menu "Rute & Tarif". | Sistem menampilkan daftar rute perjalanan terpaginasi (10 data per halaman) beserta kolom pencarian asal/tujuan rute. |
| **[UC-13]** Admin mencari rute dengan memasukkan nama kota asal/tujuan pada kolom pencarian dan menekan "Cari". | Sistem memfilter dan memuat data rute yang cocok berdasarkan kata kunci kota asal atau kota tujuan yang dimasukkan. |
| **[UC-13]** Admin menekan tombol "Tambah Rute". | Sistem menampilkan form tambah rute dengan input kota asal, kota tujuan, dan tarif dasar rute. |
| **[UC-13]** Admin mengisi kota asal, kota tujuan, tarif dasar, lalu menekan tombol "Simpan". | Sistem memvalidasi input: asal wajib diisi, tujuan wajib diisi (harus berbeda dari kota asal), dan tarif wajib berupa angka non-negatif. Jika valid, sistem menyimpan data rute baru ke database, mengarahkan kembali ke halaman rute, dan menampilkan pesan sukses *"Rute baru berhasil ditambahkan."* Jika gagal, sistem menampilkan pesan kesalahan validasi pada field terkait. |
| **[UC-13]** Admin menekan tombol "Edit" pada salah satu rute, mengubah data, lalu menekan "Update". | Sistem memvalidasi input baru. Jika lolos, sistem memperbarui data rute di database, mengarahkan kembali ke halaman rute, dan menampilkan pesan sukses *"Data rute berhasil diperbarui."* |
| **[UC-13]** Admin menekan tombol "Hapus" pada salah satu rute. | Sistem mengecek apakah rute tersebut terikat dengan jadwal keberangkatan (`jadwal()->exists()`). Jika ya, sistem menolak penghapusan, mengarahkan ke halaman rute, dan menampilkan pesan error *"Rute tidak dapat dihapus karena memiliki jadwal keberangkatan terkait."* Jika tidak terikat, sistem menghapus rute dari database dan menampilkan pesan sukses *"Rute berhasil dihapus."* |
| **[UC-14]** Admin mengakses menu "Kelola Data Armada". | Sistem memuat daftar armada travel secara terpaginasi (10 data per halaman) dan menampilkan panel detail untuk armada pertama/terpilih. |
| **[UC-14]** Admin menekan tombol "Tambah Armada", mengisi data (nama mobil, nomor plat, kapasitas kursi, status armada), dan menekan "Simpan". | Sistem memvalidasi input: nama mobil wajib diisi, nomor plat wajib diisi (harus unik), kapasitas wajib diisi (angka 1-20), dan status armada wajib dipilih (aktif/nonaktif). Jika lolos validasi, sistem menyimpan data armada ke database, mengarahkan kembali ke daftar armada dengan menandai armada baru tersebut (`selected_id`), dan menampilkan pesan sukses *"Armada baru berhasil ditambahkan."* |
| **[UC-14]** Admin menekan tombol "Edit" pada armada terpilih, mengubah data, lalu menekan "Simpan Perubahan". | Sistem memvalidasi data masukan baru (nomor plat divalidasi unik kecuali untuk armada yang sedang diedit). Jika lolos, sistem memperbarui data armada di database dan menampilkan pesan sukses *"Data armada berhasil diperbarui."* |
| **[UC-14]** Admin menekan tombol "Hapus" pada armada terpilih. | Sistem mengecek apakah armada terhubung dengan driver (`driver()->exists()`) atau sedang bertugas dalam trip aktif berstatus ready/on_trip (`trips()->whereIn('status_trip', ['ready', 'on_trip'])->exists()`). Jika terhubung driver, sistem menolak dengan pesan error *"Armada tidak dapat dihapus karena masih digunakan oleh driver."* Jika bertugas dalam trip aktif, sistem menolak dengan pesan error *"Armada tidak dapat dihapus karena sedang bertugas dalam trip aktif."* Jika lolos, sistem menghapus armada dari database dan menampilkan pesan sukses *"Armada berhasil dihapus."* |
| **[UC-15]** Admin mengakses menu "Data Driver". | Sistem menampilkan daftar driver secara terpaginasi (10 data per halaman) beserta status keaktifan dan armada yang terhubung. |
| **[UC-15]** Admin menekan tombol "Tambah Driver", mengisi form (nama driver, email, password, no HP, armada ID, status driver), lalu menekan "Simpan". | Sistem memvalidasi input: nama_driver wajib diisi, email wajib diisi (berformat email, unik di tabel users), password wajib diisi (minimal 8 karakter), no_hp wajib diisi, armada_id wajib dipilih, dan status_driver wajib ditentukan. Jika lolos, sistem memulai transaksi database untuk membuat akun login di tabel `users` (role `driver`) dan profil driver di tabel `drivers`. Setelah sukses, sistem mengarahkan ke halaman daftar driver dan menampilkan pesan sukses *"Driver baru dan akun login berhasil ditambahkan."* |
| **[UC-15]** Admin memilih salah satu driver, mengklik "Edit", mengubah data (termasuk opsi kata sandi), dan menekan "Simpan Perubahan". | Sistem memvalidasi data masukan baru (email unik kecuali untuk user ID driver bersangkutan; password bersifat opsional dengan minimal 8 karakter jika diisi). Jika lolos, sistem melakukan transaksi database untuk memperbarui user login dan profil driver secara bersamaan, lalu menampilkan pesan sukses *"Data driver berhasil diperbarui."* |
| **[UC-15]** Admin menekan tombol "Hapus" pada driver terpilih. | Sistem mengecek apakah driver sedang ditugaskan dalam trip aktif berstatus ready/on_trip. Jika ya, sistem menolak dengan pesan error *"Driver tidak dapat dihapus karena sedang bertugas dalam trip aktif."* Jika tidak, sistem memulai transaksi database untuk menghapus akun login di tabel `users` (memicu cascade-delete menghapus baris profil driver terkait di tabel `drivers`), lalu mengarahkan ke daftar driver dengan pesan sukses *"Data driver beserta akun login berhasil dihapus."* |
| **[UC-16]** Admin mengakses menu "Kelola Jadwal". | Sistem menampilkan daftar jadwal keberangkatan terpaginasi (9 data per halaman) dengan pembagian tab "Jadwal Aktif" dan "Riwayat Jadwal". |
| **[UC-16]** Admin menekan tombol "Tambah Jadwal", mengisi form (rute ID, tanggal keberangkatan, shift pagi/malam, jam berangkat, kuota, status jadwal), lalu menekan "Simpan". | Sistem memvalidasi input: rute_id wajib dipilih, tanggal_keberangkatan wajib diisi (tidak boleh di masa lalu), shift wajib dipilih, jam_berangkat wajib diisi dengan format HH:MM, kuota wajib diisi (minimal 1), dan status_jadwal wajib ditentukan. Jika lolos validasi, sistem menyimpan data jadwal keberangkatan ke database, mengarahkan kembali ke daftar jadwal, dan menampilkan pesan sukses *"Jadwal keberangkatan baru berhasil ditambahkan."* |
| **[UC-16]** Admin menekan tombol "Edit" pada salah satu jadwal, mengubah kuota penumpang atau detail lainnya, lalu menekan "Simpan Perubahan". | Sistem memvalidasi input baru. Sistem secara khusus mengecek apakah kapasitas kuota baru lebih kecil dari jumlah kursi yang sudah dipesan oleh pelanggan (`jumlah_penumpang` dari booking aktif). Jika kuota baru lebih kecil, sistem menolak perubahan dan menampilkan pesan error *"Kapasitas tidak boleh lebih kecil dari jumlah kursi yang sudah dipesan (X kursi)."* Jika kuota baru valid (>= kursi terpesan), sistem memperbarui data jadwal di database. Jika kursi terpesan pas mencapai kuota baru dan status jadwal 'aktif', sistem otomatis mengubah status menjadi 'penuh'. Sistem mengarahkan ke daftar jadwal dengan pesan sukses *"Data jadwal keberangkatan berhasil diperbarui."* |
| **[UC-16]** Admin menekan tombol "Toggle Status" (Aktif/Nonaktif) pada salah satu jadwal keberangkatan. | Sistem membaca status jadwal saat ini. Jika status awal 'aktif', sistem mengubah status menjadi 'nonaktif'. Jika status awal 'nonaktif', sistem menghitung jumlah kursi terpesan pada jadwal tersebut. Jika jumlah kursi terpesan >= kuota jadwal, status diubah menjadi 'penuh'; jika tidak, status diubah menjadi 'aktif'. Sistem memperbarui status jadwal keberangkatan di database dan menampilkan pesan sukses *"Status jadwal berhasil diubah menjadi [Nama Status Baru]."* |
| **[UC-16]** Admin menekan tombol "Hapus" pada salah satu jadwal keberangkatan. | Sistem mengecek apakah jadwal tersebut memiliki data booking terkait (`bookings()->exists()`). Jika ya, sistem menolak penghapusan dan menampilkan pesan error *"Jadwal tidak dapat dihapus karena memiliki data booking terkait."* Jika tidak memiliki booking terkait, sistem menghapus jadwal dari database dan menampilkan pesan sukses *"Jadwal keberangkatan berhasil dihapus."* |

## 4.3.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.3.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.4 F03 – Manajemen Booking Pelanggan

## 4.4.1 Deskripsi dan Prioritas

Fitur Manajemen Booking Pelanggan memfasilitasi pelanggan terautentikasi untuk mencari jadwal perjalanan yang tersedia, melakukan pemesanan travel (*booking*), menentukan lokasi penjemputan presisi menggunakan peta digital interaktif, dan memantau riwayat pemesanan mereka secara real-time. Sistem menggunakan Livewire component `BookingForm` untuk memberikan pengalaman pemesanan interaktif, termasuk perhitungan total tarif (tarif dasar rute x jumlah penumpang) dan validasi sisa kursi secara real-time.

Untuk menjaga ketersediaan kuota kursi, sistem menerapkan aturan pencegahan pemesanan duplikat pada jadwal yang sama untuk booking aktif. Setiap booking baru yang tercipta otomatis mendapatkan batas waktu pembayaran DP selama 30 menit (diatur pada kolom `expired_at`). Jika batas waktu terlampaui tanpa adanya unggahan bukti DP, sistem (via scheduled job `booking:expire` yang berjalan setiap menit) otomatis membatalkan booking tersebut dan mengubah statusnya menjadi `expired` guna membebaskan kuota kursi kembali.

*   **Tujuan Fitur**: Memudahkan pelanggan dalam melakukan reservasi kursi travel secara aman, cepat, dan presisi.
*   **Deskripsi Fitur**: Meliputi pencarian/penyaringan jadwal travel publik, pengisian form booking terintegrasi peta Leaflet untuk penentuan titik jemput dan pengantaran, pencegahan duplikasi booking aktif, kalkulasi otomatis tarif, pembatalan booking mandiri oleh pelanggan (disertai pengisian alasan pembatalan), melihat daftar booking aktif dan riwayat histori perjalanan, serta pemantauan hitung mundur kedaluwarsa pembayaran.
*   **Aktor**: Pelanggan, Guest (hanya melihat jadwal publik dan cek status booking dengan kode tanpa login)
*   **Hubungan dengan Proses Bisnis**: Menjadi pintu masuk utama arus pendapatan perusahaan travel dengan mencatat pesanan kursi dari pelanggan sebelum dikunci melalui pembayaran DP.
*   **Daftar Use Case**:
    *   UC-06 Melihat Jadwal Travel
    *   UC-07 Melakukan Booking Travel
    *   UC-08 Menentukan Titik Jemput dan Tujuan
    *   UC-11 Melihat Riwayat Booking
*   **Prioritas**: Kritis (*Critical*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 9 | Mempermudah pelanggan memesan tiket secara online secara real-time dan meningkatkan okupansi travel. |
| Penalty | 9 | Kegagalan modul booking berakibat langsung pada hilangnya potensi transaksi pemesanan dari pelanggan. |
| Cost | 6 | Memerlukan komponen Livewire dinamis untuk kalkulasi tarif reaktif, validasi sisa kursi secara real-time, dan integrasi Leaflet map picker. |
| Risk | 5 | Risiko race condition atau pemesanan kursi ganda jika ada pemesanan bersamaan, tetapi dapat dikendalikan dengan status check. |

## 4.4.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-06]** Guest/Pelanggan mengakses menu "Jadwal" di beranda. | Sistem menampilkan halaman pencarian jadwal dengan input asal, tujuan, tanggal, dan shift. Sistem memuat daftar jadwal aktif terpaginasi (9 data per halaman). Jadwal yang ditampilkan adalah jadwal berstatus bukan nonaktif, belum kedaluwarsa secara tanggal/jam, dan tidak memiliki trip terkait yang berstatus on_trip/completed. |
| **[UC-06]** Guest/Pelanggan mengisi kota asal, tujuan, tanggal, dan shift pada form pencarian, lalu menekan tombol "Cari". | Sistem menyaring data jadwal di database berdasarkan kriteria pencarian dan memuat data yang sesuai (tarif per orang, sisa kuota kursi, jenis armada, jam berangkat). Jika tidak ada jadwal yang cocok, sistem menampilkan pesan *"Jadwal keberangkatan tidak tersedia untuk tanggal atau rute ini."* |
| **[UC-07]** Pelanggan terautentikasi menekan tombol "Pesan" pada jadwal yang diinginkan. | Sistem membuka halaman form pembuatan booking travel dan secara otomatis mengunci data rute dan tanggal keberangkatan jadwal terpilih pada komponen `BookingForm`. |
| **[UC-07]** Pelanggan mengisi data penumpang (nama, no HP, jumlah penumpang), alamat penjemputan, dan alamat tujuan. | Sistem secara reaktif memvalidasi sisa kuota kursi pada jadwal. Jika jumlah penumpang melebihi kuota tersedia, sistem menampilkan error validasi. Jika aman, sistem mengkalkulasi otomatis total harga (tarif rute x jumlah penumpang) dan menampilkannya di halaman form. |
| **[UC-08]** Pelanggan mengklik tombol map picker pada kolom alamat jemput/tujuan. | Sistem membuka modul peta Leaflet interaktif. |
| **[UC-08]** Pelanggan meletakkan pin penanda lokasi pada peta digital. | Sistem secara otomatis mengambil koordinat latitude dan longitude dari posisi pin peta dan menyimpannya ke dalam input koordinat form secara tersembunyi. Pelanggan menyimpan koordinat dengan menekan "Simpan Titik Peta". |
| **[UC-07]** Pelanggan menekan tombol "Pesan Sekarang". | Sistem memvalidasi input data. Sistem mengecek ketersediaan kursi di database secara realtime dan mengecek apakah pelanggan telah memiliki booking aktif pada jadwal yang sama. Jika valid, sistem memanggil `BookingService::createBooking()`, melakukan transaksi database: 1) menyimpan record `bookings` dengan status `booking_dibuat`, 2) memotong kuota kursi secara berkala, 3) mengatur `expired_at` (sekarang + 30 menit), 4) mengarahkan pelanggan ke halaman review booking dengan pesan sukses. Jika gagal, sistem menampilkan pesan kesalahan validasi. |
| **[UC-11]** Pelanggan mengakses menu "Booking Saya". | Sistem memuat dan menampilkan daftar booking aktif pelanggan (tab aktif) dan riwayat booking terdahulu (tab histori) yang terpaginasi. |
| **[UC-11]** Pelanggan menekan tombol "Detail" pada salah satu booking miliknya. | Sistem memuat informasi lengkap booking (kode booking, jadwal, rute, total harga, status, alamat jemput/tujuan, timeline pemesanan, data bukti pembayaran DP, dan detail driver/armada jika trip sudah terbentuk). |

## 4.4.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.4.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.5 F04 – Manajemen Pembayaran DP

## 4.5.1 Deskripsi dan Prioritas

Fitur Manajemen Pembayaran DP memproses uang muka (*down payment*) sebesar flat Rp50.000 yang wajib dibayarkan oleh pelanggan guna memvalidasi pemesanan travel yang telah dibuat. Pelanggan mengunggah bukti transfer bank eksternal ke sistem dalam format gambar. Setelah bukti diunggah, status pemesanan diperbarui menjadi `menunggu_verifikasi`, dan Admin dapat memeriksa kesesuaian gambar bukti dengan mutasi rekening bank perusahaan.

Proses verifikasi oleh Admin dilakukan melalui transaksi database yang memperbarui status pembayaran dan status booking secara aman. Jika Admin menerima bukti transfer (Terima DP), status booking diperbarui menjadi `dikonfirmasi` (booking aktif) dan sistem secara otomatis mengirimkan notifikasi WhatsApp konfirmasi pembayaran sukses ke nomor pelanggan. Jika bukti transfer ditolak (Tolak DP), Admin wajib mengisi catatan alasan penolakan, status booking dikembalikan menjadi `booking_dibuat`, dan sistem mengirimkan notifikasi WhatsApp penolakan agar pelanggan melakukan unggah ulang bukti pembayaran yang valid.

*   **Tujuan Fitur**: Memvalidasi transaksi pemesanan secara finansial melalui uang muka dan mengunci kursi secara permanen di jadwal travel.
*   **Deskripsi Fitur**: Meliputi pengunggahan gambar bukti transfer DP oleh pelanggan, pengunggahan ulang bukti DP baru jika bukti sebelumnya ditolak, pemeriksaan detail pembayaran oleh admin, persetujuan/verifikasi DP (verifikasi sukses, update status booking ke dikonfirmasi, kirim notifikasi WhatsApp sukses), dan penolakan DP (simpan alasan penolakan, update status booking kembali ke booking_dibuat, kirim notifikasi WhatsApp penolakan).
*   **Aktor**: Pelanggan, Admin
*   **Hubungan dengan Proses Bisnis**: Memisahkan antara booking sementara (*booking_dibuat*) dengan booking terkonfirmasi (*dikonfirmasi*) yang siap dialokasikan ke armada keberangkatan.
*   **Daftar Use Case**:
    *   UC-09 Mengunggah Bukti Pembayaran DP
    *   UC-10 Mengunggah Ulang Bukti Pembayaran DP
    *   UC-12 Memverifikasi Pembayaran DP
*   **Prioritas**: Kritis (*Critical*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 8 | Mengurangi risiko pembatalan sepihak dan memastikan komitmen finansial pelanggan sebelum trip dijadwalkan. |
| Penalty | 8 | Tanpa verifikasi DP, kuota kursi pada jadwal travel dapat tersumbat oleh pesanan fiktif yang tidak dibayar. |
| Cost | 5 | Melibatkan unggah file bukti transfer, verifikasi manual oleh admin, pencatatan transaksi pembayaran, dan pengiriman notifikasi otomatis. |
| Risk | 4 | Risiko pemalsuan bukti transfer oleh pelanggan, sehingga verifikasi mutasi bank riil oleh admin menjadi kunci utama. |

## 4.5.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-09]** Pelanggan membuka detail booking yang berstatus `booking_dibuat`, lalu menekan tombol "Unggah Bukti Pembayaran". | Sistem menampilkan form unggah bukti pembayaran yang memuat nominal DP (Rp50.000), nomor rekening bank tujuan transfer, input nama pengirim, input metode pembayaran, dan input file bukti transfer. |
| **[UC-09]** Pelanggan mengunggah file bukti transfer (jpg/png) dan menekan "Simpan Pembayaran". | Sistem memvalidasi input: file gambar wajib diunggah (maksimal 2MB, format gambar valid). Jika lolos, sistem menyimpan gambar ke direktori storage, membuat record baru di tabel `pembayaran` (tipe `dp`, status `menunggu`), mengubah status booking menjadi `menunggu_verifikasi`, lalu mengarahkan kembali ke halaman detail booking dengan alert sukses. |
| **[UC-10]** Pelanggan membuka detail booking yang status DP-nya ditolak oleh admin (booking kembali berstatus `booking_dibuat`), lalu menekan tombol "Unggah Ulang Bukti Pembayaran". | Sistem menampilkan form unggah bukti pembayaran baru. |
| **[UC-10]** Pelanggan mengunggah file bukti transfer baru dan menekan "Simpan Pembayaran". | Sistem memvalidasi file gambar baru. Jika lolos, sistem mengunggah file baru, memperbarui record pembayaran sebelumnya dengan file baru dan mereset status pembayaran menjadi `menunggu`, mengubah status booking kembali menjadi `menunggu_verifikasi`, lalu mengarahkan kembali ke detail booking dengan alert sukses. |
| **[UC-12]** Admin mengakses menu "Verifikasi Pembayaran" di dashboard admin. | Sistem menampilkan halaman Livewire table `PembayaranTable` yang memuat seluruh transaksi pembayaran berstatus `menunggu`. |
| **[UC-12]** Admin mengklik detail salah satu pembayaran. | Sistem menampilkan data pembayaran lengkap, preview gambar bukti transfer secara jelas, serta informasi detail booking (kode booking, rute, jumlah penumpang, total harga). |
| **[UC-12]** Admin menekan tombol "Verifikasi" (Terima DP). | Sistem memverifikasi bahwa booking belum kedaluwarsa. Sistem menjalankan transaksi database untuk: 1) memperbarui status record pembayaran menjadi `terverifikasi`, 2) memperbarui status booking terkait menjadi `dikonfirmasi`, 3) memicu `BookingWhatsappNotificationService` untuk mengirimkan notifikasi WhatsApp konfirmasi pembayaran sukses ke nomor HP pelanggan. Sistem mengarahkan kembali ke daftar pembayaran dengan alert sukses. |
| **[UC-12]** Admin menekan tombol "Tolak" (Tolak DP), mengisi alasan penolakan pada input catatan modal, lalu menekan "Kirim". | Sistem memvalidasi alasan penolakan wajib diisi. Jika lolos, sistem menjalankan transaksi database untuk: 1) memperbarui status record pembayaran menjadi `ditolak` dan menyimpan alasan penolakan di kolom catatan, 2) mengembalikan status booking terkait menjadi `booking_dibuat` agar pelanggan bisa melakukan upload ulang, 3) memicu `BookingWhatsappNotificationService` untuk mengirimkan notifikasi WhatsApp penolakan pembayaran beserta catatan alasan ke pelanggan. Sistem mengarahkan kembali ke daftar pembayaran dengan alert sukses. |

## 4.5.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.5.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.6 F05 – Manajemen Operasional Trip

## 4.6.1 Deskripsi dan Prioritas

Fitur Manajemen Operasional Trip memfasilitasi Administrator (Admin) untuk menyusun rencana perjalanan riil (*trip*) berdasarkan jadwal keberangkatan harian, menugaskan pengemudi (*driver*) dan armada kendaraan, serta mengalokasikan pesanan pelanggan yang telah lunas DP (*booking dikonfirmasi*) ke dalam manifest penumpang. Sistem menerapkan validasi ketat untuk menghindari bentrok jadwal pengemudi dan armada pada tanggal keberangkatan dan shift yang sama.

Pengalokasian penumpang ke dalam trip dilakukan secara interaktif menggunakan panel alokasi. Sistem memvalidasi sisa kapasitas tempat duduk armada sebelum booking dapat dimasukkan ke dalam trip. Setiap manipulasi manifest penumpang (tambah/hapus) secara otomatis mengalir memperbarui status booking terkait via detail trip observer (`DetailTripObserver`). Admin juga dapat melakukan pembatalan booking secara administratif jika terjadi kendala operasional, yang akan otomatis melepas kuota kursi pada jadwal keberangkatan terkait.

*   **Tujuan Fitur**: Mengoordinasikan alokasi penumpang terkonfirmasi, armada kendaraan, dan driver ke dalam lembar perjalanan harian secara teratur dan bebas bentrok.
*   **Deskripsi Fitur**: Meliputi pembatalan booking administratif oleh admin (disertai pencatatan alasan dan pengembalian kuota jadwal), pembentukan trip baru dari jadwal keberangkatan, penugasan driver aktif (beserta deteksi konflik jadwal/shift driver & armada), alokasi booking terkonfirmasi ke dalam manifes trip (dilengkapi validasi kapasitas sisa armada dan rute jadwal yang cocok), penghapusan booking dari manifes trip (mengembalikan status booking ke dikonfirmasi), dan update status trip administratif.
*   **Aktor**: Admin
*   **Hubungan dengan Proses Bisnis**: Menjembatani antara data pesanan pelanggan yang telah lunas DP dengan pelaksanaan pengantaran perjalanan riil yang akan dijalankan oleh driver.
*   **Daftar Use Case**:
    *   UC-17 Mengelola Booking
    *   UC-18 Pembentukan Trip
    *   UC-19 Assign Driver dan Armada ke Trip
    *   UC-20 Memasukkan Booking ke Trip
*   **Prioritas**: Tinggi (*High*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 9 | Memungkinkan pengalokasian manifest, pengemudi, dan armada travel terstruktur dengan validasi pencegahan bentrok shift. |
| Penalty | 9 | Ketidakmampuan menyusun trip menghambat keberangkatan travel secara fisik dan mengacaukan operasional di lapangan. |
| Cost | 6 | Memerlukan join relasi yang kompleks, form assignment driver/armada dengan logic filter kesibukan driver, serta listener cascade status. |
| Risk | 5 | Risiko kekeliruan alokasi driver/armada jika terjadi perubahan jadwal mendadak yang memicu ketidakpuasan pelanggan. |

## 4.6.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-17]** Admin mengakses menu "Manajemen Booking" di panel admin. | Sistem menampilkan Livewire table `BookingTable` berisi seluruh daftar booking, filter status booking, dan kolom pencarian. |
| **[UC-17]** Admin mengklik detail salah satu booking dan menekan tombol "Batalkan Booking", mengisi alasan pembatalan pada input modal, lalu mengklik "Konfirmasi". | Sistem memvalidasi alasan pembatalan wajib diisi. Jika lolos, sistem memperbarui status booking menjadi `cancelled`, menyimpan alasan pembatalan di kolom alasan, memicu recalculate status jadwal keberangkatan (`checkAndUpdateStatus()` membebaskan kuota kursi), memicu notifikasi WhatsApp pembatalan ke pelanggan, lalu me-refresh halaman detail booking dengan pesan sukses. |
| **[UC-18]** Admin mengakses menu "Manajemen Trip". | Sistem menampilkan daftar trip terpaginasi (10 data per halaman) beserta status trip (new/ready/on_trip/completed/cancelled) dan filter status. |
| **[UC-18]** Admin menekan tombol "Tambah Trip Baru". | Sistem menampilkan form tambah trip dengan drop-down pilihan jadwal keberangkatan aktif dan pilihan driver aktif. |
| **[UC-18]** Admin memilih jadwal keberangkatan dan driver aktif, lalu menekan tombol "Simpan". | Sistem memvalidasi data input. Jika lolos, sistem menyimpan record trip baru dengan status awal `new` dan secara otomatis mengasosiasikan armada aktif yang melekat pada driver tersebut ke dalam trip. Sistem mengarahkan ke daftar trip dengan alert sukses *"Trip berhasil dibuat."* |
| **[UC-19]** Admin menekan tombol "Edit" pada salah satu trip berstatus `new`. | Sistem menampilkan form edit trip yang memuat daftar pilihan driver aktif beserta armadanya. |
| **[UC-19]** Admin mengganti driver pada form edit trip dan menekan tombol "Update Trip". | Sistem melakukan pengecekan konflik jadwal: sistem memeriksa apakah driver atau armada bawaan driver tersebut sudah ditugaskan pada trip lain (kecuali trip yang sedang diedit dan trip berstatus cancelled) pada tanggal keberangkatan dan kategori shift yang sama (`whereConflictingSchedule()`). Jika terdeteksi bentrok, sistem menolak pembaruan dan menampilkan pesan error konflik. Jika aman, sistem memperbarui data driver dan armada pada record trip, lalu mengarahkan ke daftar trip dengan alert sukses *"Data trip berhasil diperbarui."* |
| **[UC-20]** Admin membuka halaman detail trip berstatus `new` atau `ready`. | Halaman detail trip dimuat, menampilkan panel manifest penumpang saat ini dan panel samping berisi daftar booking berstatus `dikonfirmasi` yang memiliki rute dan jadwal keberangkatan yang sama dengan trip. |
| **[UC-20]** Admin memilih salah satu booking terkonfirmasi pada panel samping dan menekan tombol "Assign ke Trip". | Sistem menghitung kapasitas armada bawaan trip dan menjumlahkan kapasitas terisi saat ini. Jika jumlah penumpang pada booking melebihi sisa kapasitas armada, sistem menolak penugasan dan menampilkan alert error kapasitas penuh. Jika sisa kapasitas mencukupi, sistem membuat record baru di tabel `detail_trips` (relasi trip dan booking). Pembuatan detail trip ini secara otomatis memicu `DetailTripObserver` untuk memperbarui status booking terkait menjadi `assigned_to_trip` di database. Sistem juga memicu WhatsApp notification kepada pelanggan bahwa pesanan mereka telah dijadwalkan masuk trip beserta info driver/armada. Halaman dimuat ulang memperbarui manifest teraktif. |
| **[UC-20]** Admin menekan tombol "Remove" pada salah satu manifest penumpang di detail trip. | Sistem menghapus record `detail_trips` terkait. Penghapusan ini memicu `DetailTripObserver` untuk secara otomatis mengembalikan status booking terkait menjadi `dikonfirmasi` di database. Sistem memperbarui kapasitas armada dan me-refresh halaman detail trip dengan alert sukses. |
| **[UC-18]** Admin menekan tombol "Hapus Trip" pada trip berstatus `new`. | Sistem memulai transaksi database: menghapus semua record `detail_trips` pada trip tersebut (yang otomatis mengembalikan seluruh status booking manifest menjadi `dikonfirmasi`), menghapus record trip dari database, mengarahkan kembali ke daftar trip, dan menampilkan pesan sukses. |

## 4.6.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.6.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.7 F06 – Operasional Driver

## 4.7.1 Deskripsi dan Prioritas

Fitur Operasional Driver memfasilitasi pengemudi (*driver*) travel untuk mengelola dan mengeksekusi perjalanan (*trip*) yang ditugaskan kepada mereka secara mandiri melalui perangkat seluler/aplikasi. Driver dapat memantau tugas trip aktif hari ini, melihat manifes manifest penumpang lengkap dengan alamat jemput/antar dan navigasi peta interaktif, serta mencatat status perjalanan penumpang secara real-time.

Proses operasional trip diatur dalam state machine yang ketat di bawah kendali driver. Driver hanya dapat memulai trip (*Start Trip*) jika manifest trip memenuhi syarat minimal keterisian (minimal 3 penumpang). Ketika melakukan pengantaran penumpang (*Drop-off*), sistem secara otomatis melakukan proses pencatatan pelunasan tunai jika penumpang belum lunas pembayaran tiketnya (menciptakan record pembayaran pelunasan cash senilai total tagihan dikurangi DP Rp50.000). Driver hanya dapat menyelesaikan trip (*Complete Trip*) jika seluruh manifest penumpang telah berstatus selesai diantar dan lunas pembayarannya.

*   **Tujuan Fitur**: Memandu dan mencatat seluruh proses eksekusi penjemputan, pengantaran, pelunasan sisa tagihan, dan penyelesaian perjalanan oleh driver secara real-time.
*   **Deskripsi Fitur**: Meliputi dashboard driver (tugas trip aktif & statistik completed), melihat manifes penumpang rinci, navigasi titik jemput presisi menggunakan peta Leaflet, pembaruan status pickup penumpang (status jemput berubah + timestamp), pembaruan status dropoff penumpang (status antar berubah + timestamp + pencatatan pelunasan otomatis secara cash), konfirmasi pembayaran pelunasan manual, memvalidasi syarat minimal penumpang untuk mulai perjalanan, dan menyelesaikan trip secara keseluruhan (cascade update seluruh status booking manifest menjadi completed via `TripObserver`).
*   **Aktor**: Driver
*   **Hubungan dengan Proses Bisnis**: Menjadi eksekutor akhir pelayanan jasa travel di lapangan serta bertanggung jawab atas pengumpulan sisa tagihan tiket tunai dari pelanggan.
*   **Daftar Use Case**:
    *   UC-24 Melihat Trip Hari Ini
    *   UC-25 Melihat Manifest Penumpang
    *   UC-26 Melihat Lokasi Jemput
    *   UC-27 Checklist Pickup dan Drop-off Penumpang
    *   UC-28 Memperbarui Status Trip
    *   UC-29 Mengonfirmasi Pelunasan Pembayaran
    *   UC-30 Melihat Riwayat Trip Driver
*   **Prioritas**: Tinggi (*High*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 8 | Membantu driver melihat manifes secara langsung, navigasi lokasi jemput presisi, checklist penumpang, dan rekap pelunasan cash. |
| Penalty | 8 | Tanpa modul driver, koordinasi lapangan menjadi manual (kertas/telepon) dan rawan salah jemput atau sisa biaya lupa ditagih. |
| Cost | 5 | Memerlukan pembuatan dashboard mobile-friendly bagi pengemudi, pemetaan Leaflet koordinat, dan otomasi pelunasan cash saat dropoff. |
| Risk | 4 | Risiko keterbatasan jaringan internet driver di jalan yang dapat mengganggu update realtime status perjalanan. |

## 4.7.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-24]** Driver login dan membuka halaman dashboard driver (`/driver/dashboard`). | Sistem memuat data profil driver. Sistem mengecek apakah ada trip aktif berstatus `ready` atau `on_trip` yang ditugaskan kepada driver tersebut. Jika ada, sistem menayangkan kartu ringkasan trip aktif (jadwal, rute, nama armada mobil, plat nomor, dan jumlah manifest penumpang). Dashboard juga memuat statistik driver dari trip completed (total trip, total penumpang diantar, total pendapatan tunai). |
| **[UC-25]** Driver menekan tombol "Detail Perjalanan" pada kartu trip aktif di dashboard. | Sistem memuat rincian trip dan menampilkan daftar manifest penumpang (nama, no HP, jumlah kursi dipesan, sisa biaya pelunasan, status jemput, status antar, alamat jemput, dan alamat tujuan). |
| **[UC-26]** Driver mengklik tombol peta/lokasi pada baris salah satu manifest penumpang. | Sistem membuka modal peta Leaflet interaktif yang menampilkan penanda lokasi (marker) presisi alamat penjemputan berdasarkan koordinat GPS yang disimpan oleh pelanggan. |
| **[UC-28]** Driver menekan tombol "Mulai Perjalanan" (*Start Trip*) pada detail trip yang berstatus `ready`. | Sistem menghitung total penumpang dalam manifest trip tersebut. Jika total penumpang kurang dari 3 orang, sistem menolak keberangkatan dan menampilkan pesan kesalahan *"Minimal harus ada 3 penumpang terisi di dalam trip untuk memulai keberangkatan."* Jika penumpang berjumlah 3 orang atau lebih, sistem memperbarui status trip menjadi `on_trip`, mengisi kolom `started_at` dengan timestamp saat ini, serta secara otomatis memperbarui status seluruh booking dalam manifest menjadi `on_trip` via `TripObserver`. Halaman direfresh memperbarui antarmuka. |
| **[UC-27]** Driver menekan tombol "Checklist Jemput" (*Pickup*) pada salah satu penumpang di manifes. | Sistem memperbarui kolom `status_jemput` pada detail trip menjadi `sudah_dijemput` dan mencatat timestamp waktu penjemputan. Halaman dimuat ulang menampilkan badge hijau terjemput. |
| **[UC-29]** Driver menekan tombol "Konfirmasi Lunas" manual pada baris penumpang yang belum melunasi sisa tagihan tiket. | Sistem menghitung sisa biaya (total tagihan - DP Rp50.000), membuat record baru di tabel `pembayaran` (tipe `pelunasan`, metode `cash`, status `terverifikasi`, jumlah_bayar senilai sisa biaya), memperbarui status pembayaran di manifes menjadi lunas (sisa Rp0), lalu me-refresh halaman manifest. |
| **[UC-27]** Driver menekan tombol "Checklist Sampai" (*Dropoff*) pada salah satu penumpang yang sudah dijemput. | Sistem memperbarui kolom `status_antar` pada detail trip menjadi `sudah_diantar` dan mencatat timestamp waktu pengantaran. Secara otomatis, jika penumpang tersebut belum memiliki pembayaran lunas (belum ada record pelunasan), sistem melakukan transaksi database membuat record pembayaran tipe `pelunasan` metode cash status `terverifikasi` senilai sisa biaya perjalanan penumpang. Halaman diperbarui menampilkan status terantar dan lunas. |
| **[UC-28]** Driver menekan tombol "Selesaikan Trip" (*Complete Trip*) pada trip berstatus `on_trip`. | Sistem memverifikasi manifest trip. Jika masih ada penumpang yang belum berstatus `sudah_diantar` (belum dropoff) atau pembayaran belum lunas, sistem menolak penyelesaian dan menampilkan alert error. Jika seluruh penumpang di manifest berstatus dropoff dan lunas, sistem memulai transaksi database: 1) mengubah status trip menjadi `completed`, 2) mengisi kolom `completed_at` dengan timestamp saat ini, 3) secara otomatis memperbarui seluruh status booking manifest menjadi `completed` via `TripObserver`, 4) melepaskan driver dan armada dari tugas aktif. Halaman dashboard dimuat kembali memperbarui statistik driver. |
| **[UC-30]** Driver mengakses menu "Riwayat Perjalanan" di sidebar driver. | Sistem menyaring data trip driver yang berstatus `completed` dan menampilkan daftar riwayat trip lengkap terpaginasi (rute, tanggal jalan, jam berangkat, armada, dan jumlah penumpang). |

## 4.7.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.7.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)

---

# 4.8 F07 – Pelaporan & Notifikasi

## 4.8.1 Deskripsi dan Prioritas

Fitur Pelaporan & Notifikasi menyediakan instrumen analisis operasional bagi Administrator (Admin) untuk memantau kinerja keuangan dan okupansi armada, serta menyediakan jembatan komunikasi otomatis kepada pelanggan dan driver melalui WhatsApp API. Sistem pelaporan merangkum data secara real-time dan menampilkannya dalam bentuk kartu statistik ringkasan, grafik Chart.js pendapatan harian, rincian harian tabel daily report, dan manifest trip summary. Admin dapat menyaring laporan berdasarkan range periode tanggal dan shift, serta mengekspor data ke file CSV terenkode UTF-8 BOM.

Fitur notifikasi diimplementasikan menggunakan `BookingWhatsappNotificationService` dan `FonnteService`. Sistem melakukan normalisasi nomor HP tujuan ke format standar internasional (+62/62) sebelum mengirimkan pesan. Setiap pengiriman WhatsApp (baik sukses maupun gagal) dicatat ke dalam tabel `whatsapp_notifications` sebagai log audit. WhatsApp dikirim secara real-time saat terjadi perubahan status (DP terverifikasi, booking masuk trip, booking dibatalkan) serta dijalankan secara berkala menggunakan scheduler Laravel (`booking:send-confirmation` setiap jam 06.00 WIB untuk mengonfirmasi keberangkatan pagi).

*   **Tujuan Fitur**: Menyajikan data analisis performa bisnis travel bagi admin dan otomatisasi komunikasi informasi perjalanan secara presisi kepada pelanggan dan driver.
*   **Deskripsi Fitur**: Meliputi dashboard pelaporan keuangan/okupansi, grafik statistik pendapatan, filter periode dan shift laporan harian, export data laporan ke file CSV (UTF-8 BOM), normalisasi nomor telepon WhatsApp, pengiriman notifikasi WhatsApp otomatis (verifikasi DP, assign trip, pembatalan booking), pencatatan log notifikasi WhatsApp, dan cron scheduler konfirmasi keberangkatan pagi hari H.
*   **Aktor**: Admin, Sistem (Scheduler)
*   **Hubungan dengan Proses Bisnis**: Menyediakan evaluasi bisnis keuangan bagi manajemen travel dan menjamin penyebaran informasi jadwal perjalanan terkini kepada pelanggan dan driver agar tepat waktu di lokasi jemput.
*   **Daftar Use Case**:
    *   UC-21 Melihat Laporan Booking
    *   UC-22 Melihat Laporan Trip
    *   UC-23 Melihat Laporan Pendapatan
    *   UC-31 Mengirim Notifikasi WhatsApp
*   **Prioritas**: Tinggi (*High*)

| Komponen | Nilai | Alasan |
| :--- | :--- | :--- |
| Benefit | 8 | Memberikan visualisasi omset harian bagi admin, melacak okupansi armada, serta menginfokan keberangkatan otomatis via WhatsApp. |
| Penalty | 7 | Tanpa laporan, keputusan bisnis tidak terukur. Tanpa notifikasi WA, koordinasi keberangkatan dengan pelanggan menjadi lambat. |
| Cost | 5 | Melibatkan integrasi API pihak ketiga (Fonnte) untuk WhatsApp, scheduled cron job harian, Chart.js, serta ekspor file format CSV. |
| Risk | 5 | Ketergantungan penuh pada kestabilan API pihak ketiga dan sisa kuota pengiriman pesan WhatsApp. |

## 4.8.2 Urutan Stimulus dan Response

| Stimulus (Aktor) | Response (Sistem) |
| :--- | :--- |
| **[UC-21, UC-22, UC-23]** Admin mengakses menu "Laporan" pada dashboard admin. | Sistem memuat statistik kumulatif: total booking, total pendapatan bersih (sum total_harga dari booking `completed`), total trip (count trip status `completed`), dan okupansi kursi rata-rata (total kursi terisi vs kapasitas total armada). Sistem merender grafik garis Chart.js pendapatan harian dan memuat tabel rincian laporan harian terpaginasi. |
| **[UC-21, UC-22, UC-23]** Admin memilih tanggal mulai, tanggal akhir, dan shift (pagi/malam) pada kolom filter, lalu menekan "Filter". | Sistem menyaring data database berdasarkan periode dan shift yang dipilih, menghitung ulang ringkasan statistik harian, dan merender kembali grafik Chart.js pendapatan harian. |
| **[UC-21, UC-22, UC-23]** Admin menekan tombol "Export CSV". | Sistem mengambil data laporan harian terfilter, menyusunnya ke dalam file format CSV dengan enkoder UTF-8 BOM agar terbaca rapi di Microsoft Excel, lalu memicu download file CSV secara otomatis di browser admin. |
| **[UC-31]** Perubahan status booking dipicu (DP diverifikasi admin, atau booking di-assign ke trip, atau booking dibatalkan admin/pelanggan). | Sistem (via `BookingWhatsappNotificationService` / `FonnteService`) mendeteksi event perubahan, merangkum pesan teks notifikasi sesuai format event, menormalisasi nomor HP tujuan (mengganti awalan 0/08/+62 menjadi format internasional 62), lalu mengirimkan HTTP POST request ke API Fonnte. |
| **[UC-31]** API Fonnte mengembalikan respon status pengiriman pesan. | Sistem menangkap status respon (success/error). Sistem mencatat record baru di tabel `whatsapp_notifications` berisi kode booking terkait, nomor target, isi pesan teks, tipe event, status pengiriman (`sent` jika API mengembalikan sukses, atau `failed` jika error/koneksi gagal), dan menyimpan mentah respon JSON API di kolom response untuk keperluan log audit. |
| **[UC-31]** Cron scheduler sistem menjalankan perintah `booking:send-confirmation` setiap hari jam 06.00 WIB pagi keberangkatan. | Sistem memfilter booking berstatus `dikonfirmasi` atau `assigned_to_trip` yang memiliki tanggal keberangkatan hari ini dan belum pernah menerima notifikasi keberangkatan pagi. Untuk setiap booking yang memenuhi syarat, sistem secara otomatis mengambil detail alamat jemput, rute, jam, nama driver, nomor HP driver, memformat pesan teks keberangkatan, mengirimkannya ke WhatsApp pelanggan, dan mencatat log pengiriman ke database. |

## 4.8.3 Activity Diagram

(Activity Diagram akan disisipkan secara manual)

## 4.8.4 Sequence Diagram

(Sequence Diagram akan disisipkan secara manual)
