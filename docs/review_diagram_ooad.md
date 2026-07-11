# Hasil Review Dokumen OOAD: Sistem Informasi Singgalang Jaya Travel

Dokumen ini berisi hasil review komprehensif terhadap dokumen arsitektur, spesifikasi kebutuhan fungsional (SRS), Use Case Diagram (UCD), Activity Diagram (AD), dan Sequence Diagram (SD) pada proyek **Sistem Informasi Singgalang Jaya Travel**.

---

## 1. Analisis & Jawaban 10 Pertanyaan Review

### 1. Apakah setiap Activity Diagram sudah sesuai dengan Use Case yang diwakilinya?
* **AD-12 (Kelola Rute) & AD-13 (Kelola Armada)**: **Tidak Sepenuhnya**. Diagram ini menggabungkan alur CRUD secara linear tanpa percabangan (*Decision Node*) yang jelas. Hal ini menyebabkan alur Hapus (*Delete*) dipaksa melalui langkah validasi data input (seperti kota asal/tujuan atau nomor plat) yang sebenarnya tidak relevan saat menghapus data.
* **AD-14 (Kelola Driver) & AD-15 (Kelola Jadwal Keberangkatan)**: **Tidak Sesuai**. Kedua diagram ini **hanya menggambarkan alur Tambah (Create)**. Alur untuk Ubah (*Update*) dan Hapus (*Delete*) hilang sepenuhnya dari diagram aktivitas.
* **AD-16 (Kelola Booking)**: Menggambarkan alur Admin melihat dan membatalkan booking. Namun, **Use Case "Kelola Booking" oleh Admin tidak terdaftar di Use Case Diagram** (`usecase_diagram.md`), yang menyebabkan inkonsistensi arsitektur.

### 2. Apakah setiap Sequence Diagram sudah sesuai dengan Activity Diagram?
* Ya, secara garis besar mereka konsisten mengikuti kesalahan logika yang ada pada Activity Diagram terkait.
* Namun, Sequence Diagram memiliki masalah pemodelan tambahan: **SD-12, SD-13, SD-14, dan SD-15 menggabungkan metode `store()`, `update()`, dan `destroy()` dalam satu alur linear** menggunakan notasi garis miring (`/`). Di Laravel MVC, metode-metode ini dipicu oleh request HTTP (POST, PUT, DELETE) yang berbeda dan melalui alur eksekusi yang berbeda pula. Penggabungan ini merusak konsep Sequence Diagram UML standar yang seharusnya memetakan satu skenario interaksi spesifik.

### 3. Apakah ada langkah yang hilang?
Ya, beberapa langkah krusial hilang dari alur diagram:
* **Alur Hapus (Delete)** untuk Driver (AD-14/SD-14) dan Jadwal Keberangkatan (AD-15/SD-15) hilang sepenuhnya.
* **Alur Read (Melihat Daftar)** sebelum melakukan aksi Tambah/Ubah/Hapus tidak digambarkan.
* **Alur Penolakan Validasi (Validation Failures)**: Tidak digambarkan bagaimana sistem merespons input yang tidak valid (misal, mengembalikan error ke view/pengguna). Di Sequence Diagram, fragmen `alt` tidak memiliki alur alternatif (`else`).
* **Pemicuan Event/Observer**: Alur pemicuan `BookingObserver::saved()` untuk memperbarui status kuota jadwal keberangkatan serta pengiriman notifikasi WhatsApp pembatalan via `FonnteService` (seperti yang didefinisikan di `sequence.md` bagian 12) hilang pada `SD-16`.

### 4. Apakah ada langkah yang seharusnya dipindahkan ke diagram lain?
Ya:
* Pada **SD-12** dan **SD-13**, langkah pemeriksaan relasi database (seperti `jadwal()->exists()` atau `driver()->exists()`) harus diletakkan di **awal** alur Hapus (Delete), bukan setelah pemanggilan `save rute()` atau `save armada()` yang merupakan bagian dari alur Tambah/Ubah.
* Pada **SD-15**, langkah memanggil model Rute (`Rute::latest()->get()`) untuk mengisi pilihan dropdown form harus dipindahkan ke alur rendering form (GET), bukan berada di dalam alur submit data (POST/PUT).

