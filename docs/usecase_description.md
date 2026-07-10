# Use Case Description - Sistem Informasi Singgalang Jaya Travel

Dokumen ini berisi penjelasan detail (Use Case Description) untuk setiap usecase yang terdapat pada **Sistem Informasi Singgalang Jaya Travel** sesuai dengan Use Case Diagram dan spesifikasi kebutuhan sistem.

---

## Daftar Aktor dan Use Case

| No | Nama Use Case | Aktor Utama | Deskripsi Singkat |
|---|---|---|---|
| **Umum** | | | |
| 1 | Login | Pelanggan, Admin, Driver | Masuk ke sistem menggunakan kredensial terdaftar. |
| 2 | Logout | Pelanggan, Admin, Driver | Keluar dari sesi aktif sistem. |
| **Pelanggan** | | | |
| 3 | Melihat Informasi Travel | Pelanggan | Melihat profil travel, rute, tarif dasar, armada, kontak, dll. |
| 4 | Mencari & Memilih Shift Keberangkatan | Pelanggan | Menyaring jadwal berdasarkan asal, tujuan, tanggal, dan shift (pagi/malam). |
| 5 | Melakukan Pemesanan (Booking) | Pelanggan | Memesan kursi dengan memilih nomor kursi, mengisi data diri, dan alamat penjemputan. |
| 6 | Membayar DP | Pelanggan | Melakukan pembayaran DP minimal dan mengunggah bukti transfer (Include). |
| 7 | Membatalkan Booking | Pelanggan | Membatalkan transaksi pemesanan yang belum terverifikasi/lunas. |
| 8 | Cek Booking Saya | Pelanggan | Melihat riwayat transaksi dan status pemesanan aktif. |
| 9 | Memberikan Ulasan & Rating | Pelanggan | Memberikan umpan balik rating bintang dan ulasan setelah perjalanan selesai. |
| 10 | Melakukan Pelunasan Sisa Bayar ke Driver | Pelanggan | Membayar sisa tagihan secara tunai langsung ke driver. |
| 11 | Mengelola Lokasi Penjemputan [Edit Maps] | Pelanggan | Menentukan titik koordinat penjemputan presisi pada peta digital. |
| 12 | Mengelola Edit Jumlah Penumpang | Pelanggan | Mengubah jumlah penumpang serta nomor kursi sebelum trip masuk manifes final. |
| **Admin** | | | |
| 13 | Kelola Laporan | Admin | Mengakses dan mengekspor laporan pendapatan, trip, dan booking. |
| 14 | CRUD Kelola Data Driver | Admin | Menambah, mengubah, menampilkan, dan menghapus data driver. |
| 15 | CRUD Rute dan Tarif | Admin | Menambah, mengubah, menampilkan, dan menghapus data rute beserta tarifnya. |
| 16 | CRUD Jadwal Keberangkatan | Admin | Menambah, mengubah, menampilkan, dan menghapus jadwal keberangkatan harian. |
| 17 | Kelola Trip | Admin | Membuat trip baru, memasukkan penumpang ke trip, dan menugaskan driver/armada. |
| 18 | CRUD Armada | Admin | Menambah, mengubah, menampilkan, dan menghapus data armada mobil travel. |
| 19 | Verifikasi Bukti DP | Admin | Memvalidasi bukti transfer yang diunggah pelanggan (Terima/Tolak DP). |
| **Driver** | | | |
| 20 | Lihat Data Trip & Manifest Penumpang | Driver | Melihat rincian rute perjalanan dan manifest penumpang yang ditugaskan. |
| 21 | Mengonfirmasi Status Penjemputan | Driver | Memperbarui status penjemputan (Pickup) dan penurunan (Dropoff) penumpang. |
| 22 | Mengonfirmasi Pelunasan Tunai | Driver | Memverifikasi pembayaran tunai sisa biaya perjalanan dari penumpang. |
| 23 | Menyelesaikan Status Perjalanan Trip | Driver | Menutup trip setelah seluruh penumpang diantar ke tujuan dan pembayaran lunas. |

