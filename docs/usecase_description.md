# Use Case Description - Sistem Informasi Singgalang Jaya Travel

Dokumen ini berisi penjelasan skenario detail untuk 31 use case pada sistem sesuai dengan dokumen spesifikasi kebutuhan fungsional (UC-01 s.d. UC-31).

---

### UC-01 Registrasi Pelanggan

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Registrasi Pelanggan |
| **Aktor** | Pengunjung / Pelanggan |
| **Deskripsi** | Pengunjung membuat akun pelanggan baru menggunakan nama, email, nomor WhatsApp, dan password. |
| **Kondisi Awal** | Pengunjung mengakses sistem dan belum memiliki akun terdaftar. |
| **Kondisi Akhir** | Akun pelanggan berhasil dibuat dan disimpan dalam database sistem. |
| **Alur Utama** | 1. Pengunjung memilih menu pendaftaran.<br>2. Sistem menampilkan form registrasi.<br>3. Pengunjung mengisi data nama, email, password, dan no WhatsApp.<br>4. Pengunjung menekan tombol Daftar.<br>5. Sistem memvalidasi data dan menyimpannya ke database.<br>6. Sistem menampilkan pesan sukses dan mengarahkan ke halaman login. |
| **Alur Alternatif** | Jika email atau nomor WhatsApp sudah terdaftar, sistem akan menampilkan pesan error dan meminta pengunjung memasukkan informasi yang berbeda. |

### UC-02 Login Pelanggan

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Login Pelanggan |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan masuk ke sistem menggunakan email dan password untuk mengakses fitur pelanggan. |
| **Kondisi Awal** | Pelanggan berada di halaman login. |
| **Kondisi Akhir** | Pelanggan berhasil login dan diarahkan ke halaman beranda/dashboard pelanggan. |
| **Alur Utama** | 1. Pelanggan memasukkan email dan password.<br>2. Pelanggan menekan tombol login.<br>3. Sistem memvalidasi kredensial di database.<br>4. Sistem mengarahkan ke halaman utama pelanggan. |
| **Alur Alternatif** | Jika kredensial salah, sistem menampilkan notifikasi kesalahan (error) dan meminta pelanggan mencoba lagi. |

### UC-03 Login Admin

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Login Admin |
| **Aktor** | Admin |
| **Deskripsi** | Admin masuk ke sistem menggunakan email dan password untuk mengakses dashboard administrasi. |
| **Kondisi Awal** | Admin berada di halaman login. |
| **Kondisi Akhir** | Admin berhasil login dan diarahkan ke dashboard admin. |
| **Alur Utama** | 1. Admin memasukkan email dan password.<br>2. Admin menekan tombol login.<br>3. Sistem memvalidasi kredensial dan hak akses (role).<br>4. Sistem mengarahkan ke halaman dashboard admin. |
| **Alur Alternatif** | Jika kredensial salah atau role bukan admin, sistem menolak akses dan menampilkan error. |

### UC-04 Login Driver

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Login Driver |
| **Aktor** | Driver |
| **Deskripsi** | Driver masuk ke sistem menggunakan email dan password untuk mengakses fitur operasional perjalanan. |
| **Kondisi Awal** | Driver berada di halaman login. |
| **Kondisi Akhir** | Driver berhasil login dan diarahkan ke dashboard driver. |
| **Alur Utama** | 1. Driver memasukkan email dan password.<br>2. Driver menekan tombol login.<br>3. Sistem memvalidasi kredensial dan hak akses (role).<br>4. Sistem mengarahkan ke halaman dashboard operasional driver. |
| **Alur Alternatif** | Jika kredensial salah, sistem menolak akses dan menampilkan error. |

