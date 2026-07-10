# 5 Other Non-Functional Requirements

## 5.1 Performance Requirements

Kebutuhan performa dalam sistem ini didefinisikan berdasarkan batasan implementasi sistem Laravel 13, database relasional MySQL/MariaDB, dan integrasi API WhatsApp Fonnte. Aspek performa mencakup:

*   **Waktu Respon Halaman**: Seluruh halaman publik, dashboard admin, dan dashboard driver dirancang untuk memuat data di bawah 2 detik pada kondisi jaringan internet standar. Komponen interaktif seperti Livewire `BookingForm`, `BookingTable`, dan `PembayaranTable` mengolah data secara asinkron menggunakan AJAX bawaan Livewire tanpa perlu me-refresh halaman penuh, menghemat beban lalu lintas data.
*   **Proses Login**: Kecepatan otentikasi login memakan waktu di bawah 1 detik. Sistem menggunakan session-based authentication dengan driver `file` (default Laravel) untuk melacak otorisasi pengguna.
*   **Proses Booking**: Komponen Livewire `BookingForm` melakukan kalkulasi tarif dan ketersediaan kursi secara dinamis (*reactive state*) melalui event listener Alpine.js/Livewire, memberikan respon waktu di bawah 500 ms saat jumlah penumpang diubah. Proses penyimpanan data booking (`BookingService::createBooking`) menggunakan transaksi database terisolasi untuk memastikan penulisan data berlangsung cepat dan aman.
*   **Upload Bukti Pembayaran**: Sistem membatasi ukuran berkas gambar bukti pembayaran maksimal sebesar 2 MB (2048 KB) dengan ekstensi `.jpg`, `.jpeg`, atau `.png` (diatur pada `StorePembayaranRequest`). Validasi ukuran berkas ini mencegah penurunan performa penyimpanan server (*disk I/O*) akibat berkas yang terlalu besar.
*   **Generate Laporan**: Halaman laporan admin (`LaporanController@index`) menyajikan data statistik secara langsung dari database menggunakan query agregasi Eloquent (seperti `withSum` dan `count`). Ekspor laporan ke berkas CSV (`LaporanController@export`) disajikan menggunakan stream response agar proses unduh berkas tidak membebani memori server web meskipun data transaksi berjumlah besar.
*   **Query Database dan Pagination**: Untuk menghindari beban muat data berlebih (*memory exhaustion*), query pada data master Rute, Armada, Driver, Jadwal, Trip, dan Rating dibatasi menggunakan pagination (`->paginate(10)` atau `->paginate(9)`) dengan pencarian filter terindeks.
*   **Scheduler**: Sistem mengandalkan scheduler internal Laravel yang berjalan secara sinkron melalui cron job server:
    *   `booking:expire`: Berjalan setiap 1 menit untuk mencari booking yang melampaui `expired_at` (30 menit) dan membebaskan kuota kursi.
    *   `booking:send-confirmation`: Berjalan sekali sehari setiap pukul 06.00 WIB untuk mengirim WhatsApp konfirmasi keberangkatan pagi kepada penumpang.
*   **Timeout**: Timeout HTTP request untuk API eksternal Fonnte diatur secara default (30 detik) agar kegagalan koneksi ke API pihak ketiga tidak menyebabkan request pelanggan menggantung (*freeze*).

## 5.2 Safety Requirements

Persyaratan keselamatan sistem fokus pada perlindungan keutuhan data operasional travel dari kehilangan, manipulasi tidak sengaja, dan kegagalan sistem.

*   **Rollback Transaksi Database**: Seluruh proses transaksional kritis yang melibatkan penulisan ke beberapa tabel sekaligus dijalankan di dalam blok transaksi (`DB::transaction`). Ini mencakup pembuatan akun driver (`DriverController@store` / `update`), proses checklist dropoff dan pelunasan cash driver (`TripController@dropoff`), verifikasi pembayaran DP oleh admin (`PembayaranController@verify`), serta pembatalan trip keberangkatan. Jika terjadi kesalahan di tengah eksekusi, seluruh perubahan dibatalkan otomatis (*rollback*), mencegah ketidakkonsistenan data (seperti akun driver tercipta tetapi profil driver tidak, atau penumpang diturunkan tanpa tercatat pembayaran sisa tarif).
*   **Validasi Masukan (Input Validation)**: Sistem mencegah input data tidak valid melalui validasi berlapis di tingkat Form Request (seperti `StoreRuteRequest`, `StoreArmadaRequest`, `StoreJadwalRequest`, dan `StoreBookingRequest`). Contoh validasi keselamatan:
    *   Kapasitas armada tidak boleh melebihi 20 kursi.
    *   Kuota jadwal tidak boleh diturunkan di bawah jumlah kursi yang telah dipesan aktif.
    *   Rute asal dan tujuan wajib berbeda.
    *   Tanggal keberangkatan jadwal baru tidak boleh di masa lalu (`after_or_equal:today`).