---

## 1. Use Case Umum (General)

### Use Case 1: Login
* **Aktor Utama**: Pelanggan, Admin, Driver
* **Deskripsi**: Aktor memasukkan kredensial berupa email/nomor telepon dan password untuk masuk ke dalam sistem sesuai hak akses masing-masing.
* **Pre-kondisi**: Aktor berada di halaman login dan belum masuk (terautentikasi) ke sistem.
* **Post-kondisi**: Aktor berhasil diarahkan ke halaman beranda atau dashboard yang sesuai dengan perannya.
* **Alur Utama (Basic Flow)**:
  1. Aktor membuka halaman login.
  2. Sistem menampilkan form isian email dan password.
  3. Aktor memasukkan email dan password yang terdaftar, lalu menekan tombol "Login".
  4. Sistem melakukan validasi kredensial ke database.
  5. Sistem mengenali peran aktor (role redirection) dan mengarahkan aktor ke halaman tujuan:
     * **Admin** diarahkan ke halaman `/admin/dashboard`.
     * **Driver** diarahkan ke halaman `/driver/dashboard`.
     * **Pelanggan** diarahkan ke halaman beranda utama (landing page).
* **Alur Alternatif (Alternative Flow)**:
  * **Kredensial Salah**: Jika email atau password tidak cocok, sistem menampilkan pesan error ("Kredensial tidak cocok dengan data kami") dan meminta aktor mengisi ulang.

### Use Case 2: Logout
* **Aktor Utama**: Pelanggan, Admin, Driver
* **Deskripsi**: Aktor keluar dari sesi aktif di sistem untuk menjaga keamanan akun.
* **Pre-kondisi**: Aktor dalam keadaan login di sistem.
* **Post-kondisi**: Sesi aktif aktor dihapus dan sistem mengarahkan aktor kembali ke halaman login atau beranda publik.
* **Alur Utama (Basic Flow)**:
  1. Aktor mengklik tombol "Logout".
  2. Sistem menghapus data sesi (session) login aktor tersebut.
  3. Sistem mengarahkan aktor kembali ke halaman utama (landing page) atau form login.

---

## 2. Use Case Aktor: Pelanggan

### Use Case 3: Melihat Informasi Travel
* **Aktor Utama**: Pelanggan (termasuk Guest/belum login)
* **Deskripsi**: Pelanggan melihat profil perusahaan travel, rute yang disediakan, tarif perjalanan, armada kendaraan, testimoni/ulasan, dan kontak resmi.
* **Pre-kondisi**: Pelanggan mengakses sistem.
* **Post-kondisi**: Pelanggan mendapatkan informasi lengkap mengenai layanan travel.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan membuka website Singgalang Jaya Travel.
  2. Sistem menampilkan halaman landing page utama.
  3. Pelanggan menjelajahi informasi rute populer, rincian kontak, deskripsi armada, serta charter mobil yang tersedia.

### Use Case 4: Mencari & Memilih Shift Keberangkatan
* **Aktor Utama**: Pelanggan (termasuk Guest/belum login)
* **Deskripsi**: Pelanggan menyaring dan memilih jadwal keberangkatan berdasarkan stasiun asal, tujuan, tanggal, serta memilih shift pagi atau malam.
* **Pre-kondisi**: Pelanggan berada di halaman jadwal keberangkatan.
* **Post-kondisi**: Pelanggan menemukan jadwal yang diinginkan dan siap melakukan pemesanan.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan membuka menu "Jadwal" atau fitur pencarian tiket di halaman utama.
  2. Pelanggan memasukkan kota asal, tujuan, dan tanggal keberangkatan yang diinginkan.
  3. Pelanggan memilih filter shift keberangkatan (Pagi atau Malam).
  4. Pelanggan menekan tombol "Cari".
  5. Sistem menampilkan daftar jadwal yang tersedia beserta sisa kuota kursi, tarif, jenis mobil, dan jam keberangkatan.
  6. Pelanggan memilih jadwal yang diinginkan untuk dipesan.