### UC-05 Logout

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Logout |
| **Aktor** | Pelanggan, Admin, Driver |
| **Deskripsi** | Pengguna keluar dari sesi sistem untuk mengakhiri akses aplikasi. |
| **Kondisi Awal** | Pengguna dalam keadaan terautentikasi (login) di sistem. |
| **Kondisi Akhir** | Sesi pengguna dihapus dari sistem dan diarahkan kembali ke halaman login atau beranda. |
| **Alur Utama** | 1. Pengguna menekan tombol/menu logout.<br>2. Sistem menghapus sesi aktif (session) dari cache/database.<br>3. Sistem mengarahkan kembali ke halaman publik/login. |
| **Alur Alternatif** | - |

### UC-06 Melihat Jadwal Travel

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Jadwal Travel |
| **Aktor** | Pengunjung, Pelanggan |
| **Deskripsi** | Pengunjung atau pelanggan melihat jadwal keberangkatan, rute, tarif, shift, dan ketersediaan kursi. |
| **Kondisi Awal** | Pengguna membuka menu jadwal atau pencarian rute. |
| **Kondisi Akhir** | Daftar jadwal beserta status kursi dan harga tertampil di layar. |
| **Alur Utama** | 1. Pengguna membuka menu pencarian tiket/jadwal.<br>2. Pengguna memasukkan kriteria (rute asal, tujuan, atau tanggal).<br>3. Pengguna menekan tombol "Cari".<br>4. Sistem menampilkan daftar ketersediaan keberangkatan sesuai kriteria. |
| **Alur Alternatif** | Jika kriteria pencarian tidak cocok dengan jadwal manapun, sistem menampilkan pesan "Jadwal travel tidak ditemukan". |

### UC-07 Melakukan Booking Travel

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melakukan Booking Travel |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan melakukan pemesanan travel dengan mengisi data perjalanan dan penumpang. |
| **Kondisi Awal** | Pelanggan telah login dan menemukan jadwal perjalanan yang sesuai. |
| **Kondisi Akhir** | Transaksi pemesanan tercatat di database dengan status menunggu pembayaran DP. |
| **Alur Utama** | 1. Pelanggan menekan tombol pesan pada jadwal terpilih.<br>2. Pelanggan mengisi formulir data penumpang, menentukan nomor kursi, dan lokasi penjemputan.<br>3. Pelanggan menyetujui pemesanan.<br>4. Sistem menyimpan data booking ke database dan mengunci nomor kursi sementara waktu.<br>5. Sistem menampilkan halaman instruksi tagihan DP (Down Payment). |
| **Alur Alternatif** | Jika kursi yang diincar pelanggan keduluan dipesan orang lain sesaat sebelum menekan persetujuan, sistem akan menolak dan meminta pelanggan merestart form pemilihan kursi. |

### UC-08 Menentukan Titik Jemput dan Tujuan

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Menentukan Titik Jemput dan Tujuan |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan menentukan lokasi penjemputan dan tujuan menggunakan peta digital (OpenStreetMap/Leaflet). |
| **Kondisi Awal** | Pelanggan sedang melakukan pengisian form booking. |
| **Kondisi Akhir** | Titik koordinat jemput/tujuan (latitude, longitude) disimpan ke sistem. |
| **Alur Utama** | 1. Pelanggan membuka peta dari formulir pemesanan.<br>2. Pelanggan menggeser penanda (marker) pada peta ke titik lokasi penjemputan akurat.<br>3. Pelanggan menekan "Simpan Titik Peta".<br>4. Sistem menyimpan nilai koordinat ke formulir yang akan dikirim ke sistem. |
| **Alur Alternatif** | Jika peta gagal dimuat (misal karena jaringan lambat), pelanggan tetap bisa mengetikkan nama/deskripsi jalan secara manual pada kotak alamat. |

