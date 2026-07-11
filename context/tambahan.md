2. ubah susunan kalimat perjalanan padang Panjang ke pekanbaru
3. jumlah penumpang diambil dari dari database
4. rating diambil dari database rumusnya Total Bintang ÷ Jumlah Total Ulasan (contoh terdapat 20 ulasan bintang 5 dan 1 ulasan bintang 1 perhitungannya)
20x5+1x1/21 = 101/21 = 4,80
5. ubah text 2 jam jadi 2 jam -+
6. ubah logo di kenapa memilih singgalang jaya travel
7. sesuaikan jumlah penumpang
8. sesuaikan jumlah rating
9. sesuaikan jumlah rute
10. sesuaikan tepat Waktu
11. pada jadwal keberangkatan shift malam perbaiki bug logo malam yg terpotong
12. pada jadwal keberangkatan terkini, hilangkan tuilisan "Full AC" pada jadwal
13. warna component shift malam ganti hitam tulisan putih
14. bagian armada hapus tulisan Padang Panjang ↔️ Pekanbaru
15. foto armada nanti sesuaikan dengan data armada di database
16. tulisan Kendaraan ber-AC, bersih, dan terawat untuk kenyamanan perjalanan Anda. ganti jangan pakai kendaraan ber ac
17. di card armada tampilkan status armada terkini
18. pada booking saya bagian booking aktif rute dan jadwal tanggal 25 jun 2026 dan sekarang tanggal 29 jun 2026 seharusnya booking tidak aktif lagi kecuali pembayaran dp sudah diverifikasi dan sudah masuk trip jadi dalam perjalanan kalua dp belum dibyar dan sudah lewat tenggat harusnya booking tidak aktif lagi yaitu dihapus
19. pada bagian profil pelanggan seharusnya ada tambah, edit foto profil tapi ini bersifat opsional, tapi fitur tetap ada
20. bagian isi titik jemput apakah bisa missal isi alamat trus markup peta otomatis menandakan sesuai alamat
21. di detail booking lebih di tonjolkan berapa sisa bayar uang biar kelihatan oleh pelanggan

halaman admin
21. bagian armada yaitu tambahkan saat crud yaitu foto armada bisa opsional saat create dan bisa ditambahkan saat edit
22. foto armada nantinya akan terhubung dengan foto armada di landing page
23. pada jadwal saat edit hapus opsi untuk pilihan penuh karena kenapa pula admin yg isi travel penuh atau tidak ganggu system (saat penuh jadinya gak muncul di pelanggan dan kuota masih 0 jadi mending dihapus)
24. perbaikan bug saat jadwal penuh di create trip gak muncul jadwalnya seharusnya kan muncul biar bisa dimasukkan ke trip (kondisi ini terjadi saat pelanggan booking 5 di jadwal kuota 5) baca point 23 sepertinya terhubung
25. pada detail trip admin cuman bisa setujui tidak bisaa mulai trip karena itu tugas driver aja biar jelas
26. pada export csv di laporan buatkan formatnya dengan rapi atau pakai pdf saja biar jelas
27. kegiatan yg dilakukan pelanggan buatkan agar bell notifikasinya masuk di dashboar admin jadi tau apa kegiatan yg terjadi contoh saat pelanggan booking saat batal, saat driver mulai perjalanan biar jelas

general
28. perbaiki format untuk notifikasi whatsaap biar terbaca (mintak ai buatkan format rapi dulu ntar di cek baru setujui jadi mintak contoh dulu) notifikasi ke pelanggan dan driver

driver
29. buatkan suatu tombol yg memudahkan untuk status driver missal di ada masalah jadi nanti ada tombol missal pending atau istirahat tersedia (kayak quick tombol)
30. untuk peta rute di driver ganti alamat pelanggan
31. mungkin di Riwayat trip bisa bikin filter itu untuk pendapatan misal hari ini, 7 hari lalu, bulan ini seperti admin