* **Alur Alternatif (Alternative Flow)**:
  * **Jadwal Tidak Ditemukan**: Jika rute pada tanggal tersebut tidak ada, sistem akan menampilkan pesan "Jadwal keberangkatan tidak tersedia untuk tanggal atau rute ini."

### Use Case 5: Melakukan Pemesanan (Booking)
* **Aktor Utama**: Pelanggan (harus Login)
* **Deskripsi**: Pelanggan melakukan pemesanan kursi travel untuk jadwal keberangkatan yang telah dipilih sebelumnya.
* **Pre-kondisi**: Pelanggan sudah login dan sudah memilih jadwal keberangkatan tertentu.
* **Post-kondisi**: Pemesanan tersimpan di database dengan status "Menunggu Pembayaran DP".
* **Alur Utama (Basic Flow)**:
  1. Pelanggan menekan tombol "Pesan Sekarang" pada jadwal yang telah dipilih.
  2. Sistem menampilkan halaman form booking.
  3. Pelanggan mengisi data penumpang (Nama, No. Telepon/WhatsApp), jumlah penumpang, dan memilih nomor kursi kosong yang tersedia secara visual.
  4. Pelanggan menuliskan alamat penjemputan dan pengantaran secara tekstual.
  5. Sistem menghitung total tarif serta nominal minimum DP yang harus dibayarkan.
  6. Pelanggan meninjau ringkasan pemesanan dan menekan tombol "Konfirmasi Pemesanan".
  7. Sistem menyimpan data pemesanan, memperbarui status kursi sementara menjadi dipesan, dan memberikan kode booking serta batas waktu pembayaran DP (misal 2 jam).
* **Alur Alternatif (Alternative Flow)**:
  * **Kursi Telah Terisi**: Jika nomor kursi yang dipilih tiba-tiba terisi oleh pemesan lain sesaat sebelum konfirmasi, sistem membatalkan proses dan meminta pelanggan memilih nomor kursi lain.

### Use Case 6: Membayar DP (Down Payment)
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan membayar uang muka minimum yang disyaratkan untuk mengunci pesanan kursi travel. Proses ini mewajibkan (include) pengunggahan bukti transfer bank.
* **Pre-kondisi**: Pelanggan memiliki transaksi booking berstatus "Menunggu Pembayaran DP".
* **Post-kondisi**: Bukti pembayaran terkirim ke admin dan status booking berubah menjadi "Menunggu Verifikasi".
* **Alur Utama (Basic Flow)**:
  1. Pelanggan masuk ke menu "Booking Saya" dan memilih detail booking yang belum dibayar.
  2. Sistem menampilkan informasi jumlah DP yang harus ditransfer dan nomor rekening bank tujuan.
  3. Pelanggan melakukan transfer bank secara eksternal.
  4. Pelanggan mengklik tombol "Unggah Bukti Transfer" di sistem.
  5. Pelanggan mengunggah berkas foto/screenshot bukti transfer, lalu memasukkan nama pengirim dan tanggal transfer. **[Include: Mengunggah Bukti Transfer DP]**
  6. Sistem memvalidasi berkas, menyimpannya, lalu mengubah status booking menjadi "Menunggu Verifikasi Pembayaran".
* **Alur Alternatif (Alternative Flow)**:
  * **Waktu Pembayaran Habis (Expired)**: Jika batas waktu pembayaran terlampaui sebelum pelanggan mengunggah bukti transfer, sistem (via background job) otomatis membatalkan booking tersebut dan membebaskan kursi kembali. Pelanggan tidak dapat mengunggah bukti lagi.