### UC-09 Mengunggah Bukti Pembayaran DP

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengunggah Bukti Pembayaran DP |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan mengunggah bukti pembayaran uang muka (DP) sebesar Rp50.000 untuk proses verifikasi. |
| **Kondisi Awal** | Pelanggan memiliki transaksi booking berstatus menunggu pembayaran DP. |
| **Kondisi Akhir** | Bukti transfer (gambar) terkirim dan status pemesanan menjadi "Menunggu Verifikasi Admin". |
| **Alur Utama** | 1. Pelanggan telah mentransfer sejumlah uang secara eksternal.<br>2. Pelanggan membuka halaman tagihan/booking-nya.<br>3. Pelanggan memilih file berkas bukti transaksi dan menambah catatan pengirim.<br>4. Pelanggan menekan "Unggah".<br>5. Sistem menyimpan file di server dan memperbarui status pembayaran. |
| **Alur Alternatif** | Jika format file salah (bukan PDF/JPG/PNG), sistem menolak unggahan dan menampilkan peringatan error format file. |

### UC-10 Memverifikasi Pembayaran DP

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Memverifikasi Pembayaran DP |
| **Aktor** | Admin |
| **Deskripsi** | Admin memeriksa dan memvalidasi bukti pembayaran DP yang diunggah pelanggan. |
| **Kondisi Awal** | Sistem menerima unggahan bukti transfer dan berstatus menunggu verifikasi. |
| **Kondisi Akhir** | Transaksi disetujui, pembayaran DP tercatat, dan status booking menjadi "Dikonfirmasi". |
| **Alur Utama** | 1. Admin masuk ke modul verifikasi pembayaran.<br>2. Admin memeriksa kecocokan mutasi bank dan gambar bukti unggahan.<br>3. Admin menekan tombol konfirmasi/validasi pembayaran.<br>4. Sistem meresmikan status transaksi menjadi "Dikonfirmasi" dan (via trigger UC-31) mengirim pemberitahuan validasi ke pelanggan. |
| **Alur Alternatif** | Jika bukti tidak sah atau tidak ditemukan pada mutasi, admin menolak DP, dan status booking dikembalikan ke "Menunggu Pembayaran Ulang". |

### UC-11 Mengunggah Ulang Bukti Pembayaran DP

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengunggah Ulang Bukti Pembayaran DP |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan mengunggah kembali bukti pembayaran apabila pembayaran sebelumnya ditolak oleh admin. |
| **Kondisi Awal** | Status pemesanan ditolak verifikasinya oleh admin. |
| **Kondisi Akhir** | Berkas baru masuk ke sistem untuk diverifikasi kembali. |
| **Alur Utama** | 1. Pelanggan menerima notifikasi penolakan verifikasi pembayaran.<br>2. Pelanggan membuka kembali laman tagihan pada pesanan terkait.<br>3. Pelanggan menghapus/mengganti file lama dan mengunggah gambar bukti yang sah/jelas.<br>4. Sistem mengubah status kembali ke "Menunggu Verifikasi Admin". |
| **Alur Alternatif** | Jika batas waktu penguncian jadwal habis, transaksi booking otomatis dibatalkan sistem, sehingga pelanggan tidak dapat mengunggahnya lagi. |

### UC-12 Mengelola Rute

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengelola Rute |
| **Aktor** | Admin |
| **Deskripsi** | Admin menambah, mengubah, dan menghapus data rute beserta tarif perjalanan. |
| **Kondisi Awal** | Admin berada di menu Master Data Rute. |
| **Kondisi Akhir** | Perubahan database rute dan tarif travel berhasil disimpan. |
| **Alur Utama** | 1. Admin memilih tambah, ubah, atau hapus rute.<br>2. Admin mengisi atau mengubah form rute (nama kota asal, kota tujuan, harga dasar).<br>3. Sistem memvalidasi logika pengisian data (misal: harga > 0).<br>4. Sistem menyimpan/menghapus catatan rute dari database.<br>5. Sistem menampilkan pop-up/alert berhasil. |
| **Alur Alternatif** | Jika admin menghapus rute yang telah digunakan atau terkait pada jadwal aktif, sistem akan menolak operasi penghapusan (Dependency Check Constraint). |