*   **Pencegahan Perubahan Data Tidak Valid (State Protection)**: Sistem menerapkan proteksi status pada model bisnis:
    *   Booking yang sudah berstatus `expired`, `cancelled`, atau `completed` dilindungi sehingga tidak dapat mengunggah bukti DP atau diubah detail penumpangnya.
    *   Trip hanya boleh dijalankan jika statusnya `ready` dan memiliki minimal 3 penumpang di manifest.
    *   Armada tidak dapat dihapus jika masih bertugas pada trip berstatus `ready` atau `on_trip`.
*   **Upload File Aman**: Sistem membatasi tipe MIME berkas unggahan bukti transfer hanya berupa gambar. Berkas yang diunggah diletakkan di direktori penyimpanan `storage/app/public/bukti_pembayaran` dengan penamaan acak yang aman untuk menghindari penimpaan berkas (*file overwriting*) dan eksekusi skrip berbahaya (*shell upload*).
*   **Error Handling**: Sistem menggunakan Exception Handler Laravel untuk menangkap kesalahan sistem secara global. Dalam mode produksi, sistem menyembunyikan detail error teknis (seperti SQL query error) dan menampilkan halaman ramah pengguna (*user-friendly error page*) seperti halaman HTTP 403 (akses ditolak) atau HTTP 404 (data tidak ditemukan) untuk menjaga informasi internal kode sumber.

## 5.3 Security Requirements

Sistem Informasi Singgalang Jaya Travel menerapkan protokol keamanan standar industri yang terintegrasi secara bawaan dalam Laravel Framework.

*   **Authentication & Session**: Sistem otentikasi menggunakan Laravel Breeze dengan penyimpanan sesi berbasis cookie terenkripsi. Sesi pengguna memiliki masa kedaluwarsa otomatis dan session ID diregenerasi secara otomatis setiap kali login sukses untuk mencegah serangan pembajakan sesi (*session fixation*).
*   **Authorization & RBAC (Role-Based Access Control)**: Hak akses dikendalikan secara ketat berdasarkan peran pengguna (`users.role` bernilai `admin`, `driver`, atau `pelanggan`) melalui `RoleMiddleware`. Rute-rute penting diproteksi:
    *   Rute dengan awalan `/admin/*` hanya dapat diakses oleh user ber-role `admin`.
    *   Rute dengan awalan `/driver/*` hanya dapat diakses oleh user ber-role `driver`.
    *   Rute transaksional booking (`/booking/*`) hanya dapat diakses oleh user ber-role `pelanggan`.
*   **CSRF Protection**: Semua form input HTML dan komponen Livewire dilengkapi dengan proteksi Cross-Site Request Forgery (CSRF token) secara otomatis. Setiap request POST, PUT, dan DELETE yang tidak menyertakan token CSRF yang cocok akan ditolak otomatis oleh sistem (HTTP 419).
*   **Password Hashing**: Kata sandi pengguna tidak pernah disimpan dalam bentuk teks biasa. Sistem menggunakan enkripsi satu arah yang aman (`Bcrypt`/`Argon2` melalui fungsi `Hash::make()`) untuk mengenkripsi kata sandi pengguna sebelum disimpan ke database.
*   **Pencegahan SQL Injection**: Seluruh interaksi database menggunakan Laravel Eloquent ORM dan Query Builder yang secara otomatis mengimplementasikan PDO parameter binding. Masukan pengguna dibersihkan secara otomatis, mencegah serangan injeksi perintah SQL.
*   **Perlindungan XSS (Cross-Site Scripting)**: Mesin pembuat tampilan Laravel (Blade Templating Engine) menggunakan sintaks `{{ $var }}` yang secara otomatis menerapkan fungsi `htmlspecialchars` untuk menyaring karakter HTML khusus sebelum ditampilkan ke browser, mencegah eksekusi skrip JavaScript berbahaya dari input pengguna.
*   **Pencegahan Akses File Ilegal**: Berkas bukti transfer disimpan di dalam direktori internal `storage` yang tidak dapat diakses langsung secara publik. Hanya berkas yang di-link-kan ke direktori public (`php artisan storage:link`) yang dapat diakses secara terbatas.