### 5. Apakah ada Activity Diagram yang sebenarnya menggabungkan lebih dari satu Use Case?
* Ya. **AD-12 (Kelola Rute), AD-13 (Kelola Armada), AD-14 (Kelola Driver), dan AD-15 (Kelola Jadwal Keberangkatan)** mencoba menggabungkan seluruh operasi CRUD ke dalam satu diagram. 
* Sesuai prinsip *"Satu Use Case = Satu Activity Diagram"*, alur CRUD yang memiliki aturan validasi dan manipulasi database yang berbeda sebaiknya dipisahkan, atau minimal menggunakan percabangan (*Decision Node*) di awal diagram untuk memisahkan alur Tambah, Ubah, dan Hapus secara jelas.

### 6. Apakah ada Sequence Diagram yang terlalu besar sehingga sebaiknya dipisahkan?
* Ya. **SD-12, SD-13, SD-14, dan SD-15** terlalu besar dan overloaded karena memaksakan penggabungan operasi Create, Update, dan Delete ke dalam satu diagram. 
* Diagram-diagram tersebut harus dipisahkan menjadi skenario **Tambah, Ubah, dan Hapus** secara mandiri agar sesuai dengan prinsip UML dan mempermudah pemetaan ke kode program Laravel.

### 7. Apakah ada Activity Diagram atau Sequence Diagram yang tidak diperlukan?
* **Tidak ada**. Semua diagram yang ada mewakili fungsionalitas master data dan transaksi booking yang penting bagi sistem. Namun, struktur dan kontennya perlu direvisi/diganti agar akurat.

### 8. Apakah ada diagram yang seharusnya ditambahkan?
Ya. Sistem ini **kehilangan diagram untuk alur transaksi utama (core transactions)**. Diagram saat ini hanya berfokus pada CRUD master data admin, sedangkan alur utama berikut belum memiliki diagram sama sekali:
1. **Pelanggan — Melakukan Booking Travel (UC-07 / UC-08)**: Proses Livewire, pengecekan kuota real-time, dan Leaflet map picker.
2. **Pelanggan — Mengunggah Bukti Pembayaran DP (UC-09 / UC-10)**: Transaksi awal keuangan.
3. **Admin — Verifikasi Pembayaran DP (UC-12)**: Persetujuan/penolakan DP dan integrasi notifikasi Fonnte WA.
4. **Admin — Kelola Trip & Alokasi Penumpang (UC-18 / UC-19 / UC-20)**: Pembentukan manifest trip riil.
5. **Driver — Operasional Lapangan (UC-24 s.d UC-30)**: Alur kerja supir (Pickup, Drop-off + Auto Pelunasan, Complete Trip).

### 9. Apakah nama diagram sudah konsisten?
**Belum Konsisten**:
* **Penomoran**: Nomor diagram (12 s.d 16) tidak cocok dengan nomor Use Case di Use Case Diagram (misal: Kelola Rute di UCD adalah nomor 18, di Use Case Description adalah nomor 15, namun di file bernama AD-12/SD-12).
* **Penamaan**: Ada ketidaksesuaian penamaan istilah, seperti "nonaktifkan armada" di AD-13 (seharusnya "hapus armada" sesuai CRUD/Usecase Description) dan "Kelola Booking" di AD-16 yang tidak terdaftar di Use Case Diagram Admin.

### 10. Apakah alurnya sudah mengikuti implementasi Laravel MVC?
**Belum Sepenuhnya**:
* Penggabungan rute HTTP POST (`store`), PUT (`update`), dan DELETE (`destroy`) dalam satu alur controller di diagram tidak sesuai dengan konsep Laravel routing.
* Form Request (`StoreDriverRequest`/`StoreArmadaRequest` dll.) digambarkan juga dieksekusi pada aksi Hapus (`destroy`), padahal di Laravel Hapus tidak memerlukan validasi field data.
* Pemeriksaan relasi database untuk penghapusan digambarkan terjadi setelah data disimpan di database (`save()`), padahal di Laravel MVC pemeriksaan ini dilakukan di awal sebelum data dihapus.
* `SD-16` memanggil method fiktif `status tetap DP hangus()` pada model Pembayaran, yang secara logika Laravel tidak ada dan tidak diperlukan.

---