### Use Case 7: Membatalkan Booking
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan membatalkan pesanan travel yang telah dibuat secara sepihak sebelum diverifikasi oleh admin.
* **Pre-kondisi**: Booking terdaftar di sistem dengan status masih "Menunggu Pembayaran DP" atau "Menunggu Verifikasi Pembayaran".
* **Post-kondisi**: Status booking berubah menjadi "Dibatalkan" dan nomor kursi dilepaskan kembali agar bisa dipesan orang lain.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan membuka menu "Booking Saya".
  2. Pelanggan memilih booking aktif yang ingin dibatalkan.
  3. Pelanggan menekan tombol "Batalkan Booking".
  4. Sistem menampilkan pop-up konfirmasi pembatalan.
  5. Pelanggan mengklik "Ya, Batalkan".
  6. Sistem mengubah status pemesanan menjadi "Dibatalkan" dan membebaskan nomor kursi terkait di database.

### Use Case 8: Cek Booking Saya
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan melihat riwayat transaksi pemesanan perjalanan miliknya beserta status terbaru dari tiap transaksi.
* **Pre-kondisi**: Pelanggan sudah login ke sistem.
* **Post-kondisi**: Pelanggan dapat melihat daftar dan status detail booking mereka.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan mengakses menu "Booking Saya" pada navigasi profil.
  2. Sistem mengambil data transaksi berdasarkan user ID yang login.
  3. Sistem menampilkan daftar booking (kode booking, rute, tanggal pergi, shift, nominal DP, dan status seperti: *Menunggu DP, Menunggu Verifikasi, Dikonfirmasi, Dibatalkan, Selesai*).
  4. Pelanggan dapat menekan tombol "Detail" pada salah satu pemesanan untuk melihat rincian manifest, nomor kursi, titik maps penjemputan, dan log transaksi.

### Use Case 9: Memberikan Ulasan & Rating
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan memberikan umpan balik (rating bintang 1-5 dan komentar) atas pelayanan driver, armada, dan kenyamanan travel.
* **Pre-kondisi**: Transaksi pemesanan pelanggan sudah berstatus "Selesai" (perjalanan telah rampung).
* **Post-kondisi**: Ulasan dan rating tersimpan dan dapat dilihat secara publik di landing page atau oleh admin.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan membuka detail booking yang sudah selesai di menu "Booking Saya".
  2. Pelanggan menekan tombol "Berikan Ulasan".
  3. Sistem menampilkan form isian berupa bintang (1 s.d. 5) dan kotak teks ulasan.
  4. Pelanggan memilih jumlah bintang, mengetik ulasan, dan menekan tombol "Kirim".
  5. Sistem menyimpan ulasan dan memperbarui skor rating kumulatif sistem.

### Use Case 10: Melakukan Pelunasan Sisa Pembayaran ke Driver
* **Aktor Utama**: Pelanggan (berinteraksi dengan Driver)
* **Deskripsi**: Pelanggan menyerahkan sisa biaya tiket (total tiket dikurangi DP) secara tunai langsung kepada driver ketika dijemput atau saat tiba di tujuan.
* **Pre-kondisi**: Status booking pelanggan adalah "Dikonfirmasi" (DP sudah diverifikasi oleh admin) dan perjalanan sedang berlangsung.
* **Post-kondisi**: Pelanggan menyelesaikan kewajiban bayar secara tunai.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan bertemu dengan driver di titik penjemputan/armada.
  2. Pelanggan menanyakan sisa tagihan tiket (jika lupa) kepada driver.
  3. Pelanggan menyerahkan uang tunai senilai sisa tagihan kepada driver.
  4. Pelanggan menerima konfirmasi dari driver bahwa pembayaran tunai telah diterima dan dicatat lunas pada sistem.