## 5.4 Software Quality Attributes

Atribut kualitas perangkat lunak dari sistem ini dianalisis berdasarkan struktur kode dan arsitektur Laravel:

*   **Availability**: Sistem dirancang untuk dapat diakses secara terus-menerus. Scheduler otomatis `booking:expire` berjalan mandiri di latar belakang untuk memastikan pembebasan kursi yang tidak dibayar berjalan tanpa intervensi admin, menjaga ketersediaan kuota kursi travel bagi pelanggan lain.
*   **Reliability**: Keandalan sistem didukung oleh implementasi basis data relasional dengan integritas data referensial yang kuat (Foreign Key Constraints). Sistem WhatsApp notification mencatat log status pengiriman (`sent`/`failed`) dan respon mentah dari API Fonnte pada tabel `whatsapp_notifications`, memudahkan pelacakan jika terjadi kegagalan pengiriman.
*   **Maintainability**: Sistem memiliki tingkat pemeliharaan yang tinggi karena memisahkan logika bisnis dari Controller ke Service Layer (seperti `BookingService.php` dan `FonnteService.php`). Hal ini meminimalkan ukuran Controller (*skinny controllers*) dan memusatkan logika perubahan status agar lebih mudah dipelihara.
*   **Modularity**: Struktur aplikasi terbagi secara modular mengikuti pola Model-View-Controller (MVC) Laravel serta pembagian modul Livewire terpisah untuk kebutuhan tampilan interaktif dinamis seperti `BookingForm`, `BookingTable`, dan `PembayaranTable`.
*   **Testability**: Kode program memiliki kemampuan uji (*testability*) yang tinggi. Proyek dilengkapi dengan suite pengujian otomatis (Laravel Feature Tests) yang mencakup pengujian otentikasi, alur pembuatan booking, deteksi duplikat booking, kedaluwarsa pembayaran, moderasi rating, alokasi trip admin, hingga operasional perjalanan driver.
*   **Usability**: Aplikasi menyediakan tampilan yang responsif untuk berbagai perangkat. Antarmuka untuk driver dioptimalkan untuk perangkat seluler (*mobile-friendly*) guna memudahkan operasi checklist manifest di lapangan, lengkap dengan integrasi peta penjemputan Leaflet.

## 5.5 Business Rules

Berikut adalah seluruh aturan bisnis (*Business Rules*) riil yang diimplementasikan dalam kode program:

*   **Aturan Booking**:
    *   Pelanggan hanya dapat memesan kursi pada jadwal keberangkatan yang berstatus `aktif` dan memiliki sisa kuota kursi yang cukup.
    *   Pelanggan dilarang memiliki lebih dari satu booking aktif pada jadwal keberangkatan yang sama (mencegah booking ganda).
    *   Setiap booking baru otomatis mendapatkan status `booking_dibuat` dan diberikan waktu 30 menit (`expired_at`) untuk mengunggah bukti DP.
*   **Aturan Pembayaran DP**:
    *   Nilai pembayaran Down Payment (DP) ditetapkan flat sebesar Rp50.000 per transaksi booking (di-hardcode pada logika sistem).
    *   Mengunggah bukti pembayaran DP mengubah status booking menjadi `menunggu_verifikasi`.
    *   Jika pembayaran DP disetujui admin, status booking diperbarui menjadi `dikonfirmasi`.
    *   Jika pembayaran DP ditolak admin, status booking dikembalikan ke `booking_dibuat` dan pelanggan dapat mengunggah bukti baru selama booking belum kedaluwarsa.
*   **Aturan Pembentukan Trip**:
    *   Satu jadwal keberangkatan harian dapat dipecah menjadi beberapa armada trip perjalanan riil.
    *   Setiap trip terikat pada satu jadwal, satu driver, dan satu armada.
    *   Driver dan armada tidak boleh ditugaskan pada trip lain yang memiliki tanggal keberangkatan dan shift (pagi/malam) yang sama (pencegahan bentrok jadwal).
    *   Satu armada trip menggunakan kapasitas tempat duduk maksimal berdasarkan kuota armada bawaan driver tersebut.