### UC-13 Mengelola Armada

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengelola Armada |
| **Aktor** | Admin |
| **Deskripsi** | Admin mengelola data armada operasional yang digunakan untuk perjalanan travel. |
| **Kondisi Awal** | Admin berada di menu Master Data Armada. |
| **Kondisi Akhir** | Data inventaris dan identitas armada mobil terbarui di database. |
| **Alur Utama** | 1. Admin mengklik tambah atau ubah armada.<br>2. Admin mengisi informasi kendaraan (nopol, jenis, kapasitas kursi maksimal, status ketersediaan).<br>3. Sistem memvalidasi format inputan.<br>4. Sistem merekam atau memperbarui data kendaraan tersebut.<br>5. Sistem menayangkan alert sukses. |
| **Alur Alternatif** | Pada saat menghapus, jika kendaraan (armada) tersebut sedang bertugas aktif dalam trip harian berjalan, operasi penghapusan digagalkan. |

### UC-14 Mengelola Driver

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengelola Driver |
| **Aktor** | Admin |
| **Deskripsi** | Admin mengelola data driver beserta akun yang digunakan untuk login ke sistem. |
| **Kondisi Awal** | Admin membuka menu Master Data Driver. |
| **Kondisi Akhir** | Akun pengguna (role: driver) dan data profil pengemudinya diperbarui atau dibuat. |
| **Alur Utama** | 1. Admin memilih menambah atau mengubah driver.<br>2. Admin mengisi kredensial login (email, sandi), informasi identitas diri sopir, dan armada bawaan yang ditugaskan (opsional).<br>3. Admin menekan tombol "Simpan".<br>4. Sistem menjalankan transaksi DB: mencatat tabel *User*, dan tabel profil *Driver*.<br>5. Sistem memberitahu hasil sukses tindakan admin. |
| **Alur Alternatif** | Saat pembuatan baru, bila email yang diregistrasikan sudah dipakai oleh pelanggan atau admin lain, sistem langsung menolak dan meminta email lain. |

### UC-15 Mengelola Jadwal Keberangkatan

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengelola Jadwal Keberangkatan |
| **Aktor** | Admin |
| **Deskripsi** | Admin mengelola jadwal keberangkatan, termasuk penambahan, perubahan, pengaktifan, dan penghapusan jadwal. |
| **Kondisi Awal** | Admin berada di modul Jadwal Travel. |
| **Kondisi Akhir** | Katalog jadwal (shift, jam keberangkatan, kapasitas sisa, dan rute) berhasil diperbarui. |
| **Alur Utama** | 1. Admin memilih aksi kelola (tambah, edit, atau ganti status aktif).<br>2. Admin mengisi kriteria operasional jadwal pada form.<br>3. Saat ditekan "Simpan", sistem memvalidasi pergerakan kapasitas dan parameter jadwal.<br>4. Sistem menyimpan ke database dan menayangkan jadwal baru tersebut di aplikasi publik. |
| **Alur Alternatif** | Pada aksi update, apabila admin merendahkan kuota di bawah jumlah penumpang terpesan yang aktif hari ini, sistem menampilkan pesan peringatan kapasitas tak valid dan menggagalkan simpanan. |

### UC-16 Mengelola Booking

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengelola Booking |
| **Aktor** | Admin |
| **Deskripsi** | Admin memantau, mengelola, dan memperbarui status seluruh booking pelanggan (misalnya pembatalan sepihak karena alasan spesifik). |
| **Kondisi Awal** | Admin membuka daftar manajemen seluruh pemesanan. |
| **Kondisi Akhir** | Status transaksi pemesanan berubah dan memicu penyesuaian kuota sistem. |
| **Alur Utama** | 1. Admin melihat informasi rinci dari daftar transaksi booking tertentu.<br>2. Admin melakukan aksi intervensi administratif (misal: "Batalkan Pesanan").<br>3. Admin mengisi catatan pembenaran pembatalan.<br>4. Sistem me-*rollback* status pemesanan tersebut jadi *Cancelled*, lalu secara otomatis memanggil algoritma kalkulasi pembebasan kursi (mengembalikan kuota sisa) pada jadwal keberangkatan terkait. |
| **Alur Alternatif** | Apabila admin tidak memasukkan alasan pembatalan pada sistem form, validasi gagal dan aksi tak dapat dilanjutkan. |