### Use Case 11: Mengelola Lokasi Penjemputan [Edit Maps]
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan menentukan letak geografis titik penjemputan secara visual melalui modul peta digital (Google Maps/OpenStreetMap) agar driver mendapat rute navigasi yang akurat.
* **Pre-kondisi**: Pelanggan sedang melakukan pengisian form booking atau mengubah lokasi penjemputan sebelum keberangkatan dijadwalkan secara permanen.
* **Post-kondisi**: Koordinat latitude dan longitude lokasi penjemputan tersimpan di sistem.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan mengklik tombol "Pilih Lokasi di Peta" pada form alamat penjemputan.
  2. Sistem menampilkan modul peta digital interaktif.
  3. Pelanggan menyeret pin (marker) peta ke posisi rumah atau titik kumpul yang tepat.
  4. Sistem mengambil nilai koordinat lintang (latitude) dan bujur (longitude) dari posisi pin tersebut.
  5. Pelanggan menekan tombol "Simpan Titik Peta".
  6. Sistem menyimpan data koordinat GPS tersebut ke dalam detail pesanan.

### Use Case 12: Mengelola / Edit Jumlah Penumpang
* **Aktor Utama**: Pelanggan
* **Deskripsi**: Pelanggan mengubah jumlah kursi yang dipesan dalam satu kode booking (menambah/mengurangi penumpang) selama transaksi belum masuk ke dalam manifes trip keberangkatan final.
* **Pre-kondisi**: Booking terdaftar dengan status "Menunggu Pembayaran DP" atau "Menunggu Verifikasi Pembayaran".
* **Post-kondisi**: Jumlah penumpang, nomor kursi, dan total tagihan diperbarui di sistem.
* **Alur Utama (Basic Flow)**:
  1. Pelanggan membuka detail booking miliknya.
  2. Pelanggan menekan tombol "Edit Detail Penumpang".
  3. Pelanggan menambah atau mengurangi jumlah penumpang, lalu memilih ulang posisi nomor kursi yang tersedia.
  4. Sistem mengkalkulasi ulang total tagihan tiket dan minimal DP yang harus dibayarkan.
  5. Pelanggan menyimpan perubahan.
* **Alur Alternatif (Alternative Flow)**:
  * **Sisa Kursi Tidak Cukup**: Jika pelanggan menambah jumlah penumpang tetapi kursi yang tersisa pada jadwal tersebut tidak mencukupi, sistem menampilkan pesan error "Kapasitas kursi tidak mencukupi."

---

## 3. Use Case Aktor: Admin

### Use Case 13: Kelola Laporan
* **Aktor Utama**: Admin
* **Deskripsi**: Admin mengelola, menganalisis, dan mengekspor laporan kinerja operasional travel. Use case ini di-extend oleh tiga jenis laporan spesifik.
* **Pre-kondisi**: Admin login dan berada di panel administrasi.
* **Post-kondisi**: Admin mendapatkan laporan dalam bentuk tabel, grafik, atau file cetak (PDF/Excel).
* **Alur Utama (Basic Flow)**:
  1. Admin membuka menu "Laporan" pada dashboard admin.
  2. Admin menyaring data berdasarkan rentang tanggal awal dan akhir (filter periode).
  3. Admin memilih format laporan yang diinginkan:
     * **Laporan Pendapatan (Extend)**: Menampilkan total kas masuk dari DP bank dan pelunasan tunai driver.
     * **Laporan Trip (Extend)**: Menampilkan riwayat perjalanan, keterisian kursi rata-rata, driver bertugas, dan armada terpakai.
     * **Laporan Booking (Extend)**: Menampilkan jumlah tiket terpesan, dibatalkan, atau kedaluwarsa.
  4. Sistem memproses data dan menayangkannya di layar.
  5. Admin dapat menekan tombol "Export" untuk mengunduh dokumen laporan.

