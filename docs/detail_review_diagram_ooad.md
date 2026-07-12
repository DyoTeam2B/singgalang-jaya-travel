x# Laporan Detail Review Perbaikan Diagram OOAD: Singgalang Jaya Travel

Laporan ini memuat panduan perbaikan rinci untuk 5 Activity Diagram (AD) dan 5 Sequence Diagram (SD) master data dan booking pada proyek **Sistem Informasi Singgalang Jaya Travel** sesuai dengan implementasi aktual Laravel MVC.

---

# AD-12 Kelola Rute

## Ringkasan Review
Belum sesuai. Diagram AD-12 menggabungkan 3 aksi CRUD (Tambah, Ubah, Hapus) dalam satu alur linear tanpa adanya percabangan (*Decision Node*) yang membedakan logika masing-masing aksi. Hal ini menyebabkan kesalahan logika (seperti memproses input dan memvalidasi kota asal/tujuan pada saat menghapus rute).

## Kesalahan Diagram
- **Alur CRUD Linear Tanpa Percabangan**: Aksi Tambah, Ubah, dan Hapus digabung langsung setelah menu dibuka, padahal ketiganya memiliki alur kerja, input form, dan logika bisnis yang berbeda.
- **Validasi Tidak Relevan untuk Hapus**: Langkah *"Validasi asal, tujuan, tarif"* diproses juga untuk alur Hapus, padahal Hapus rute di Laravel hanya mendeteksi parameter ID dan melakukan cek dependensi jadwal keberangkatan (`jadwal()->exists()`).
- **Siklus Error Validasi Tidak Sesuai**: Jika validasi gagal, alur diarahkan kembali ke tindakan Admin secara umum, padahal seharusnya kembali ke form input spesifik (Form Tambah atau Form Edit).
- **Penomoran Inkonsisten**: Nomor diagram (AD-12) tidak sinkron dengan Use Case Diagram (Kelola Rute adalah UC-18) dan Use Case Description (UC-15).

## Langkah Perbaikan
- Tambahkan **Decision Node** setelah aktivitas *"Sistem menampilkan rute"* untuk membagi alur menjadi tiga cabang: **Tambah Rute**, **Ubah Rute**, dan **Hapus Rute**.
- **Untuk Cabang Tambah Rute**: 
  1. Arahkan ke form tambah rute.
  2. Input data (asal, tujuan, tarif) dan klik Simpan.
  3. Masukkan *decision node* "Data Valid?" (berdasarkan `StoreRuteRequest`). Jika "Ya", simpan ke database dan tampilkan notifikasi sukses. Jika "Tidak", tampilkan error validasi dan arahkan kembali ke form tambah.
- **Untuk Cabang Ubah Rute**: 
  1. Pilih salah satu rute dan klik Edit.
  2. Tampilkan form edit dengan data terisi.
  3. Ubah data dan klik Update.
  4. Masukkan *decision node* "Data Valid?" (berdasarkan `UpdateRuteRequest`). Jika "Ya", simpan perubahan ke database dan tampilkan notifikasi sukses. Jika "Tidak", tampilkan error validasi dan arahkan kembali ke form edit.
- **Untuk Cabang Hapus Rute**: 
  1. Pilih salah satu rute dan klik Hapus.
  2. Masukkan *decision node* "Memiliki jadwal terkait?" (`jadwal()->exists()`). Jika "Ya", tampilkan pesan error ("Rute tidak dapat dihapus...") dan batalkan penghapusan. Jika "Tidak", hapus data dari database dan tampilkan notifikasi sukses.
- **Konsistensi Penomoran**: Sesuaikan nomor diagram (misal menjadi AD-18 atau AD-15).