### UC-17 Membentuk Trip

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Membentuk Trip |
| **Aktor** | Admin |
| **Deskripsi** | Admin membuat trip operasional berdasarkan jadwal keberangkatan yang tersedia. |
| **Kondisi Awal** | Sistem mempunyai jadwal aktif dan daftar pemesanan dari pelanggan. |
| **Kondisi Akhir** | Dokumen operasional perjalanan (*Trip Manifest*) berhasil diterbitkan di sistem. |
| **Alur Utama** | 1. Admin memasuki modul Manajemen Trip dan menekan buat trip baru.<br>2. Admin memilih tanggal jalan dan merelasikannya dengan template Jadwal keberangkatan.<br>3. Sistem menyusun instansi log *Trip* pada periode berjalan dengan status *"Menunggu / Ready"*.<br>4. Admin mendapat halaman notifikasi pembuatan trip sukses. |
| **Alur Alternatif** | - |

### UC-18 Menugaskan Driver dan Armada ke Trip

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Menugaskan Driver dan Armada ke Trip |
| **Aktor** | Admin |
| **Deskripsi** | Admin menetapkan driver dan armada pada trip yang telah dibuat. |
| **Kondisi Awal** | Trip yang ada berstatus kosong armada maupun drivernya. |
| **Kondisi Akhir** | Manifest/Trip mengikat identitas pengemudi beserta data fisik mobil (armada). |
| **Alur Utama** | 1. Admin meninjau data Trip lalu masuk ke opsi "Assign Driver".<br>2. Sistem memuat daftar menu *dropdown* driver dan armada.<br>3. Admin menyeleksi armada serta pengemudi yang layak.<br>4. Sistem merelasikan *(update ID relasi)* database trip ke data master armada dan driver. |
| **Alur Alternatif** | - |

### UC-19 Memasukkan Booking ke Trip

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Memasukkan Booking ke Trip |
| **Aktor** | Admin |
| **Deskripsi** | Admin memasukkan booking pelanggan yang telah terverifikasi ke dalam manifest trip sesuai kapasitas armada. |
| **Kondisi Awal** | Telah terbentuk trip (dan assigned driver) yang memiliki kapasitas kosong di sistem. |
| **Kondisi Akhir** | Penumpang-penumpang terkonfirmasi pindah alur dari *"Daftar Tunggu Keberangkatan"* ke daftar *Detail Manifest* di kendaraan tertentu. |
| **Alur Utama** | 1. Admin melihat rincian manifest sebuah trip.<br>2. Admin memilih sekumpulan booking penumpang berstatus DP divalidasi yang jalurnya sepadan.<br>3. Admin mengonfirmasi inisiasi pemindahan data tersebut.<br>4. Sistem menciptakan baris-baris *Detail Trip* per tiket dan menaikkan status pemesanan ke tingkat Assigned/Penugasan.<br>5. Informasi manifest di dasbor pengemudi otomatis termutakhirkan. |
| **Alur Alternatif** | Apabila sistem menemukan kalkulasi *overcapacity* dari total daftar transferan dengan sisa kapasitas aktual armada, sistem memberikan error validasi peringatan pencegahan penumpang belebih. |