### Use Case 14: CRUD Kelola Data Driver
* **Aktor Utama**: Admin
* **Deskripsi**: Admin melakukan pengelolaan data master pengemudi (driver) yang bertugas dalam operasional perjalanan.
* **Pre-kondisi**: Admin sudah login.
* **Post-kondisi**: Database driver terbarui.
* **Alur Utama (Basic Flow)**:
  1. Admin mengakses menu "Data Driver".
  2. Sistem menampilkan tabel daftar driver aktif.
  3. Admin dapat memilih salah satu aksi berikut:
     * **Tambah Driver (Extend)**: Admin menekan tombol "Tambah", mengisi data nama lengkap, nomor SIM, nomor telepon, email, password akun driver, dan status keaktifan, kemudian klik "Simpan".
     * **Edit Driver (Extend)**: Admin memilih salah satu driver, mengklik tombol "Edit", memperbarui informasi (misal nomor telepon atau status aktif), kemudian klik "Simpan".
     * **Hapus Driver (Extend)**: Admin memilih driver, mengklik tombol "Hapus", lalu memberikan konfirmasi. Sistem menghapus data driver dari database (atau menonaktifkan akun jika ada riwayat trip terkait).

### Use Case 15: CRUD Rute dan Tarif
* **Aktor Utama**: Admin
* **Deskripsi**: Admin mengelola daftar rute perjalanan (kota asal ke kota tujuan) beserta harga tiket standar yang dikenakan kepada penumpang.
* **Pre-kondisi**: Admin sudah login.
* **Post-kondisi**: Rute perjalanan dan tarif diperbarui dalam sistem.
* **Alur Utama (Basic Flow)**:
  1. Admin masuk ke menu "Rute & Tarif".
  2. Sistem memuat daftar rute perjalanan yang aktif.
  3. Admin memilih salah satu aksi:
     * **Tambah Rute (Extend)**: Admin klik "Tambah", mengisi nama rute (misal: Padang - Pekanbaru), tarif dasar, dan perkiraan durasi perjalanan, lalu klik "Simpan".
     * **Edit Rute dan Tarif (Extend)**: Admin memilih rute, memperbarui tarif tiket karena penyesuaian harga, lalu klik "Simpan".
     * **Hapus Rute (Extend)**: Admin menghapus rute yang sudah tidak beroperasi dari daftar aktif.

### Use Case 16: CRUD Jadwal Keberangkatan
* **Aktor Utama**: Admin
* **Deskripsi**: Admin mengelola templat jadwal keberangkatan harian, menentukan jam berangkat, shift kerja (pagi/malam), serta rute yang dilewati.
* **Pre-kondisi**: Admin sudah login.
* **Post-kondisi**: Jadwal harian yang menjadi acuan booking pelanggan terbarui.
* **Alur Utama (Basic Flow)**:
  1. Admin masuk ke menu "Kelola Jadwal".
  2. Sistem menampilkan daftar jadwal keberangkatan.
  3. Admin memilih aksi:
     * **Tambah Jadwal (Extend)**: Admin mengisi form berupa pilihan rute, jam keberangkatan (misal jam 09.00), kategori shift (Pagi/Malam), dan menetapkan armada default, lalu klik "Simpan".
     * **Edit Jadwal (Extend)**: Admin mengubah jam keberangkatan atau shift jadwal tertentu, lalu klik "Simpan".
     * **Hapus Jadwal (Extend)**: Admin menghapus jadwal keberangkatan tertentu.

### Use Case 17: Kelola Trip
* **Aktor Utama**: Admin
* **Deskripsi**: Admin membuat lembar perjalanan riil (trip) pada tanggal tertentu, menugaskan driver dan armada mobil, serta menata manifes penumpang.
* **Pre-kondisi**: Admin sudah login, terdapat daftar booking terkonfirmasi (DP lunas) pada tanggal terkait.
* **Post-kondisi**: Terbentuk trip keberangkatan yang siap dijalankan oleh driver.
* **Alur Utama (Basic Flow)**:
  1. Admin membuka menu "Manajemen Trip".
  2. Admin memilih opsi berikut:
     * **Tambah Trip (Extend)**: Admin membuat instansi trip keberangkatan dengan menentukan tanggal jalan dan mencocokkannya dengan template jadwal keberangkatan.
     * **Assign Driver ke Trip (Extend)**: Admin menugaskan driver yang sedang kosong (available) dan menetapkan armada mobil yang siap jalan ke dalam trip tersebut.
     * **Memasukkan Penumpang ke Trip (Extend)**: Admin menyaring daftar booking terkonfirmasi pada rute dan tanggal yang sama, kemudian memasukkan nama-nama penumpang tersebut ke dalam manifest trip.
  3. Admin menekan tombol "Publish/Siapkan Trip".
  4. Sistem memperbarui status trip menjadi "Siap Berangkat" dan memunculkan data trip tersebut pada dashboard driver yang bertugas.

