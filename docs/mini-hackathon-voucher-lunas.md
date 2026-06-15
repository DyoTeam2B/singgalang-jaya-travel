# Mini Hackathon - Voucher Diskon 10% Pembayaran Lunas

## 1. Komponen yang Berdampak

Change request ini menambahkan voucher diskon 10% jika pelanggan memilih pembayaran lunas. Walaupun perubahan terlihat sederhana, dampaknya menyentuh beberapa bagian sistem karena berhubungan dengan aturan harga, penyimpanan transaksi, dan tampilan pelanggan/admin.

| Komponen | Terdampak | Keterangan Dampak |
|---|---|---|
| Database | Ya | Tabel `pembayaran` perlu menyimpan informasi voucher, persentase diskon, dan nominal diskon agar transaksi lunas bisa diaudit. |
| Model | Ya | Model `Pembayaran` menjadi tempat konstanta nominal DP, kode voucher `LUNAS10`, persentase diskon 10%, dan helper perhitungan nominal lunas. |
| Controller | Ya | `PembayaranController` harus menentukan apakah pelanggan memilih DP atau pelunasan, lalu menghitung nominal transfer dari backend. |
| Request Validation | Ya | `StorePembayaranRequest` perlu memvalidasi `jenis_pembayaran` agar hanya menerima `dp` atau `pelunasan`. |
| View Pelanggan | Ya | Halaman pembayaran menampilkan pilihan Bayar DP dan Bayar Lunas dengan voucher diskon 10%. |
| View Cek Booking | Ya | Halaman cek booking menampilkan informasi voucher, nominal diskon, dan sisa pembayaran Rp0 jika pelanggan sudah memilih pelunasan. |
| View Admin Pembayaran | Ya | Admin perlu melihat apakah bukti pembayaran adalah DP atau pelunasan dengan voucher `LUNAS10`. |
| View Admin Booking | Ya | Detail booking admin perlu menampilkan pembayaran terakhir dan informasi diskon jika ada. |
| Dashboard Admin | Ya | Perhitungan revenue perlu memperhatikan pembayaran lunas setelah diskon agar tidak melebihkan pendapatan. |
| Route | Tidak | Route pembayaran yang sudah ada tetap digunakan, sehingga tidak perlu endpoint baru. |
| Middleware | Tidak | Hak akses pelanggan dan admin tetap memakai middleware yang sudah tersedia. |
| Dokumentasi | Ya | Changelog dan dokumen mini hackathon perlu diperbarui agar perubahan tercatat. |
| Testing | Ya | Test ditambahkan untuk memastikan pembayaran lunas menyimpan voucher, diskon, nominal bayar, dan status booking dengan benar. |

## 2. Risiko

1. Nominal pembayaran bisa dimanipulasi jika dihitung dari input frontend.

   Mitigasi: nominal DP, diskon, dan total pembayaran lunas dihitung ulang di backend melalui model/controller.

2. Alur pembayaran DP lama bisa terganggu.

   Mitigasi: opsi DP tetap disediakan dan menggunakan nominal tetap Rp50.000 seperti alur sebelumnya.

3. Admin bisa keliru membedakan pembayaran DP dan pembayaran lunas.

   Mitigasi: tampilan admin menampilkan label pembayaran `DP` atau `Pelunasan`, termasuk kode voucher jika ada.

4. Revenue bisa tidak akurat jika tetap memakai harga sebelum diskon.

   Mitigasi: dashboard admin memperhitungkan nominal pembayaran lunas setelah diskon.

5. Data pembayaran lama tidak memiliki informasi voucher.

   Mitigasi: kolom baru menggunakan nilai default aman, yaitu `voucher_kode` nullable, `diskon_persen` default 0, dan `nominal_diskon` default 0.

6. Pembulatan diskon bisa tidak konsisten.

   Mitigasi: diskon dihitung terpusat menggunakan helper `hitungDiskonLunas()` pada model `Pembayaran`.

## 3. Hasil Implementasi

Fitur voucher pembayaran lunas berhasil diterapkan pada sistem Singgalang Jaya Travel.

Hasil utama:

- Pelanggan dapat memilih dua jenis pembayaran pada halaman pembayaran: `Bayar DP` atau `Bayar Lunas`.
- Jika pelanggan memilih `Bayar Lunas`, sistem otomatis menerapkan voucher `LUNAS10`.
- Diskon 10% dihitung dari `total_harga` booking.
- Total transfer lunas adalah total harga setelah dikurangi diskon.
- Sisa bayar ke driver menjadi Rp0 untuk pembayaran lunas.
- Data voucher tersimpan pada tabel `pembayaran`.
- Admin dapat melihat tipe pembayaran dan informasi voucher pada halaman verifikasi pembayaran.
- Detail booking pelanggan dan admin menampilkan informasi diskon jika pembayaran menggunakan voucher.
- Changelog diperbarui untuk mencatat fitur mini hackathon.
- Test fitur ditambahkan untuk memastikan perhitungan dan penyimpanan voucher berjalan benar.

Contoh perhitungan:

| Item | Nominal |
|---|---:|
| Total harga booking | Rp200.000 |
| Voucher `LUNAS10` 10% | - Rp20.000 |
| Total transfer lunas | Rp180.000 |
| Sisa bayar ke driver | Rp0 |

Verifikasi yang dilakukan:

- `php artisan migrate` berhasil.
- `php artisan test` berhasil dengan 36 test lulus.
- `npm run build` berhasil.
- Halaman pembayaran dicek secara visual melalui browser lokal.

## 4. Refleksi

Perubahan ini menunjukkan bahwa change request kecil seperti diskon 10% tetap dapat berdampak ke banyak komponen sistem. Fitur tidak cukup ditambahkan di tampilan pelanggan saja, karena aturan harga harus konsisten dari database, backend, admin, hingga laporan pendapatan.

Pelajaran utama dari implementasi ini adalah perhitungan nominal pembayaran harus dilakukan di backend. Jika nominal hanya dihitung di frontend, pelanggan berpotensi mengirim nilai yang tidak valid. Dengan memusatkan aturan pada model dan controller, sistem menjadi lebih aman, mudah diuji, dan lebih mudah dirawat.

Mini hackathon ini juga memperlihatkan pentingnya impact analysis sebelum coding. Dengan memetakan komponen terdampak lebih dulu, implementasi dapat dilakukan lebih terarah dan risiko regresi pada alur DP lama bisa dikurangi.