## 2. Tabel Evaluasi dan Review Diagram

| Diagram | Status | Permasalahan | Rekomendasi |
| :--- | :--- | :--- | :--- |
| **AD-12 Kelola Rute** | ⚠️ Perlu Direvisi | 1. Menggabungkan alur CRUD secara linear tanpa *decision node*.<br/>2. Alur Hapus dipaksa melalui validasi input field.<br/>3. Penomoran (AD-12) tidak sinkron dengan UCD (UC-18) dan Deskripsi UC (UC-15). | 1. Tambahkan *Decision Node* setelah menampilkan rute untuk memisahkan cabang Tambah, Ubah, dan Hapus.<br/>2. Pisahkan langkah validasi data (Tambah/Ubah) dengan pemeriksaan dependensi jadwal (Hapus).<br/>3. Sinkronkan nomor diagram. |
| **SD-12 Kelola Rute** | ⚠️ Perlu Direvisi | 1. Menggabungkan `store()`, `update()`, dan `destroy()` dalam satu alur linear.<br/>2. Form Request divalidasi pada aksi `destroy()`.<br/>3. Pengecekan `jadwal()->exists()` diletakkan *setelah* pemanggilan `save rute()`.<br/>4. Fragmen `alt` tidak memiliki jalur alternatif (`else`). | 1. Pisahkan menjadi 3 diagram mandiri: Tambah Rute (`store`), Ubah Rute (`update`), dan Hapus Rute (`destroy`).<br/>2. Pada diagram Hapus Rute, lakukan cek relasi jadwal di awal sebelum menghapus data.<br/>3. Lengkapi fragmen `alt` dengan alur pengembalian error jika validasi/cek dependensi gagal. |
| **AD-13 Kelola Armada** | ⚠️ Perlu Direvisi | 1. Menggunakan istilah "nonaktifkan armada", tidak konsisten dengan "hapus armada" di dokumen deskripsi use case.<br/>2. Alur CRUD digabung secara linear tanpa percabangan logika.<br/>3. Penomoran (AD-13) tidak sinkron dengan UCD (UC-24) dan Deskripsi UC (UC-18). | 1. Ganti istilah "nonaktifkan" menjadi "hapus" armada.<br/>2. Gunakan *Decision Node* setelah menampilkan daftar armada untuk membagi alur Tambah, Ubah, dan Hapus.<br/>3. Pisahkan validasi data fisik armada dari cek relasi driver/trip aktif. |
| **SD-13 Kelola Armada** | ⚠️ Perlu Direvisi | 1. Menggabungkan metode `store()`, `update()`, dan `destroy()` dalam satu alur.<br/>2. Form Request divalidasi pada alur Hapus.<br/>3. Cek dependensi `driver()->exists()` dan `trips()->exists()` diletakkan *setelah* `save armada()`.<br/>4. Fragmen `alt` tidak memiliki percabangan alternatif. | 1. Pisahkan menjadi diagram terpisah untuk Tambah, Ubah, dan Hapus Armada.<br/>2. Pada diagram Hapus Armada, lakukan cek relasi driver & trip aktif di awal sebelum memanggil `delete()`.<br/>3. Lengkapi fragmen `alt` dengan alur penolakan. |
| **AD-14 Kelola Driver** | ❌ Sebaiknya Diganti | 1. Hanya menggambarkan alur **Tambah (Create)** driver. Alur Ubah dan Hapus hilang sepenuhnya.<br/>2. Percabangan "Driver memiliki satu armada?" kurang logis karena sistem menggunakan dropdown pilihan tunggal. Validasi krusial Laravel (email unik, format nomor HP, password) tidak digambarkan.<br/>3. Penomoran (AD-14) tidak sinkron dengan UCD (UC-17) & Deskripsi UC (UC-14). | 1. Buat ulang diagram aktivitas Kelola Driver yang mencakup cabang logika CRUD lengkap (Tambah, Ubah, Hapus).<br/>2. Detailkan validasi akun user (email unik) dan relasi armada.<br/>3. Sinkronkan penomoran diagram. |
| **SD-14 Kelola Driver** | ❌ Sebaiknya Diganti | 1. Hanya menggambarkan alur `store()` dan `update()`, alur `destroy()` (Delete) hilang sepenuhnya.<br/>2. Pada alur `update()`, pemanggilan pembuatan user baru (`save user role=driver()`) tidak logis karena user sudah ada.<br/>3. Tidak menggambarkan penanganan konflik jika armada yang dipilih ternyata sudah digunakan oleh driver lain. | 1. Buat ulang dan pisahkan menjadi Sequence Diagram tersendiri untuk Tambah, Ubah, dan Hapus Driver.<br/>2. Di alur Hapus, gambarkan transaksi database untuk menghapus profil driver beserta akun user-nya secara bersamaan.<br/>3. Gambarkan pengecekan bentrok armada di database. |
| **AD-15 Kelola Jadwal Keberangkatan** | ❌ Sebaiknya Diganti | 1. Hanya menggambarkan alur **Tambah (Create)** jadwal. Alur Ubah, Hapus, dan Toggle Status (Aktif/Nonaktif) hilang sepenuhnya.<br/>2. Alur jika rute tidak tersedia ("Tampilkan pesan rute harus dibuat dulu") menggantung tanpa alur selesai (*dead end*).<br/>3. Penomoran (AD-15) tidak sinkron dengan UCD (UC-19) & Deskripsi UC (UC-16). | 1. Buat ulang diagram aktivitas Kelola Jadwal yang mencakup alur CRUD lengkap beserta alur **Toggle Status** jadwal.<br/>2. Hubungkan alur jika rute tidak tersedia kembali ke halaman daftar jadwal atau berikan simpul selesai.<br/>3. Sinkronkan penomoran diagram. |
| **SD-15 Kelola Jadwal Keberangkatan** | ❌ Sebaiknya Diganti | 1. Hanya menggambarkan `store()`, `update()`, dan `toggleStatus()`. Alur `destroy()` (Delete) hilang.<br/>2. Memasukkan query GET data master (`Rute::latest()->get()`) ke dalam alur submit data (POST/PUT).<br/>3. Melakukan sum kapasitas booking sebelum jadwal dibuat di database.<br/>4. Menggunakan `StoreJadwalRequest` pada alur `toggleStatus()`. | 1. Buat ulang dan pisahkan menjadi Sequence Diagram mandiri untuk Tambah Jadwal, Ubah Jadwal, Toggle Status Jadwal, dan Hapus Jadwal.<br/>2. Pastikan query data master `Rute` digambarkan pada saat render form (GET), bukan saat submit data (POST/PUT).<br/>3. Di alur Hapus, gambarkan cek dependensi `bookings()->exists()`. |
| **AD-16 Kelola Booking** | ⚠️ Perlu Direvisi | 1. Use Case "Kelola Booking" untuk Admin tidak terdaftar di Use Case Diagram.<br/>2. Tindakan "catat DP hangus tanpa refund" tidak memiliki representasi perubahan status pembayaran di diagram.<br/>3. Penomoran (AD-16) bentrok dengan nomor Use Case di dokumen lain. | 1. Tambahkan "Kelola Booking" sebagai use case Admin di UCD agar sinkron dengan dokumen SRS (UC-17) dan diagram ini.<br/>2. Perjelas alur pencatatan status DP hangus di sistem.<br/>3. Sinkronkan nomor diagram. |
| **SD-16 Kelola Booking** | ⚠️ Perlu Direvisi | 1. Menggunakan pemanggilan metode fiktif `status tetap DP hangus()` pada model `Pembayaran` yang tidak ada dalam implementasi MVC.<br/>2. Tidak menggambarkan pemicuan event/observer untuk memperbarui kuota jadwal keberangkatan (`jadwal->checkAndUpdateStatus()`) saat booking dibatalkan.<br/>3. Tidak menggambarkan alur pengiriman notifikasi WhatsApp pembatalan. | 1. Hapus pesan fiktif `status tetap DP hangus()`. Cukup tunjukkan status booking di-update ke `cancelled`.<br/>2. Gambarkan pemanggilan observer `BookingObserver::saved()` yang memicu `checkAndUpdateStatus()` pada model `Jadwal` untuk mengembalikan kuota kursi.<br/>3. Gambarkan pengiriman pesan WhatsApp pembatalan ke pelanggan/driver via `FonnteService` agar sesuai dengan spesifikasi sistem. |
