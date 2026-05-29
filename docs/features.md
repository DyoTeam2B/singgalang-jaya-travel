# Login

## Tujuan fitur
Memungkinkan admin dan driver masuk ke sistem sesuai hak akses masing-masing.

## Aktor
- Admin
- Driver

## Alur fitur
User membuka halaman login → input email dan password → sistem melakukan validasi → sistem mengecek role user → sistem mengarahkan user ke dashboard sesuai role.

## Route / Controller terkait

GET /login  
POST /login  
POST /logout  

AuthController

## Screenshot fitur
- Halaman Login
- Dashboard Admin
- Dashboard Driver

---

# Logout

## Tujuan fitur
Memungkinkan user keluar dari sistem dengan aman.

## Aktor
- Admin
- Driver

## Alur fitur
User menekan tombol logout → sistem menghapus session login → user kembali ke halaman login.

## Route / Controller terkait

POST /logout  

AuthController

## Screenshot fitur
- Tombol logout
- Halaman login

---

# Booking Travel

## Tujuan fitur
Memungkinkan pelanggan melakukan pemesanan travel secara online.

## Aktor
- Pelanggan

## Alur fitur
Pelanggan membuka website → memilih tujuan dan jadwal → mengisi form booking → sistem menyimpan data booking → sistem membuat ID booking otomatis → status booking menjadi “Menunggu DP”.

## Route / Controller terkait

GET /booking  
POST /booking  

BookingController

## Screenshot fitur
- Form booking
- Halaman sukses booking

---

# Cek Status Booking

## Tujuan fitur
Memungkinkan pelanggan melihat status pemesanan dan pembayaran.

## Aktor
- Pelanggan

## Alur fitur
Pelanggan memasukkan ID booking → sistem mencari data booking → sistem menampilkan status booking dan pembayaran.

## Route / Controller terkait

GET /cek-status  
POST /cek-status  

BookingStatusController

## Screenshot fitur
- Halaman cek status
- Detail booking

---

# Upload Bukti Pembayaran DP

## Tujuan fitur
Memungkinkan pelanggan mengupload bukti pembayaran DP sebagai konfirmasi pemesanan.

## Aktor
- Pelanggan

## Alur fitur
Pelanggan membuka halaman pembayaran → sistem menampilkan instruksi pembayaran → pelanggan upload bukti transfer → sistem menyimpan bukti pembayaran → status menjadi “Menunggu Verifikasi”.

## Route / Controller terkait

GET /payment/{booking}  
POST /payment/upload  

PaymentController

## Screenshot fitur
- Upload bukti pembayaran
- Instruksi pembayaran

---

# Verifikasi Pembayaran

## Tujuan fitur
Memungkinkan admin memverifikasi pembayaran DP pelanggan.

## Aktor
- Admin

## Alur fitur
Admin membuka dashboard pembayaran → melihat daftar pembayaran masuk → admin memverifikasi bukti pembayaran → sistem mengubah status booking menjadi “DP Terverifikasi”.

## Route / Controller terkait

GET /admin/payments  
POST /admin/payments/{id}/verify  

PaymentVerificationController

## Screenshot fitur
- Dashboard pembayaran
- Detail bukti transfer

---

# Pelunasan Pembayaran

## Tujuan fitur
Memungkinkan pelanggan melakukan pelunasan pembayaran perjalanan.

## Aktor
- Pelanggan
- Driver
- Admin

## Alur fitur
Pelanggan melakukan pelunasan → pembayaran dilakukan melalui transfer atau tunai ke driver → admin memverifikasi pelunasan → status booking berubah menjadi “Lunas”.

## Route / Controller terkait

POST /payment/payoff  
POST /admin/payments/{id}/verify-payoff  

PaymentSettlementController

## Screenshot fitur
- Halaman pelunasan
- Status pembayaran lunas

---

# Manajemen Trip

## Tujuan fitur
Memungkinkan admin mengatur pembagian trip perjalanan.

## Aktor
- Admin

## Alur fitur
Admin melihat daftar booking → sistem menghitung kapasitas kendaraan → admin membuat trip → pelanggan dimasukkan ke trip → trip dikonfirmasi.

## Route / Controller terkait

GET /admin/trips  
POST /admin/trips  
PUT /admin/trips/{id}  

TripController

## Screenshot fitur
- Halaman manajemen trip
- Detail trip

---

# Assign Driver

## Tujuan fitur
Memungkinkan admin menentukan driver untuk setiap trip.

## Aktor
- Admin

## Alur fitur
Admin memilih trip → memilih driver tersedia → sistem menyimpan assignment → driver menerima notifikasi perjalanan.

## Route / Controller terkait

POST /admin/trips/{id}/assign-driver  

DriverAssignmentController

## Screenshot fitur
- Form assign driver
- Informasi driver trip

---

# Dashboard Driver

## Tujuan fitur
Memungkinkan driver melihat jadwal dan data penumpang.

## Aktor
- Driver

## Alur fitur
Driver login → sistem menampilkan daftar trip → driver melihat manifest penumpang → driver melihat lokasi jemput melalui peta.

## Route / Controller terkait

GET /driver/dashboard  
GET /driver/trips/{id}  

DriverDashboardController

## Screenshot fitur
- Dashboard driver
- Manifest penumpang
- Peta lokasi

---

# Konfirmasi Penjemputan

## Tujuan fitur
Memungkinkan driver mengupdate status penjemputan penumpang.

## Aktor
- Driver

## Alur fitur
Driver membuka data trip → memilih penumpang → driver melakukan konfirmasi penjemputan → sistem mengubah status penumpang menjadi “Sudah Dijemput”.

## Route / Controller terkait

POST /driver/pickup/{booking}  

PickupConfirmationController

## Screenshot fitur
- Halaman manifest
- Status penjemputan

---

# Upload Bukti Setoran Driver

## Tujuan fitur
Memungkinkan driver mengupload bukti setoran hasil perjalanan.

## Aktor
- Driver

## Alur fitur
Driver menyelesaikan trip → driver menginput nominal setoran → upload bukti transfer → sistem menyimpan data setoran → admin menerima notifikasi.

## Route / Controller terkait

GET /driver/setoran  
POST /driver/setoran  

DriverDepositController

## Screenshot fitur
- Form upload setoran
- Riwayat setoran