## Ilustrasi Alur yang Benar
Start
↓
Admin membuka menu Rute
↓
Sistem menampilkan daftar rute
↓
Pilih Aksi?
├── Tambah
│   ↓
│   Sistem menampilkan Form Tambah
│   ↓
│   Admin mengisi data (asal, tujuan, tarif) & klik Simpan
│   ↓
│   Sistem memvalidasi input (StoreRuteRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke Form Tambah
│   └── Ya
│       ↓
│       Simpan rute baru ke database
│       ↓
│       Tampilkan notifikasi berhasil & kembali ke daftar rute
│
├── Ubah
│   ↓
│   Admin memilih salah satu rute & klik Edit
│   ↓
│   Sistem menampilkan Form Edit berisi data rute terpilih
│   ↓
│   Admin mengubah data & klik Update
│   ↓
│   Sistem memvalidasi input (UpdateRuteRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke Form Edit
│   └── Ya
│       ↓
│       Update rute di database
│       ↓
│       Tampilkan notifikasi berhasil & kembali ke daftar rute
│
└── Hapus
    ↓
    Admin memilih salah satu rute & klik Hapus
    ↓
    Sistem memeriksa relasi jadwal (`jadwal()->exists()`)?
    ├── Ya (Ada jadwal terkait)
    │   ↓
    │   Tampilkan pesan error "Rute memiliki jadwal keberangkatan terkait" & kembali ke daftar rute
    └── Tidak
        ↓
        Hapus rute dari database
        ↓
        Tampilkan notifikasi berhasil & kembali ke daftar rute
↓
End

---

# SD-12 Kelola Rute

## Ringkasan Review
Belum sesuai. Diagram menggabungkan routing HTTP POST (`store`), PUT (`update`), dan DELETE (`destroy`) ke dalam satu alur linear menggunakan tanda garis miring (`/`), yang melanggar standar UML Sequence Diagram dan bertentangan dengan arsitektur routing RESTful Laravel MVC.

## Kesalahan Diagram
- **Penggabungan Request Berbeda**: Pesan `store() / update() / destroy()` dan Request `StoreRuteRequest / UpdateRuteRequest` digambarkan dieksekusi bersamaan. Padahal, ketiganya dipicu oleh HTTP verb yang berbeda (`POST`, `PUT`, `DELETE`).
- **Form Request pada Delete**: `StoreRuteRequest` atau `UpdateRuteRequest` tidak dipanggil saat melakukan `destroy()`.
- **Pengecekan Dependensi Salah Posisi**: Langkah `jadwal()->exists()` diletakkan *setelah* `save rute()`. Ini salah, karena jika aksi yang dipilih adalah delete, kita harus memvalidasi dependensi di awal sebelum menghapus dari database.
- **Fragmen Alt Menggantung**: Alt fragment `Data rute valid?` tidak memiliki garis putus-putus (*else*) untuk memodelkan respon kegagalan validasi.

## Langkah Perbaikan
- **Pisahkan menjadi 3 Sequence Diagram**: `SD-12a Tambah Rute` (metode `store`), `SD-12b Ubah Rute` (metode `update`), dan `SD-12c Hapus Rute` (metode `destroy`).
- **Untuk SD-12a Tambah Rute**:
  - Hapus pemanggilan `update()` and `destroy()`.
  - Gunakan hanya `StoreRuteRequest` untuk memvalidasi input. Tunjukkan alur jika validasi gagal, FormRequest melempar exception dan mengembalikan *response redirect back with errors* ke view.
  - Jika valid, Controller memanggil `Rute::create()` dan mengembalikan *redirect* ke route `admin.rute.index` dengan session message success.
- **Untuk SD-12b Ubah Rute**:
  - Alur mirip dengan `store()`, namun gunakan `UpdateRuteRequest` dan panggil `Rute::update()`.
- **Untuk SD-12c Hapus Rute**:
  - Hilangkan `StoreRuteRequest / UpdateRuteRequest` dan pemanggilan `validated()`.
  - Pindahkan pemanggilan check `jadwal()->exists()` ke bagian awal di dalam method `destroy()`.
  - Jika `jadwal()->exists()` menghasilkan `true`, kembalikan redirect dengan error. Jika `false`, panggil `$rute->delete()` dan kembalikan redirect dengan success.

## Ilustrasi Sequence yang Benar

### Tambah Rute (store)
Admin -> Route (POST /admin/rute) -> RuteController -> StoreRuteRequest -> RuteController -> Rute Model -> Database -> Rute Model -> RuteController -> View (redirect dengan success)
*(Jika validasi gagal: StoreRuteRequest -> RuteController -> View (redirect back dengan errors))*

### Ubah Rute (update)
Admin -> Route (PUT /admin/rute/{id}) -> RuteController -> UpdateRuteRequest -> RuteController -> Rute Model -> Database -> Rute Model -> RuteController -> View (redirect dengan success)
*(Jika validasi gagal: UpdateRuteRequest -> RuteController -> View (redirect back dengan errors))*

### Hapus Rute (destroy)
Admin -> Route (DELETE /admin/rute/{id}) -> RuteController -> Rute Model -> Database (cek jadwal()->exists()) -> Rute Model -> RuteController
├── [Jika Ada Jadwal] RuteController -> View (redirect dengan error)
└── [Jika Tidak Ada Jadwal] RuteController -> Rute Model -> Database (delete()) -> Rute Model -> RuteController -> View (redirect dengan success)

---

# AD-13 Kelola Armada

## Ringkasan Review
Belum sesuai. Diagram menggunakan istilah "nonaktifkan armada" yang tidak konsisten dengan fungsi hapus (*delete*) pada spesifikasi sistem, serta menggabungkan alur CRUD secara linear tanpa adanya percabangan.

## Kesalahan Diagram
- **Istilah Tidak Konsisten**: Tindakan Admin bertuliskan *"Tambah, ubah, atau nonaktifkan armada"*. Padahal di program, data Armada dihapus secara fisik via method `destroy()` dengan pengecekan dependensi driver/trip aktif.
- **Alur CRUD Menggabungkan Validasi**: Langkah *"Validasi data kendaraan"* dipaksa dilewati oleh alur Hapus/Nonaktif, padahal Hapus tidak membutuhkan validasi field kendaraan (hanya cek relasi driver & trip aktif).
- **Penulisan Tindakan Kurang Tepat**: Tindakan *"Simpan data armada terpisah dari driver"* kurang bermakna, cukup dituliskan *"Simpan data armada ke database"*.

## Langkah Perbaikan
- **Ubah kata "nonaktifkan" menjadi "hapus"** pada tindakan admin agar sesuai dengan fungsi database.
- **Tambahkan Decision Node** setelah menampilkan daftar armada untuk memisahkan alur: **Tambah**, **Ubah**, dan **Hapus**.
- **Untuk Cabang Tambah**:
  1. Input data armada (nama mobil, nomor plat, kapasitas, status).
  2. Klik Simpan.
  3. Cek validasi input (berdasarkan `StoreArmadaRequest`). Jika Valid, simpan ke database dan tampilkan pesan sukses (mengirim parameter `selected_id`). Jika tidak, tampilkan error dan kembali ke form.
- **Untuk Cabang Ubah**:
  1. Pilih armada dan klik Edit.
  2. Ubah data armada.
  3. Klik Simpan Perubahan.
  4. Cek validasi input (berdasarkan `UpdateArmadaRequest`). Jika Valid, update ke database dan tampilkan pesan sukses (dengan `selected_id`). Jika tidak, tampilkan error dan kembali ke form.
- **Untuk Cabang Hapus**:
  1. Pilih armada dan klik Hapus.
  2. Cek apakah armada memiliki driver (`driver()->exists()`) ATAU terikat pada trip aktif (`trips() -> ready / on_trip`).
  3. Jika terhubung ke driver atau trip aktif, tampilkan error terkait dan batalkan penghapusan. Jika bersih, hapus armada dari database dan tampilkan pesan sukses.

## Ilustrasi Alur yang Benar
Start
↓
Admin membuka menu Armada
↓
Sistem menampilkan daftar armada & kapasitas (admin.armada.index)
↓
Pilih Aksi?
├── Tambah
│   ↓
│   Admin mengisi form tambah armada & klik Simpan
│   ↓
│   Sistem memvalidasi input (StoreArmadaRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Simpan data armada baru ke database
│       ↓
│       Tampilkan notifikasi sukses & kembali ke daftar (dengan selected_id)
│
├── Ubah
│   ↓
│   Admin memilih armada, mengubah data pada form edit & klik Simpan Perubahan
│   ↓
│   Sistem memvalidasi input (UpdateArmadaRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Update data armada di database
│       ↓
│       Tampilkan notifikasi sukses & kembali ke daftar (dengan selected_id)
│
└── Hapus
    ↓
    Admin memilih armada & klik Hapus
    ↓
    Sistem memeriksa: Apakah armada terhubung driver ATAU sedang bertugas dalam trip aktif (ready/on_trip)?
    ├── Ya (Ada relasi aktif)
    │   ↓
    │   Tampilkan pesan error dependensi & kembali ke daftar
    └── Tidak
        ↓
        Hapus armada dari database
        ↓
        Tampilkan notifikasi sukses & kembali ke daftar armada
↓
End

---

# SD-13 Kelola Armada

## Ringkasan Review
Belum sesuai. Diagram menggabungkan `store()`, `update()`, dan `destroy()` dalam satu alur linear, menggunakan Form Request pada alur hapus, serta meletakkan pemeriksaan dependensi setelah penyimpanan database.

## Kesalahan Diagram
- **Penggabungan Controller Method**: Menggabungkan `store()`, `update()`, dan `destroy()`.
- **Form Request pada Hapus**: `StoreArmadaRequest` atau `UpdateArmadaRequest` dijalankan pada alur penghapusan.
- **Cek Relasi di Posisi yang Salah**: Pengecekan `driver()->exists()` dan `trips()->exists()` digambarkan terjadi *setelah* database menyimpan data (`save armada()`), yang secara logika terbalik untuk alur penghapusan.

## Langkah Perbaikan
- **Pisahkan menjadi 3 Sequence Diagram**: `SD-13a Tambah Armada`, `SD-13b Ubah Armada`, dan `SD-13c Hapus Armada`.
- **Untuk SD-13a Tambah Armada**:
  - Gunakan `StoreArmadaRequest` untuk validasi. 
  - Setelah valid, panggil `Armada::create()`.
  - Kembalikan redirect ke route `admin.armada.index` dengan parameter `selected_id` dan success flash message.
- **Untuk SD-13b Ubah Armada**:
  - Gunakan `UpdateArmadaRequest` dan panggil `Armada::update()`.
  - Kembalikan redirect ke route `admin.armada.index` dengan parameter `selected_id` dan success flash message.
- **Untuk SD-13c Hapus Armada**:
  - Hapus objek request validation.
  - Tunjukkan controller memanggil database di awal untuk cek `driver()->exists()` dan `trips()->whereIn('status_trip', ['ready', 'on_trip'])->exists()`.
  - Jika ada dependensi, kembalikan redirect dengan flash error. Jika bersih, panggil `$armada->delete()`.

## Ilustrasi Sequence yang Benar

### Tambah Armada (store)
Admin -> Route (POST /admin/armada) -> ArmadaController -> StoreArmadaRequest -> ArmadaController -> Armada Model -> Database -> Armada Model -> ArmadaController -> View (redirect ke index dengan selected_id & success)
*(Jika validasi gagal: StoreArmadaRequest -> ArmadaController -> View (redirect back dengan errors))*

### Ubah Armada (update)
Admin -> Route (PUT /admin/armada/{id}) -> ArmadaController -> UpdateArmadaRequest -> ArmadaController -> Armada Model -> Database -> Armada Model -> ArmadaController -> View (redirect ke index dengan selected_id & success)
*(Jika validasi gagal: UpdateArmadaRequest -> ArmadaController -> View (redirect back dengan errors))*

### Hapus Armada (destroy)
Admin -> Route (DELETE /admin/armada/{id}) -> ArmadaController -> Armada Model -> Database (cek driver()->exists() & trips()->exists()) -> Armada Model -> ArmadaController
├── [Jika Ada Dependensi] ArmadaController -> View (redirect dengan error)
└── [Jika Bersih] ArmadaController -> Armada Model -> Database (delete()) -> Armada Model -> ArmadaController -> View (redirect dengan success)

---

# AD-14 Kelola Driver

## Ringkasan Review
Sebaiknya Diganti. Diagram ini hanya menggambarkan alur Tambah (*Create*) driver dan melewatkan alur Ubah (*Update*) serta Hapus (*Delete*) sepenuhnya. Selain itu, logika validasi yang dimodelkan tidak mencerminkan implementasi nyata program Laravel.

## Kesalahan Diagram
- **Kehilangan Alur Ubah dan Hapus**: Diagram hanya merepresentasikan pembuatan driver baru.
- **Validasi Relasi Tidak Akurat**: Percabangan *"Driver memiliki satu armada?"* tidak logis karena admin memilih armada dari dropdown data armada aktif yang terdaftar (pasti bernilai satu). Validasi krusial seperti keunikan email pada tabel `users` dan format nomor HP dilewatkan.
- **Tidak Memodelkan Transaksi**: Pembuatan akun driver melibatkan dua tabel berbeda (`users` dan `drivers`). Alur transaksi database ini tidak terpetakan.

## Langkah Perbaikan
- **Buat ulang diagram aktivitas** dengan percabangan awal untuk memisahkan alur CRUD lengkap: **Tambah**, **Ubah**, dan **Hapus**.
- **Untuk Cabang Tambah**:
  1. Input data driver (nama, email, password, no HP, armada_id, status_driver).
  2. Klik Simpan.
  3. Cek validasi input (berdasarkan `StoreDriverRequest`). Jika Valid, sistem menjalankan transaksi database untuk: 1) membuat data di tabel `users` (role driver), 2) membuat data driver di tabel `drivers`. Jika transaksi sukses, commit database dan tampilkan notifikasi berhasil. Jika tidak valid/transaksi gagal, rollback dan kembali ke form dengan error.
- **Untuk Cabang Ubah**:
  1. Pilih driver dan klik Edit.
  2. Tampilkan detail driver dan dropdown armada aktif.
  3. Ubah data (termasuk opsi kata sandi) dan klik Simpan Perubahan.
  4. Cek validasi (`UpdateDriverRequest`). Jika Valid, jalankan transaksi database untuk meng-update tabel `users` dan `drivers`. Jika sukses, tampilkan pesan sukses (mengirim `selected_id`).
- **Untuk Cabang Hapus**:
  1. Pilih driver dan klik Hapus.
  2. Cek apakah driver terikat pada trip aktif (`trips() -> ready / on_trip`).
  3. Jika terikat trip aktif, tampilkan error ("Driver sedang bertugas...") dan batalkan. Jika tidak, hapus akun login `user` terkait (database secara cascade akan menghapus profil `driver` terkait) dan tampilkan pesan sukses.

## Ilustrasi Alur yang Benar
Start
↓
Admin membuka menu Driver
↓
Sistem menampilkan daftar driver (admin.drivers.index)
↓
Pilih Aksi?
├── Tambah
│   ↓
│   Admin mengisi form tambah driver & klik Simpan
│   ↓
│   Sistem memvalidasi input (StoreDriverRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Jalankan transaksi DB: Buat data User (role driver) & buat data Driver (user_id)
│       ↓
│       Transaksi Sukses?
│       ├── Tidak (Error DB/Exception)
│       │   ↓
│       │   Rollback transaksi, tampilkan pesan error & kembali ke form
│       └── Ya
│           ↓
│           Commit transaksi, tampilkan notifikasi sukses & kembali ke daftar
│
├── Ubah
│   ↓
│   Admin memilih driver & mengisi form edit driver & klik Simpan Perubahan
│   ↓
│   Sistem memvalidasi input (UpdateDriverRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Jalankan transaksi DB: Update data User & update data Driver
│       ↓
│       Transaksi Sukses?
│       ├── Tidak (Error DB)
│       │   ↓
│       │   Rollback, tampilkan pesan error & kembali ke form
│       └── Ya
│           ↓
│           Commit, tampilkan notifikasi sukses & kembali ke daftar (dengan selected_id)
│
└── Hapus
    ↓
    Admin memilih driver & klik Hapus
    ↓
    Sistem memeriksa: Apakah driver sedang ditugaskan dalam trip aktif (ready/on_trip)?
    ├── Ya
    │   ↓
    │   Tampilkan pesan error "Driver sedang bertugas dalam trip aktif" & kembali ke daftar
    └── Tidak
        ↓
        Jalankan transaksi DB: Hapus data User (secara cascade menghapus profil Driver)
        ↓
        Tampilkan notifikasi sukses & kembali ke daftar driver
↓
End

---

# SD-14 Kelola Driver

## Ringkasan Review
Sebaiknya Diganti. Diagram hanya memodelkan alur Tambah dan Ubah driver secara bersamaan, kehilangan alur Hapus, serta tidak menggambarkan penggunaan transaksi database (`DB::transaction`) untuk menyimpan data ke tabel `users` dan `drivers`.

## Kesalahan Diagram
- **Kehilangan Alur Delete**: Aksi `destroy()` tidak digambarkan.
- **Logika Update Salah**: Alur `update()` digambarkan membuat data user baru (`save user role=driver()`) bukan memperbarui user yang sudah ada.
- **Tidak Memodelkan Transaksi Database**: Penyimpanan ganda tabel `users` dan `drivers` tidak dikelompokkan dalam alur transaksi database terpadu (`begin`, `commit`, `rollback`).
- **Relasi Armada**: Tidak memodelkan konflik jika armada yang dipilih ternyata sudah digunakan oleh driver lain.

## Langkah Perbaikan
- **Pisahkan menjadi 3 Sequence Diagram**: `SD-14a Tambah Driver`, `SD-14b Ubah Driver`, dan `SD-14c Hapus Driver`.
- **Untuk SD-14a Tambah Driver**:
  - Modelkan pembungkus transaksi `DB::transaction()` setelah request divalidasi oleh `StoreDriverRequest`.
  - Tunjukkan controller memicu pembuatan `User` (email, password) -> Database menyimpan -> panggil pembuatan `Driver` (user_id, armada_id) -> Database menyimpan -> Commit transaksi.
- **Untuk SD-14b Ubah Driver**:
  - Gunakan `UpdateDriverRequest`.
  - Di dalam transaksi DB, panggil `User::update()` untuk memperbarui nama/email/password user, lalu panggil `Driver::update()` untuk memperbarui data supir dan armada.
- **Untuk SD-14c Hapus Driver**:
  - Tunjukkan controller memanggil database untuk cek kesibukan di trip aktif. Jika bersih, panggil `$driver->user->delete()` (penghapusan user memicu cascade delete pada data driver terkait di database).

## Ilustrasi Sequence yang Benar

### Tambah Driver (store)
Admin -> Route (POST /admin/drivers) -> DriverController -> StoreDriverRequest -> DriverController -> Database (DB::transaction begin) -> DriverController -> User Model -> Database (User::create()) -> DriverController -> Driver Model -> Database (Driver::create()) -> DriverController -> Database (DB::transaction commit) -> DriverController -> View (redirect dengan success)
*(Jika validasi gagal: StoreDriverRequest -> DriverController -> View (redirect back dengan errors))*
*(Jika transaksi DB gagal: DriverController -> Database (DB::transaction rollback) -> DriverController -> View (redirect back dengan error))*

### Ubah Driver (update)
Admin -> Route (PUT /admin/drivers/{id}) -> DriverController -> UpdateDriverRequest -> DriverController -> Database (DB::transaction begin) -> DriverController -> User Model -> Database (User::update()) -> DriverController -> Driver Model -> Database (Driver::update()) -> DriverController -> Database (DB::transaction commit) -> DriverController -> View (redirect ke index dengan selected_id & success)

### Hapus Driver (destroy)
Admin -> Route (DELETE /admin/drivers/{id}) -> DriverController -> Driver Model -> Database (cek trips aktif) -> Driver Model -> DriverController
├── [Jika Ada Trip Aktif] DriverController -> View (redirect dengan error)
└── [Jika Bersih] DriverController -> Database (DB::transaction begin) -> DriverController -> User Model -> Database (user->delete()) -> DriverController -> Database (DB::transaction commit) -> DriverController -> View (redirect dengan success)

---

# AD-15 Kelola Jadwal Keberangkatan

## Ringkasan Review
Sebaiknya Diganti. Diagram hanya menampilkan alur Tambah (*Create*) jadwal keberangkatan, mengabaikan alur Ubah (*Update*), Hapus (*Delete*), dan Toggle Status (Aktif/Nonaktif) yang merupakan kebutuhan fungsional sistem.

## Kesalahan Diagram
- **Kehilangan Operasi Utama**: Alur Ubah, Hapus, dan Toggle Status hilang.
- **Alur Menggantung (*Dead End*)**: Alur jika rute tidak ditemukan (*"Tampilkan pesan rute harus dibuat dulu"*) berhenti tanpa simpul selesai atau pengembalian ke halaman daftar.
- **Pemuatan Form**: Tidak menggambarkan pemuatan data Rute (`Rute::latest()->get()`) untuk mengisi dropdown jadwal.

## Langkah Perbaikan
- **Buat ulang diagram aktivitas** dengan percabangan setelah menu utama dibuka untuk memilih aksi: **Tambah**, **Ubah**, **Toggle Status**, atau **Hapus**.
- **Untuk Cabang Tambah**:
  1. Klik "Tambah Jadwal".
  2. Sistem memuat daftar rute dan menampilkan form tambah.
  3. Input tanggal, shift, jam, kuota, dan status -> klik Simpan.
  4. Cek validasi (`StoreJadwalRequest`). Jika Valid, simpan dan tampilkan sukses. Jika Tidak, tampilkan error dan kembali ke form.
- **Untuk Cabang Ubah**:
  1. Klik Edit pada salah satu jadwal.
  2. Sistem memuat rute dan menampilkan form edit dengan data terisi.
  3. Ubah kuota atau detail lain -> klik Simpan Perubahan.
  4. Cek validasi (`UpdateJadwalRequest`). Jika Valid, cek apakah kuota baru < jumlah kursi terpesan (`booked_seats`). Jika Ya, tampilkan error dan kembali ke form. Jika Tidak, update data jadwal (jika terpesan >= kuota baru, set status jadi 'penuh') -> Tampilkan pesan sukses.
- **Untuk Cabang Toggle Status**:
  1. Klik Toggle Status.
  2. Baca status jadwal. Jika 'aktif', ubah jadi 'nonaktif'. Jika 'nonaktif', cek jumlah terpesan >= kuota (jika ya set 'penuh', jika tidak set 'aktif').
  3. Simpan status baru -> Tampilkan pesan sukses.
- **Untuk Cabang Hapus**:
  1. Klik Hapus.
  2. Cek apakah jadwal memiliki data booking (`bookings()->exists()`).
  3. Jika Ya, tampilkan error ("Jadwal memiliki booking...") dan batalkan. Jika Tidak, hapus jadwal dari database dan tampilkan pesan sukses.

## Ilustrasi Alur yang Benar
Start
↓
Admin membuka menu Jadwal Keberangkatan
↓
Sistem menampilkan daftar jadwal (admin.jadwal.index)
↓
Pilih Aksi?
├── Tambah
│   ↓
│   Admin klik "Tambah Jadwal"
│   ↓
│   Sistem memuat daftar rute & menampilkan Form Tambah (admin.jadwal.create)
│   ↓
│   Admin mengisi form & klik Simpan
│   ↓
│   Sistem memvalidasi input (StoreJadwalRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Simpan jadwal keberangkatan ke database
│       ↓
│       Tampilkan notifikasi sukses & kembali ke daftar jadwal
│
├── Ubah
│   ↓
│   Admin memilih jadwal & klik "Edit"
│   ↓
│   Sistem memuat rute & menampilkan Form Edit dengan data terisi (admin.jadwal.edit)
│   ↓
│   Admin mengubah data & klik Simpan Perubahan
│   ↓
│   Sistem memvalidasi input (UpdateJadwalRequest)?
│   ├── Tidak
│   │   ↓
│   │   Tampilkan pesan error validasi & kembali ke form
│   └── Ya
│       ↓
│       Sistem memeriksa: Apakah kuota baru < jumlah kursi yang sudah dipesan?
│       ├── Ya
│       │   ↓
│       │   Tampilkan pesan error "Kapasitas tidak boleh lebih kecil dari jumlah kursi terpesan" & kembali ke form
│       └── Tidak
│           ↓
│           Apakah jumlah kursi terpesan >= kuota baru & status 'aktif'?
│           ├── Ya → Set status_jadwal = 'penuh'
│           └── Tidak → Gunakan status_jadwal sesuai input
│           ↓
│           Update jadwal di database
│           ↓
│           Tampilkan notifikasi sukses & kembali ke daftar jadwal
│
├── Toggle Status
│   ↓
│   Admin klik "Toggle Status" pada jadwal terpilih
│   ↓
│   Sistem memeriksa status jadwal saat ini:
│   ├── Jika status 'aktif'
│   │   ↓
│   │   Ubah status menjadi 'nonaktif'
│   └── Jika status 'nonaktif'
│       ↓
│       Sistem menghitung kursi terpesan: Apakah kursi terpesan >= kuota?
│       ├── Ya → Set status menjadi 'penuh'
│       └── Tidak → Set status menjadi 'aktif'
│   ↓
│   Simpan perubahan status jadwal ke database
│   ↓
│   Tampilkan notifikasi sukses & kembali ke daftar jadwal
│
└── Hapus
    ↓
    Admin memilih jadwal & klik Hapus
    ↓
    Sistem memeriksa: Apakah jadwal memiliki data booking terkait (`bookings()->exists()`)?
    ├── Ya
    │   ↓
    │   Tampilkan pesan error "Jadwal tidak dapat dihapus karena memiliki data booking terkait"
    └── Tidak
        ↓
        Hapus jadwal dari database
        ↓
        Tampilkan notifikasi sukses & kembali ke daftar jadwal
↓
End

---

# SD-15 Kelola Jadwal Keberangkatan

## Ringkasan Review
Sebaiknya Diganti. Diagram menggabungkan submit POST/PUT dengan query data master dropdown, melakukan sum booking sebelum record jadwal dibuat (pada alur Tambah/Store), serta mengabaikan alur Hapus.

## Kesalahan Diagram
- **Kehilangan Aksi Hapus**: Aksi `destroy()` tidak digambarkan.
- **Query Rute Salah Alur**: Pemanggilan `Rute::latest()->get()` berada di alur submit POST/PUT, padahal ini adalah query GET untuk memuat dropdown form.
- **Logika Sum Booking**: Melakukan perhitungan `bookings()->sum()` di awal sebelum record jadwal baru dibuat (pada `store`), padahal jadwal baru pasti memiliki 0 booking. Sum booking hanya valid saat proses `update` dan `toggleStatus`.
- **Salah Form Request**: Menggunakan `StoreJadwalRequest` pada alur `toggleStatus()`.

## Langkah Perbaikan
- **Pisahkan menjadi 4 Sequence Diagram**: `SD-15a Tambah Jadwal`, `SD-15b Ubah Jadwal`, `SD-15c Toggle Status Jadwal`, dan `SD-15d Hapus Jadwal`.
- **Untuk SD-15a Tambah Jadwal**:
  - Hapus pemanggilan `Rute::latest()->get()` dari alur simpan. (Modelkan terpisah untuk route GET `create()`, di mana `JadwalController` memanggil `Rute::latest()->get()`).
  - Controller langsung memanggil `StoreJadwalRequest` untuk validasi -> panggil `Jadwal::create()` -> kembalikan redirect index.
- **Untuk SD-15b Ubah Jadwal**:
  - Panggil `UpdateJadwalRequest` -> panggil `Jadwal::bookings()` sum `jumlah_penumpang` untuk memverifikasi kapasitas kuota baru -> panggil `$jadwal->update()`.
- **Untuk SD-15c Toggle Status Jadwal**:
  - Hapus penggunaan form request. Controller langsung memanggil database untuk cek status dan hitung sisa kuota, lalu panggil `$jadwal->update()`.
- **Untuk SD-15d Hapus Jadwal**:
  - Controller memanggil model `Jadwal` untuk cek `bookings()->exists()`. Jika bersih, panggil `delete()`.

## Ilustrasi Sequence yang Benar

### Tambah Jadwal (store)
Admin -> Route (POST /admin/jadwal) -> JadwalController -> StoreJadwalRequest -> JadwalController -> Jadwal Model -> Database (Jadwal::create()) -> Jadwal Model -> JadwalController -> View (redirect dengan success)
*(Jika validasi gagal: StoreRuteRequest -> RuteController -> View (redirect back dengan errors))*

*(Catatan: Langkah memuat Rute `Rute::latest()->get` terjadi di GET /admin/jadwal/create: Admin -> Route (GET /admin/jadwal/create) -> JadwalController -> Rute Model -> Database -> Rute Model -> JadwalController -> View (admin.jadwal.create))*

### Ubah Jadwal (update)
Admin -> Route (PUT /admin/jadwal/{id}) -> JadwalController -> UpdateJadwalRequest -> JadwalController -> Jadwal Model -> Database (Jadwal::bookings()->sum()) -> Jadwal Model -> JadwalController
├── [Jika Kuota Baru < Kursi Terpesan] JadwalController -> View (redirect back dengan error)
└── [Jika Valid] JadwalController -> Jadwal Model -> Database (Jadwal::update()) -> Jadwal Model -> JadwalController -> View (redirect dengan success)

### Toggle Status Jadwal (toggleStatus)
Admin -> Route (PUT /admin/jadwal/{id}/toggle) -> JadwalController -> Jadwal Model -> Database (baca status & hitung booked) -> Jadwal Model -> JadwalController -> Jadwal Model -> Database (update(['status_jadwal'])) -> Jadwal Model -> JadwalController -> View (redirect dengan success)

### Hapus Jadwal (destroy)
Admin -> Route (DELETE /admin/jadwal/{id}) -> JadwalController -> Jadwal Model -> Database (cek bookings()->exists()) -> Jadwal Model -> JadwalController
├── [Jika Ada Booking] JadwalController -> View (redirect dengan error)
└── [Jika Tidak Ada Booking] JadwalController -> Jadwal Model -> Database (delete()) -> Jadwal Model -> JadwalController -> View (redirect dengan success)

---

# AD-16 Kelola Booking

## Ringkasan Review
Belum sesuai. Diagram memodelkan tindakan Admin melihat dan membatalkan booking pelanggan. Namun, Use Case "Kelola Booking" oleh Admin tidak terdaftar di Use Case Diagram, dan langkah "catat DP hangus" tidak memiliki alur data operasional yang tepat.

## Kesalahan Diagram
- **Ketidaksesuaian Use Case Diagram**: Tidak ada use case "Kelola Booking" (Admin) di Use Case Diagram.
- **Logika "DP Hangus"**: Langkah *"catat DP hangus tanpa refund"* tidak diimplementasikan sebagai status pembayaran khusus di Laravel database. Status pembayaran DP tetap `terverifikasi` (karena uang masuk tetap tercatat), melainkan status booking yang diubah menjadi `cancelled`.
- **Kehilangan Observer**: Tidak menggambarkan pemicuan observer untuk mengembalikan kuota jadwal keberangkatan secara otomatis setelah booking dibatalkan.

## Langkah Perbaikan
- Tambahkan use case "Mengelola Booking" (Admin) di Use Case Diagram agar sinkron dengan dokumen SRS (UC-17) dan diagram aktivitas ini.
- **Perbaiki langkah pembatalan**: Ubah *"catat DP hangus tanpa refund"* menjadi *"Ubah status booking menjadi cancelled dan simpan alasan pembatalan"*.
- **Gambarkan Alur Observer**: Setelah data booking berhasil disimpan ke status `cancelled`, tunjukkan alur sistem (melalui `BookingObserver::saved()`) yang otomatis memicu `checkAndUpdateStatus()` pada model Jadwal untuk mengalkulasi dan membebaskan kuota kursi kembali.
- **Hapus Notifikasi WA Pembatalan untuk Admin**: 
  - **Implementasi Laravel**: Pengiriman notifikasi pembatalan via `FonnteService` hanya dituliskan pada method `cancel` di `BookingController` milik Pelanggan (untuk memberi tahu Admin dan Driver). Sedangkan pada `Admin\BookingController::cancel`, tidak ada pengiriman notifikasi WhatsApp pembatalan kepada pelanggan. Diagram harus mencerminkan implementasi ini.

## Ilustrasi Alur yang Benar
Start
↓
Admin membuka menu Daftar Booking Pelanggan
↓
Sistem menampilkan daftar booking (admin.bookings.index)
↓
Admin melakukan penyaringan (filter) berdasarkan status atau kode booking
↓
Admin memilih salah satu booking & klik Detail
↓
Sistem menampilkan detail booking & pembayaran (admin.bookings.show)
↓
Admin memutuskan aksi:
├── Hanya melihat detail → End
└── Membatalkan booking
    ↓
    Admin mengisi form alasan pembatalan & klik Konfirmasi
    ↓
    Sistem memvalidasi input alasan pembatalan (wajib diisi, maks 500 karakter)?
    ├── Tidak
    │   ↓
    │   Tampilkan pesan error validasi & kembali ke form
    └── Ya
        ↓
        Update status_booking = 'cancelled' dan simpan alasan_pembatalan ke database
        ↓
        Sistem (via BookingObserver) otomatis memperbarui status & kuota jadwal keberangkatan (`checkAndUpdateStatus()`)
        ↓
        Tampilkan notifikasi sukses "Booking berhasil dibatalkan" & kembali ke halaman detail booking
↓
End

---

# SD-16 Kelola Booking

## Ringkasan Review
Belum sesuai. Diagram memanggil metode fiktif `status tetap DP hangus()` pada model `Pembayaran` dan melewatkan pemicuan observer untuk mengembalikan kuota jadwal keberangkatan.

## Kesalahan Diagram
- **Metode Fiktif**: Memanggil `status tetap DP hangus()` ke model `Pembayaran`. Di program Laravel MVC proyek ini, tidak ada perubahan status data pembayaran saat pembatalan booking administratif dilakukan oleh admin.
- **Kehilangan Observer**: Tidak menggambarkan pemanggilan `BookingObserver` dan `Jadwal` model untuk pembebasan kuota kursi.
- **Notifikasi WhatsApp**: Jika diagram mencoba menggambarkan notifikasi WhatsApp pembatalan pada alur ini, itu bertentangan dengan source code Laravel karena `Admin\BookingController::cancel` tidak menggunakan `FonnteService` (fitur notifikasi pembatalan hanya diimplementasikan untuk pembatalan mandiri oleh pelanggan).

## Langkah Perbaikan
- **Hapus pemanggilan metode fiktif** `status tetap DP hangus()` ke objek `Pembayaran`.
- **Gambarkan Observers**: Setelah `Booking Model` memproses `update(status_booking=cancelled)`, gambarkan pemicuan `BookingObserver::saved()` secara otomatis.
- **Pembaruan Kuota**: Tunjukkan `BookingObserver` memanggil method `checkAndUpdateStatus()` ke objek `Jadwal Model` untuk melepaskan kuota kursi kembali di database.
- **Sesuaikan Notifikasi**: Pastikan tidak ada objek `FonnteService` atau `BookingWhatsappNotificationService` digambarkan pada alur pembatalan oleh Admin ini, sesuai implementasi kode Laravel yang ada.

## Ilustrasi Sequence yang Benar

### Melihat Detail Booking
Admin -> Route (GET /admin/bookings/{id}) -> BookingController -> Booking Model -> Database (load pelanggan, jadwal, pembayaran) -> Booking Model -> BookingController -> View (admin.bookings.show)

### Membatalkan Booking
Admin -> Route (PUT /admin/bookings/{id}/cancel) -> BookingController -> Request (validate alasan_pembatalan) -> BookingController -> Booking Model -> Database (update status_booking & alasan_pembatalan) -> Booking Model -> BookingObserver -> Jadwal Model -> Database (checkAndUpdateStatus() - update kuota/status jadwal) -> Jadwal Model -> BookingObserver -> BookingController -> View (redirect ke admin.bookings.show dengan success)
