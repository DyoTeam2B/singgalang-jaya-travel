# Dependency Proyek PBL Singgalang Jaya Travel

## Pendahuluan

Dokumen ini berisi identifikasi dependency/package yang digunakan pada pengembangan Sistem Informasi Singgalang Jaya Travel berbasis Laravel 13 dan TailwindCSS. Dependency dipilih berdasarkan kebutuhan sistem agar pengembangan lebih ringan, mudah dipahami, dan sesuai dengan fitur yang dibutuhkan.

---

# 1. Laravel Breeze

### What (Apa)
Laravel Breeze merupakan package autentikasi sederhana bawaan Laravel yang menyediakan fitur login, logout, reset password, dan manajemen session.

### Why (Mengapa)
Digunakan karena sistem membutuhkan autentikasi untuk Admin dan Driver sebelum mengakses sistem.

### Who (Siapa)
Admin dan Driver.

### When (Kapan)
Digunakan saat pengguna melakukan login dan logout.

### Where (Di mana)
Pada modul autentikasi sistem.

### How (Bagaimana)
Laravel Breeze menggunakan sistem autentikasi berbasis session dan middleware bawaan Laravel.

### Referensi
https://laravel.com/docs/starter-kits#laravel-breeze

---

# 2. Livewire v3

### What (Apa)
Livewire merupakan framework full-stack Laravel yang digunakan untuk membuat halaman interaktif tanpa perlu banyak menggunakan JavaScript.

### Why (Mengapa)
Digunakan untuk mempermudah pembuatan form booking, dashboard, upload bukti pembayaran, dan fitur interaktif lainnya.

### Who (Siapa)
Pelanggan, Admin, dan Driver.

### When (Kapan)
Digunakan ketika pengguna berinteraksi dengan sistem.

### Where (Di mana)
Pada halaman booking, dashboard admin, dan dashboard driver.

### How (Bagaimana)
Livewire menghubungkan frontend dan backend secara otomatis melalui AJAX.

### Referensi
https://livewire.laravel.com

---

# 3. Leaflet.js

### What (Apa)
Leaflet.js merupakan library JavaScript yang digunakan untuk menampilkan peta interaktif.

### Why (Mengapa)
Digunakan untuk menampilkan lokasi penjemputan pelanggan dan navigasi driver.

### Who (Siapa)
Pelanggan dan Driver.

### When (Kapan)
Saat pelanggan memilih lokasi penjemputan atau driver melihat lokasi pelanggan.

### Where (Di mana)
Pada halaman booking dan dashboard driver.

### How (Bagaimana)
Leaflet mengambil koordinat lokasi kemudian menampilkannya dalam bentuk peta interaktif.

### Referensi
https://leafletjs.com

---

# 4. Fonnte API

### What (Apa)
Fonnte merupakan layanan API WhatsApp untuk mengirim pesan secara otomatis.

### Why (Mengapa)
Digunakan untuk mengirim notifikasi booking, verifikasi pembayaran, dan informasi perjalanan.

### Who (Siapa)
Pelanggan, Admin, dan Driver.

### When (Kapan)
Saat pelanggan melakukan booking, pembayaran berhasil diverifikasi, atau driver ditugaskan.

### Where (Di mana)
Pada modul notifikasi sistem.

### How (Bagaimana)
Laravel mengirim request API ke Fonnte kemudian Fonnte meneruskan pesan ke WhatsApp pengguna.

### Referensi
https://fonnte.com

---

# 5. Laravel Debugbar

### What (Apa)
Laravel Debugbar merupakan package debugging Laravel.

### Why (Mengapa)
Digunakan untuk mempermudah proses pengembangan dan menemukan error.

### Who (Siapa)
Developer.

### When (Kapan)
Saat proses pengembangan dan pengujian sistem.

### Where (Di mana)
Pada environment development.

### How (Bagaimana)
Debugbar menampilkan informasi query database, route, request, dan error pada browser.

### Referensi
https://github.com/barryvdh/laravel-debugbar

---

## Catatan Implementasi

Sistem tidak menggunakan package Role Permission tambahan seperti Spatie Laravel Permission karena hak akses sistem hanya terdiri dari Admin dan Driver. Hak akses akan diimplementasikan menggunakan middleware custom Laravel sehingga dependency lebih ringan dan mudah dipelajari.