*   **Aturan Alokasi Penumpang**:
    *   Booking pelanggan hanya dapat dimasukkan ke dalam manifest trip jika status booking telah `dikonfirmasi` (DP lunas) dan memiliki `jadwal_id` yang sama dengan trip.
    *   Booking yang dimasukkan ke trip otomatis berubah statusnya menjadi `assigned_to_trip`.
    *   Menghapus booking dari manifest trip otomatis mengembalikan status booking menjadi `dikonfirmasi`.
*   **Aturan Operasional Perjalanan Driver**:
    *   Driver hanya dapat memulai perjalanan (*Start Trip*) jika status trip adalah `ready` dan manifest penumpang minimal terisi 3 orang. Memulai trip mengubah status trip menjadi `on_trip` dan status seluruh booking manifest menjadi `on_trip`.
    *   Driver menandai penumpang yang naik sebagai `sudah_dijemput` (*Pickup*).
    *   Driver menandai penumpang yang turun sebagai `sudah_diantar` (*Drop-off*).
    *   Saat dropoff dilakukan, jika penumpang belum melunasi sisa tagihan, sistem otomatis mencatat pelunasan tunai (*auto-pelunasan cash*) senilai sisa biaya (total harga dikurangi DP Rp50.000).
    *   Driver hanya dapat menyelesaikan perjalanan (*Complete Trip*) jika seluruh manifest penumpang berstatus `sudah_diantar` dan status pembayaran lunas. Menyelesaikan trip mengubah status trip menjadi `completed` dan status seluruh booking manifest menjadi `completed`.
*   **Aturan Ulasan & Rating**:
    *   Pelanggan hanya dapat memberikan ulasan dan rating (1-5 bintang) setelah perjalanan berstatus selesai (`completed`).
    *   Ulasan pelanggan masuk ke status moderasi `menunggu` dan hanya akan ditayangkan di landing page publik setelah admin mengubah statusnya menjadi `published`.

---

# 6 Other Requirements

## 6.1 Database Requirements

Sistem Informasi Singgalang Jaya Travel menggunakan database relasional MySQL/MariaDB dengan struktur tabel terindeks dan relasi terintegrasi.

*   **Skema Tabel**: Basis data terdiri dari tabel `users`, `pelanggan`, `drivers`, `armada`, `rute`, `jadwal`, `bookings`, `pembayaran`, `trips`, `detail_trips`, `ratings`, dan `whatsapp_notifications`.
*   **Foreign Key Constraints**: Seluruh relasi tabel diikat menggunakan Foreign Key di tingkat database untuk menjaga referensi data:
    *   Tabel `pelanggan` dan `drivers` terhubung ke tabel `users` dengan cascade delete (menghapus user otomatis menghapus profil).
    *   Tabel `bookings` mereferensikan `pelanggan_id` dan `jadwal_id`.
    *   Tabel `pembayaran` mereferensikan `booking_id`.
    *   Tabel `detail_trips` mereferensikan `trip_id` dan `booking_id`.
*   **Database Transactions**: Logika transaksi kritis (seperti penciptaan driver beserta user, dropoff penumpang beserta input pelunasan kas, dan verifikasi admin) dibungkus menggunakan blok transaksi (`DB::transaction`) untuk menjamin kepatuhan ACID (Atomicity, Consistency, Isolation, Durability).
*   **Direct Deletion & Safety Logic**: Sistem tidak menerapkan mekanisme Soft Delete (`DeletedAt`). Data yang dihapus akan langsung terhapus dari tabel database. Oleh karena itu, integritas data dijaga melalui proteksi program di Controller yang mencegah penghapusan jika ada referensi transaksi (misal: rute yang memiliki jadwal tidak bisa dihapus, armada yang memiliki driver tidak bisa dihapus, jadwal yang memiliki booking tidak bisa dihapus).

## 6.2 Legal and Privacy Requirements

Sistem menyimpan data sensitif pelanggan dan driver yang dilindungi melalui hak akses terbatas:

*   **Penyimpanan Data Pelanggan**: Data berupa nama, alamat email, nomor telepon, alamat penjemputan, dan koordinat peta disimpan di database untuk keperluan penjemputan perjalanan. Hak akses data ini dibatasi secara ketat: pelanggan hanya dapat melihat data pribadi mereka, driver hanya dapat melihat manifest trip yang ditugaskan kepada mereka hari ini, dan admin dapat melihat keseluruhan data booking.
*   **Unggah Bukti Pembayaran**: Berkas bukti transfer DP berupa gambar/screenshot mutasi bank disimpan di server lokal dan hanya dapat diakses oleh admin peninjau transaksi dan pelanggan pemilik booking tersebut. Berkas dilindungi dari akses publik luar sistem dengan nama berkas acak yang tidak dapat ditebak (*unpredictable filename*).
*   **Data Driver**: Data pribadi pengemudi seperti nama lengkap, nomor SIM, nomor telepon, dan riwayat perjalanan disimpan di database dan dikelola oleh admin untuk kebutuhan administrasi perusahaan.

## 6.3 Project Objectives & Sustainability

Pengembangan sistem informasi ini memiliki tujuan strategis jangka panjang bagi keberlanjutan bisnis Singgalang Jaya Travel:

*   **Tujuan Utama**: Digitalisasi total alur bisnis pemesanan, verifikasi pembayaran, alokasi armada trip harian, operasional pengemudi, hingga pelaporan keuangan secara real-time untuk meminimalkan human-error dan memaksimalkan kapasitas keterisian kursi travel.
*   **Maintainability & Kemudahan Pengembangan**: Arsitektur modular MVC Laravel dan pemisahan logika transaksi ke Service Layer (`BookingService`, `FonnteService`) memastikan kode program mudah dibaca, diuji secara otomatis, dan dirawat oleh pengembang berikutnya.
*   **Sustainability & Scalability**: Struktur tabel yang terindeks dan relasi database yang kokoh memungkinkan sistem menangani ribuan data pemesanan harian tanpa penurunan performa yang signifikan. Sistem dirancang siap dikembangkan lebih lanjut di masa mendatang, misalnya dengan mengintegrasikan sistem gerbang pembayaran otomatis (*payment gateway*) untuk menggantikan verifikasi bukti transfer manual.

---

# 7 Appendix A: Glossary

*   **Armada**: Unit kendaraan mobil operasional travel (misalnya Toyota Hiace, Isuzu Elf) yang didaftarkan di database, memiliki kapasitas kursi tertentu, dan dikendarai oleh driver.
*   **Booking**: Dokumen transaksi reservasi kursi perjalanan travel yang dibuat oleh pelanggan untuk jadwal tertentu, berisi detail jumlah penumpang, alamat jemput, alamat tujuan, koordinat peta, dan status transaksi.
*   **Breeze**: Paket starter kit dari Laravel yang menyediakan perancangan autentikasi pengguna sederhana (login, register, reset password, email verification) dengan Tailwind CSS dan Blade templates.
*   **CSRF (Cross-Site Request Forgery)**: Metode serangan siber di mana aplikasi mengeksekusi perintah tidak sah dari pengguna tepercaya. Laravel melindungi ini melalui pencocokan token CSRF pada setiap form masukan.
*   **DP (Down Payment)**: Pembayaran uang muka tiket travel sebesar Rp50.000 yang wajib diunggah bukti transfernya oleh pelanggan agar pesanan kursi terverifikasi oleh admin.
*   **Driver**: Pengemudi resmi travel yang ditugaskan oleh admin untuk mengendarai armada dalam trip tertentu dan memiliki akses masuk sistem untuk mengelola manifest penumpang.
*   **Fonnte**: Layanan gateway WhatsApp API pihak ketiga yang digunakan oleh sistem untuk mengirimkan pesan notifikasi otomatis keberangkatan, konfirmasi pembayaran, dan pembatalan trip.
*   **Jadwal**: Templat rencana perjalanan travel berdasarkan rute, tanggal, jam berangkat, kuota tempat duduk, dan shift (pagi/malam) yang dijadikan acuan pemesanan pelanggan.
*   **Leaflet**: Pustaka JavaScript berbasis peta digital interaktif ringan yang digunakan pada aplikasi untuk memfasilitasi penentuan titik koordinat jemput/antar pelanggan dan panduan rute driver.
*   **Livewire**: Kerangka kerja Laravel (*front-end framework*) yang memungkinkan pembuatan komponen antarmuka dinamis, reaktif, dan interaktif secara real-time langsung menggunakan PHP tanpa menulis JavaScript manual.
*   **Manifest**: Daftar lengkap data penumpang travel (nama, no HP, alamat jemput/tujuan, koordinat peta, status jemput/antar) yang dialokasikan ke dalam satu trip perjalanan aktif untuk panduan tugas driver.
*   **Middleware**: Lapisan penengah pada rute Laravel yang bertugas menyaring HTTP request masuk, dalam sistem ini digunakan untuk memvalidasi hak akses role (admin, driver, pelanggan).
*   **OpenStreetMap**: Layanan peta dunia kolaboratif gratis yang digunakan bersama pustaka Leaflet sebagai layer peta dasar visualisasi titik penjemputan.
*   **RBAC (Role-Based Access Control)**: Metode pembatasan akses sistem kepada pengguna terdaftar berdasarkan peran (role) tertentu (Admin, Driver, Pelanggan).
*   **Route**: Jalur url alamat website yang didefinisikan pada Laravel untuk mengarahkan request ke file controller dan method yang sesuai.
*   **Rute**: Jalur perjalanan travel dari kota asal ke kota tujuan (misalnya Padang - Pekanbaru) beserta tarif harga dasar tiket.
*   **Scheduler**: Fitur Laravel Task Scheduling untuk menjalankan perintah Command otomatis pada latar belakang server berdasarkan waktu berkala yang ditentukan (daily, everyMinute).
*   **Trip**: Instansi lembar perjalanan riil pada tanggal tertentu yang menggabungkan rute jadwal, armada, driver bertugas, dan manifest penumpang yang siap berangkat.