### Use Case 18: CRUD Armada
* **Aktor Utama**: Admin
* **Deskripsi**: Admin mengelola daftar armada mobil travel milik perusahaan (seperti Toyota Hiace, Isuzu Elf) beserta status ketersediaannya.
* **Pre-kondisi**: Admin sudah login.
* **Post-kondisi**: Data inventaris armada terbarui.
* **Alur Utama (Basic Flow)**:
  1. Admin masuk ke menu "Kelola Armada".
  2. Sistem menyajikan daftar unit kendaraan yang dimiliki.
  3. Admin memilih aksi:
     * **Tambah Armada (Extend)**: Admin klik "Tambah", mengisi nama armada, nomor plat kendaraan, kapasitas total kursi, dan status, lalu klik "Simpan".
     * **Edit Armada (Extend)**: Admin memperbarui data (misalnya mengubah status dari "Aktif" menjadi "Servis" karena sedang perbaikan), lalu klik "Simpan".
     * **Hapus Armada (Extend)**: Admin menghapus data armada yang sudah dijual atau tidak digunakan lagi.

### Use Case 19: Verifikasi Bukti DP
* **Aktor Utama**: Admin
* **Deskripsi**: Admin melakukan pemeriksaan manual terhadap bukti transfer DP yang dikirimkan oleh pelanggan untuk disinkronkan dengan mutasi rekening bank perusahaan.
* **Pre-kondisi**: Pelanggan telah mengunggah bukti transfer, status booking adalah "Menunggu Verifikasi Pembayaran".
* **Post-kondisi**: Status booking disetujui (dikonfirmasi) atau ditolak.
* **Alur Utama (Basic Flow)**:
  1. Admin membuka menu "Verifikasi Pembayaran" di dashboard.
  2. Sistem menampilkan daftar transaksi yang menunggu verifikasi beserta file gambar bukti transfer.
  3. Admin memeriksa kesesuaian gambar bukti dengan mutasi bank riil.
  4. Admin mengambil keputusan:
     * **Terima DP (Extend)**: Admin mengklik tombol "Terima". Sistem memperbarui status transaksi menjadi "Dikonfirmasi" (booking aktif) dan mengirimkan notifikasi WA/Email sukses ke pelanggan.
     * **Tolak DP (Extend)**: Admin mengklik tombol "Tolak", lalu memasukkan alasan penolakan (misal: gambar buram/nominal tidak sesuai). Sistem mengubah status kembali ke "Pembayaran Ditolak/Menunggu Pembayaran Ulang" atau "Dibatalkan" dan mengirim notifikasi ke pelanggan agar melakukan upload ulang.

---

## 4. Use Case Aktor: Driver

### Use Case 20: Lihat Data Trip & Manifest Penumpang
* **Aktor Utama**: Driver
* **Deskripsi**: Driver melihat jadwal tugas perjalanannya (trip) untuk hari ini atau esok beserta detail manifes penumpang yang harus dijemput.
* **Pre-kondisi**: Driver sudah login ke aplikasi dan admin telah menugaskan driver tersebut pada sebuah trip.
* **Post-kondisi**: Driver mendapatkan informasi navigasi dan daftar penumpang lengkap.
* **Alur Utama (Basic Flow)**:
  1. Driver masuk ke halaman "Daftar Trip Saya" di dashboard driver.
  2. Sistem menampilkan daftar trip yang sedang aktif ditugaskan kepadanya.
  3. Driver memilih salah satu trip aktif.
  4. Sistem menyajikan informasi detail: Kota Asal & Tujuan, Armada, Tanggal/Jam Keberangkatan, serta daftar manifest penumpang (Nama, Nomor Telepon, Nomor Kursi, Status Penjemputan, Alamat Penjemputan, dan Peta Lokasi).