### UC-20 Melihat Trip Hari Ini

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Trip Hari Ini |
| **Aktor** | Driver |
| **Deskripsi** | Driver melihat daftar trip yang ditugaskan pada hari berjalan. |
| **Kondisi Awal** | Supir/driver melakukan otentikasi login masuk aplikasi. |
| **Kondisi Akhir** | Dashboard memuat kartu perjalanan yang segera harus digawangi hari itu. |
| **Alur Utama** | 1. Driver mengunjungi halaman muka portal (home) atau Daftar Tugas.<br>2. Sistem melangsungkan query data tabel Trip menggunakan identifikasi UserID pengemudi dan tanggal hari ini (Current Date).<br>3. Sistem menampilkan ringkasan informasi *pickup* armada, shift waktu, jam keberangkatan, rute, dan kode Trip. |
| **Alur Alternatif** | Sistem memberikan luaran kalimat ramah ("Anda sedang istirahat. Tidak ada jadwal tugas Anda per hari ini.") bila hasil kueri bernilai NULL. |

### UC-21 Melihat Manifest Penumpang

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Manifest Penumpang |
| **Aktor** | Driver |
| **Deskripsi** | Driver melihat daftar penumpang beserta informasi perjalanan pada trip yang ditugaskan. |
| **Kondisi Awal** | Driver membuka tugas (Trip Hari Ini). |
| **Kondisi Akhir** | Tabel lembaran penumpang beserta titik lokasi jemputan dan data penagihan tampil detil. |
| **Alur Utama** | 1. Driver menekan tombol/menu lihat detail penumpang dari tugas trip-nya.<br>2. Sistem membongkar kueri JOIN tabel Detail Trip ke Booking, Pelanggan, Pembayaran.<br>3. Sistem memproyeksikan deretan tabel penumpang, posisi letak duduk kursi, instruksi titik jemput dan jumlah tagihan biaya tunai yang belum lunas tertagih. |
| **Alur Alternatif** | - |

### UC-22 Melihat Lokasi Jemput

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Lokasi Jemput |
| **Aktor** | Driver |
| **Deskripsi** | Driver melihat lokasi penjemputan pelanggan melalui peta digital sebagai panduan perjalanan. |
| **Kondisi Awal** | Driver melihat rincian manifest seorang penumpang. |
| **Kondisi Akhir** | Antarmuka memunculkan visualisasi peta digital dengan petunjuk arah penjemputan penumpang. |
| **Alur Utama** | 1. Driver menekan ikon GPS/Peta di samping alamat pelanggan pada manifes.<br>2. Sistem mencomot nilai koordinat geografis pelanggan (Latitude/Longitude).<br>3. Sistem me-render modul Peta Interaktif di atas modal antarmuka layar untuk memberi pengemudi kejelasan visibilitas jalur.<br>4. Supir mengikuti titik pada layar. |
| **Alur Alternatif** | Dalam kasus ekstrim koordinat absen (0.0 / tidak diisi pelanggan saat pemesanan), sistem akan mengandalkan string alamat murni sebagai output layar. |

### UC-23 Mengonfirmasi Pickup dan Drop-off Penumpang

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengonfirmasi Pickup dan Drop-off Penumpang |
| **Aktor** | Driver |
| **Deskripsi** | Driver memperbarui status penjemputan dan penurunan setiap penumpang selama perjalanan berlangsung. |
| **Kondisi Awal** | Supir mendatangi alamat pemesan. |
| **Kondisi Akhir** | Progress bar layanan tercatat maju sebagai pelacakan histori waktu. |
| **Alur Utama** | 1. Supir menekan aksi "Konfirmasi Jemput (Pickup)" tatkala penumpang sah duduk di mobil.<br>2. Sistem merubah status pelacakan penumpang ke tahap "Dalam Perjalanan".<br>3. Di ujung destinasi pengantaran rute, Supir menekan aksi "Drop-off".<br>4. Sistem memfinalisasi progres penjalanan pelanggan ke jenjang "Telah Sampai Tujuan". |
| **Alur Alternatif** | - |