---

# 8 Appendix B: Analysis Models

*   **Business Flow**: Deskripsi naratif dan alur logika bisnis dari operasional Singgalang Jaya Travel, mulai dari registrasi pelanggan, pemesanan tiket, pembayaran DP, alokasi trip, operasional penjemputan pengemudi, hingga pelaporan keuangan.
*   **Use Case Diagram**: Model visual yang menggambarkan interaksi antara aktor (Pelanggan, Admin, Driver, Guest) terhadap 31 fungsionalitas (*use cases*) utama sistem travel.
*   **Activity Diagram**: Diagram alur kerja aktivitas sistem yang menggambarkan urutan logika langkah operasional per use case, termasuk percabangan kondisi validasi.
*   **Sequence Diagram**: Model interaksi dinamis berurutan waktu antara objek-objek sistem (View, Livewire, Controller, Service, Model, Database) dalam mengeksekusi fungsionalitas tertentu (seperti proses booking atau verifikasi DP).
*   **ERD (Entity Relationship Diagram)**: Skema diagram basis data yang menunjukkan struktur tabel operasional, tipe data kolom, constraints, dan hubungan kardinalitas (seperti relasi one-to-many antara Jadwal dan Booking).
*   **WBS (Work Breakdown Structure)**: Struktur rincian kerja pembangunan perangkat lunak travel yang membagi proyek ke dalam milestone, modul fungsional, dan detail tugas pengerjaan.

---

# 9 Appendix C: To Be Determined (TBD) List

Berdasarkan analisis menyeluruh terhadap source code dan implementasi aktual Singgalang Jaya Travel System saat ini, seluruh fungsionalitas inti yang direncanakan telah selesai dikembangkan secara penuh (termasuk modul CRUD, sistem booking Livewire, verifikasi pembayaran, alokasi trip admin, manifest driver dengan Leaflet map, laporan pendapatan Chart.js, ekspor CSV, serta log WhatsApp Fonnte). 

Namun, terdapat beberapa rencana optimasi kelayakan jangka panjang yang diidentifikasi sebagai item pengembangan lanjutan di masa depan (To Be Determined):

*   **Integrasi Payment Gateway**: Otomatisasi verifikasi pembayaran DP dan pelunasan sisa tiket menggunakan gerbang pembayaran instan (seperti Midtrans atau Xendit) untuk menghilangkan proses unggah bukti transfer gambar manual dan verifikasi manual oleh admin.
*   **Sistem Peta Rute Multi-Dropoff**: Optimasi visual peta Leaflet bagi pengemudi agar dapat menampilkan rute urutan penjemputan optimal (*travelling salesman problem*) dari beberapa alamat penumpang di manifest trip berjalan guna mengefisiensikan durasi perjalanan.
*   **Sistem Riwayat Pengeluaran Operasional**: Penambahan modul pencatatan pengeluaran bensin, tol, dan servis armada oleh driver yang dapat diverifikasi oleh admin untuk digabungkan ke dalam laporan keuangan laba-bersih trip travel.