### Use Case 21: Mengonfirmasi Status Penjemputan (Pickup/Dropoff)
* **Aktor Utama**: Driver
* **Deskripsi**: Driver melakukan pembaharuan status penumpang secara bertahap saat menjemput di lokasi jemput (pickup) dan menurunkan di lokasi tujuan (dropoff).
* **Pre-kondisi**: Driver sedang menjalankan trip aktif dan telah berada di titik penjemputan penumpang.
* **Post-kondisi**: Status penumpang berubah menjadi "Dijemput" (Dalam Perjalanan) lalu berubah lagi menjadi "Selesai" (Sampai Tujuan).
* **Alur Utama (Basic Flow)**:
  1. Driver membuka lembar manifest penumpang pada trip berjalan.
  2. Ketika tiba di lokasi penjemputan pelanggan dan pelanggan naik ke mobil, Driver menekan tombol "Konfirmasi Penjemputan" (Pickup). Sistem mencatat status penumpang menjadi "Dijemput".
  3. Ketika tiba di lokasi pengantaran tujuan pelanggan, Driver menekan tombol "Konfirmasi Sampai Tujuan" (Dropoff). Sistem mencatat status penumpang menjadi "Sampai Tujuan".

### Use Case 22: Mengonfirmasi Pelunasan Tunai Penumpang
* **Aktor Utama**: Driver
* **Deskripsi**: Driver mengonfirmasi penerimaan pembayaran sisa biaya perjalanan yang dibayarkan pelanggan secara tunai (cash) langsung kepada driver.
* **Pre-kondisi**: Penumpang dalam status "Dikonfirmasi" tetapi memiliki sisa pembayaran yang belum lunas (baru bayar DP).
* **Post-kondisi**: Sisa pembayaran penumpang dicatat lunas di sistem.
* **Alur Utama (Basic Flow)**:
  1. Penumpang menyerahkan uang tunai sisa pembayaran tiket kepada driver.
  2. Driver menghitung kesesuaian uang tunai dengan nominal sisa bayar yang tertera pada manifest aplikasi.
  3. Driver mengklik tombol "Konfirmasi Lunas Tunai" pada baris nama penumpang tersebut di sistem.
  4. Sistem menyimpan catatan pelunasan tersebut dan mengubah status pembayaran booking terkait menjadi "Lunas".

### Use Case 23: Menyelesaikan Status Perjalanan Trip
* **Aktor Utama**: Driver
* **Deskripsi**: Driver menutup status perjalanan trip secara keseluruhan apabila tugas mengantar semua penumpang telah rampung dan seluruh administrasi sisa pembayaran telah selesai dikonfirmasi.
* **Pre-kondisi**: Semua penumpang dalam manifest trip telah dikonfirmasi sampai tujuan (Dropoff) dan sisa pembayaran mereka telah lunas.
* **Post-kondisi**: Status trip berubah menjadi "Selesai" (Completed) dan status driver/armada menjadi kosong (available) kembali.
* **Alur Utama (Basic Flow)**:
  1. Driver memastikan semua penumpang di manifest berstatus "Sampai Tujuan" dan berstatus pembayaran "Lunas".
  2. Driver menekan tombol "Selesaikan Trip".
  3. Sistem memverifikasi kondisi tersebut.
  4. Sistem mengubah status trip menjadi "Selesai", mencatat waktu trip berakhir, serta melepaskan driver dan armada dari penugasan aktif agar siap digunakan untuk trip selanjutnya.