### UC-24 Memperbarui Status Trip

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Memperbarui Status Trip |
| **Aktor** | Driver |
| **Deskripsi** | Driver memperbarui status perjalanan mulai dari berangkat hingga selesai. |
| **Kondisi Awal** | Mobil sedang berada pada pangkalan dan semua persiapan penumpang usai (tugas berstatus 'Ready'). |
| **Kondisi Akhir** | Logistika tugas log menjadi 'On Trip' dan ditutup menjadi 'Completed' di akhirnya. |
| **Alur Utama** | 1. Saat pedal diinjak awal perjalanan, pengemudi menekan "Mulai Perjalanan Trip".<br>2. Sistem membukukan status "On Trip".<br>3. Saat operasi berakhir tuntas, pengemudi menekan "Akhiri/Selesaikan Trip".<br>4. Sistem membukukan kondisi perjalanan ke tahap tertutup ("Completed"). |
| **Alur Alternatif** | - |

### UC-25 Mengonfirmasi Pelunasan Pembayaran

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengonfirmasi Pelunasan Pembayaran |
| **Aktor** | Driver |
| **Deskripsi** | Driver mengonfirmasi pelunasan sisa pembayaran pelanggan setelah pembayaran diterima. |
| **Kondisi Awal** | Terdapat sisa kekurangan dana tunai tagihan di detail manifest (misal: Tagihan 250rb - DP Bank 50rb = Tunggakan 200rb). |
| **Kondisi Akhir** | Tunggakan penumpang tercatat lunas nihil (0). |
| **Alur Utama** | 1. Supir mendagihkan nominal sisa ke pelanggan saat menaikkannya.<br>2. Pelanggan membayar uang tunai tersebut secara perorangan langsung ke supir.<br>3. Supir menekan tombol "Set Status Lunas" pada manifest.<br>4. Sistem menyesuaikan baris buku pencatatan pembayaran transaksi menjadi berlabel "Lunas" total dan menampung log penyetor. |
| **Alur Alternatif** | - |

### UC-26 Melihat Riwayat Booking

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Riwayat Booking |
| **Aktor** | Pelanggan |
| **Deskripsi** | Pelanggan melihat riwayat pemesanan dan status perjalanan yang pernah dilakukan. |
| **Kondisi Awal** | Pelanggan login. |
| **Kondisi Akhir** | Jejak langkah histori terdahulu dimuat di layar riwayat. |
| **Alur Utama** | 1. Pelanggan mengakses menu Histori / Riwayat Transaksi Booking.<br>2. Sistem mengambil data transaksi di seluruh siklus waktu milik Pelanggan tersebut (misal perjalanan bulan-bulan sebelumnya).<br>3. Sistem mendistribusikan data historis (termasuk yg Batal atau Tuntas) pada layar daftar historikal. |
| **Alur Alternatif** | - |

### UC-27 Melihat Riwayat Trip Driver

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Riwayat Trip Driver |
| **Aktor** | Driver |
| **Deskripsi** | Driver melihat riwayat perjalanan yang pernah diselesaikan. |
| **Kondisi Awal** | Driver login. |
| **Kondisi Akhir** | Log pencapaian/rekap historikal perjalanan Supir tersebut tertampil. |
| **Alur Utama** | 1. Supir mampir ke laman Menu Histori / Trip Lalu.<br>2. Sistem membongkar gudang database trip yang statusnya telah usai/Completed berdasarkan identifier Supir itu.<br>3. Supir dapat merunut balik perjalanan-perjalanan kerja yang dia telah pertanggungjawabkan kepada perusahaan. |
| **Alur Alternatif** | - |

### UC-28 Melihat Laporan Booking

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Laporan Booking |
| **Aktor** | Admin |
| **Deskripsi** | Admin melihat laporan data booking berdasarkan periode tertentu. |
| **Kondisi Awal** | Admin berada dalam Dasbor bagian Pelaporan. |
| **Kondisi Akhir** | Rekap analitik penyajian tabel rekap booking ditampilkan untuk diekstrak. |
| **Alur Utama** | 1. Admin beranjak pada panel menu cetak Laporan Booking.<br>2. Admin menginjeksi filter batas awal waktu (Start Date) dan batas penghujung waktu (End Date).<br>3. Sistem menyarikan tren hasil agregasi volume booking tiket dan memuntahkannya jadi tabel pelaporan statistik siap ekspor (PDF/Excel) dan cetak (Print). |
| **Alur Alternatif** | - |

### UC-29 Melihat Laporan Trip

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Laporan Trip |
| **Aktor** | Admin |
| **Deskripsi** | Admin melihat laporan operasional perjalanan (trip) berdasarkan periode tertentu. |
| **Kondisi Awal** | Admin berada dalam Dasbor bagian Pelaporan. |
| **Kondisi Akhir** | Rekap data evaluasi pergerakan lalu lintas trip muncul. |
| **Alur Utama** | 1. Admin mencetus pilihan cetak Laporan Operasional Trip.<br>2. Sistem menyediakan opsi filtrasi kurun penanggalan yang dimau.<br>3. Sistem merumuskan tabel-tabel data mobil, supir yang sering bertugas, relasi log jalan, lalu memberikannya sebagai umpan ekspor (Print Out/Softcopy). |
| **Alur Alternatif** | - |

### UC-30 Melihat Laporan Pendapatan

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Melihat Laporan Pendapatan |
| **Aktor** | Admin |
| **Deskripsi** | Admin melihat laporan pendapatan yang berasal dari pembayaran DP maupun pelunasan pelanggan. |
| **Kondisi Awal** | Admin menekan Laporan Pendapatan Finansial Kas. |
| **Kondisi Akhir** | Data omset (baik dari aliran dana rekening Bank DP dan tunai Supir) dikonsolidasikan. |
| **Alur Utama** | 1. Admin menyusuri menu pelaporan omzet Kas.<br>2. Sistem membaca arus uang tunai dan verifikasi yang sukses disahkan sepanjang siklus tenggang waktu inputan Admin.<br>3. Laporan mengalkulasikan neraca total uang masuk yang berhasil lalu memperlihatkannya ke Admin sebagai bahan cetakan laporan. |
| **Alur Alternatif** | - |

### UC-31 Mengirim Notifikasi WhatsApp

| Atribut | Keterangan |
| --- | --- |
| **Nama Use Case** | Mengirim Notifikasi WhatsApp |
| **Aktor** | Sistem (Otomatis) |
| **Deskripsi** | Sistem mengirimkan notifikasi otomatis kepada pelanggan melalui Fonnte API pada kondisi tertentu, seperti verifikasi DP atau pengingat keberangkatan. |
| **Kondisi Awal** | Diberlakukannya sebuah trigger dari controller atau observer di belakang layar (pembatalan booking / sukses verifikasi admin). |
| **Kondisi Akhir** | Pengiriman instruksi request HTTP dikirim ke pihak ke-3 untuk disampaikan ke gawai (HP) Pelanggan. |
| **Alur Utama** | 1. Algoritma observer (e.g., BookingObserver) merekam guncangan perubahaan nilai state valid di database.<br>2. Algoritma mengkompilasi templat redaksional teks pemberitahuan berbasis string variabel pengguna.<br>3. Algoritma menghantam injeksi request (HTTP POST Payload) ke server Fonnte Service / Webhook WhatsApp pihak ke-3 memuat Nomer HP Pelanggan/Sopir.<br>4. Webhook merutekan pesan, menembak langsung ke chat wa target aktor. |
| **Alur Alternatif** | Bila Webhook (Fonnte API) jatuh alias down time/Error 500 koneksi tak bertuan, Sistem akan mengabaikan eksekusi gagal itu (*fire-and-forget* logic) tanpa membuat aplikasi travelnya ikut tumbang crash 500 error page. |
