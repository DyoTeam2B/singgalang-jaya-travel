# Tambahan Tabel Skenario & Hasil Pengujian

Dokumen ini berisi bagian-bagian **Tabel Skenario Pengujian** dan **Tabel Hasil Pengujian** yang sebelumnya belum ada atau belum lengkap pada laporan utama (`laporan_statement_branch.md`).

---

## 1. DashboardController.php (Admin) — Function index()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | ADC-01 → ADC-02 → ADC-03 → ADC-04 → ADC-05 → ADC-06 | - | Admin membuka halaman dashboard | Semua statistik dan data booking terbaru ditampilkan |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Admin membuka dashboard | Statistik dan data terbaru tampil | Sesuai Harapan | PASS |

---

## 2. DriverController.php — Function destroy()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Hapus driver dengan trip aktif | Ditolak, pesan error | Sesuai Harapan | PASS |
| TC-02   | Hapus driver tanpa trip aktif | User & Driver terhapus | Sesuai Harapan | PASS |
| TC-03   | Hapus driver, DB gagal | Rollback, pesan error | Sesuai Harapan | PASS |

---

## 3. JadwalController.php — Function index()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | JWC-01 → JWC-02 → JWC-03 → JWC-04 → JWC-05(F) → JWC-06(F) → JWC-07(T) → JWC-08 → JWC-09 | BR-01(F), BR-02(F), BR-03(T) | Akses halaman jadwal tab active tanpa search | Jadwal aktif mendatang ditampilkan |
| TC-02   | JWC-01 → JWC-02 → JWC-03 → JWC-04 → JWC-05(T) → JWC-06(T) → JWC-07(F) → JWC-08 → JWC-09 | BR-01(T), BR-02(T), BR-03(F) | Akses halaman jadwal tab history dengan search "Padang" | Jadwal riwayat dengan keyword "Padang" ditampilkan |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Tab active, tanpa search | Jadwal aktif tampil | Sesuai Harapan | PASS |
| TC-02   | Tab history, search "Padang" | Jadwal riwayat terfilter | Sesuai Harapan | PASS |

---

## 4. JadwalController.php — Function update()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Kuota=5, booked=8 | Gagal, kembali ke form dengan error | Sesuai Harapan | PASS |
| TC-02   | Kuota=8, booked=8, status=aktif | Update berhasil, status jadi 'penuh' | Sesuai Harapan | PASS |
| TC-03   | Kuota=12, booked=8 | Update berhasil, status tetap 'aktif' | Sesuai Harapan | PASS |

---

## 5. LaporanController.php — Function index() & getDateRange()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | LAC-01 → LAC-02 → LAC-03 → LAC-04 → LAC-05 → LAC-06 → LAC-07 → LAC-08 → LAC-09 → LAC-10 | BR-01(today) | Period = `today`, shift = `semua` | Laporan statistik hari ini tampil |
| TC-02   | LAC-01 → LAC-02 → LAC-03 → LAC-04 → LAC-05 → LAC-06 → LAC-07 → LAC-08 → LAC-09 → LAC-10 | BR-01(30days) | Period = `30days`, shift = `pagi` | Laporan statistik 30 hari terakhir |
| TC-03   | LAC-01 → LAC-02 → LAC-03 → LAC-04 → LAC-05 → LAC-06 → LAC-07 → LAC-08 → LAC-09 → LAC-10 | BR-01(custom) | Period = `custom`, start_date dan end_date diisi manual | Laporan statistik rentang kustom |
| TC-04   | LAC-01 → LAC-02 → LAC-03 → LAC-04 → LAC-05 → LAC-06 → LAC-07 → LAC-08 → LAC-09 → LAC-10 | BR-01(default) | Period = `7days` (default) | Laporan statistik 7 hari terakhir |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Period=today, shift=semua | Laporan hari ini | Sesuai Harapan | PASS |
| TC-02   | Period=30days, shift=pagi | Laporan 30 hari, filter shift pagi | Sesuai Harapan | PASS |
| TC-03   | Period=custom, tanggal manual | Laporan rentang kustom | Sesuai Harapan | PASS |
| TC-04   | Period=7days (default) | Laporan 7 hari terakhir | Sesuai Harapan | PASS |

---

## 6. PembayaranController.php (Admin) — Function verify()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Verifikasi booking expired | Booking dibatalkan otomatis, ditolak | Sesuai Harapan | PASS |
| TC-02   | Verifikasi pembayaran baru | Status terverifikasi, notifikasi WA terkirim | Sesuai Harapan | PASS |
| TC-03   | Verifikasi ulang pembayaran yang sudah verified | Tidak kirim WA ganda | Sesuai Harapan | PASS |

---

## 7. RatingController.php (Admin) — Function index()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | RAC-01 → RAC-02 → RAC-03 → RAC-04(F) → RAC-06(F) → RAC-08 → RAC-09 | BR-01(F), BR-02(F) | Akses halaman rating tanpa filter | Semua rating ditampilkan |
| TC-02   | RAC-01 → RAC-02 → RAC-03 → RAC-04(T) → RAC-05 → RAC-06(T) → RAC-07 → RAC-08 → RAC-09 | BR-01(T), BR-02(T) | Akses dengan status="published", search="bagus" | Rating terpublikasi dengan keyword "bagus" |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Tanpa filter | Semua rating tampil | Sesuai Harapan | PASS |
| TC-02   | status=published, search="bagus" | Rating terfilter | Sesuai Harapan | PASS |

---

## 8. RuteController.php — Function destroy()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Hapus rute dengan jadwal aktif | Ditolak, pesan error | Sesuai Harapan | PASS |
| TC-02   | Hapus rute tanpa jadwal | Rute terhapus, redirect sukses | Sesuai Harapan | PASS |

---

## 9. TripController.php (Admin) — Function update()

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($driver->status_driver === 'nonaktif')` | TRUE / FALSE |
| BR-02  | `if (! $driver->armada)` | TRUE / FALSE |
| BR-03  | `if ($driver->armada->status_armada === 'nonaktif')` | TRUE / FALSE |
| BR-04  | `if ($hasScheduleConflict)` | TRUE / FALSE |
| BR-05  | `if ($hasArmadaConflict)` | TRUE / FALSE |
| BR-06  | `if ($totalPax < 3)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | TRC-01 → TRC-02(T) → TRC-03 → TRC-04(T) | BR-01(T) | Assign driver nonaktif ke trip | Ditolak, pesan error driver tidak aktif |
| TC-02   | TRC-01 → TRC-02(T) → TRC-03 → TRC-04(F) → TRC-05(T) | BR-01(F), BR-02(T) | Assign driver tanpa armada | Ditolak, pesan error belum punya armada |
| TC-03   | TRC-01 → TRC-02(T) → TRC-03 → TRC-04(F) → TRC-05(F) → TRC-06(T) | BR-01(F), BR-02(F), BR-03(T) | Assign driver dengan armada nonaktif | Ditolak, pesan error armada tidak aktif |
| TC-04   | ... → TRC-07 → TRC-08(T) | BR-04(T) | Assign driver yang sudah punya trip di tanggal & shift sama | Ditolak, pesan konflik jadwal |
| TC-05   | ... → TRC-09 → TRC-10(T) | BR-05(T) | Assign driver yang armadanya sudah dipakai trip lain | Ditolak, pesan konflik armada |
| TC-06   | ... → TRC-11(T) → TRC-12(T) → TRC-13(T) | BR-06(T) | Ubah status trip ke 'ready' tetapi penumpang baru 2 orang | Ditolak, minimal 3 penumpang belum terpenuhi |
| TC-07   | TRC-01 → TRC-02(T) → ... → TRC-11(T) → TRC-12(T) → TRC-13(F) → TRC-14 → TRC-15 | Semua BR(F) | Assign driver valid, ubah status ke 'ready' dengan 3+ penumpang | Trip berhasil diperbarui |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Driver nonaktif | Ditolak, error | Sesuai Harapan | PASS |
| TC-02   | Driver tanpa armada | Ditolak, error | Sesuai Harapan | PASS |
| TC-03   | Armada nonaktif | Ditolak, error | Sesuai Harapan | PASS |
| TC-04   | Konflik jadwal driver | Ditolak, error | Sesuai Harapan | PASS |
| TC-05   | Konflik armada | Ditolak, error | Sesuai Harapan | PASS |
| TC-06   | Penumpang < 3 orang | Ditolak, error | Sesuai Harapan | PASS |
| TC-07   | Semua kondisi valid | Trip diperbarui sukses | Sesuai Harapan | PASS |

---

## 10. DashboardController.php (Driver) — Function index()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Login sebagai user non-driver | Dashboard kosong, profil belum siap | Sesuai Harapan | PASS |
| TC-02   | Login sebagai driver terdaftar | Dashboard dengan statistik dan trip aktif | Sesuai Harapan | PASS |

---

## 11. TripController.php (Driver) — Function start()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Driver lain klik start | Akses ditolak HTTP 403 | Sesuai Harapan | PASS |
| TC-02   | Trip status 'new' / 'completed' | Dicegat, pesan error | Sesuai Harapan | PASS |
| TC-03   | Trip status 'ready' (valid) | Status jadi 'on_trip', waktu tercatat | Sesuai Harapan | PASS |

---

## 12. TripController.php (Driver) — Function dropoff()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Driver lain klik dropoff | Akses ditolak HTTP 403 | Sesuai Harapan | PASS |
| TC-02   | Penumpang belum lunas (cash) | Ditandai sampai, pelunasan cash dibuat otomatis | Sesuai Harapan | PASS |
| TC-03   | Penumpang sudah lunas via transfer | Ditandai sampai, tidak buat pembayaran baru | Sesuai Harapan | PASS |

---

## 13. BookingController.php (Public) — Function review()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Kode booking fiktif | Redirect, pesan error tidak ditemukan | Sesuai Harapan | PASS |
| TC-02   | Buka booking orang lain | HTTP 403 | Sesuai Harapan | PASS |
| TC-03   | Booking sudah dikonfirmasi | Redirect ke halaman pembayaran | Sesuai Harapan | PASS |
| TC-04   | Booking baru dibuat | View review pemesanan tampil | Sesuai Harapan | PASS |

---

## 14. BookingController.php (Public) — Function update()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | PBC-09 → PBC-10(T) | BR-01(T) | Kode booking tidak ditemukan | Redirect dengan pesan error |
| TC-02   | PBC-09 → PBC-10(F) → PBC-11(T) | BR-01(F), BR-02(T) | Ubah booking orang lain | HTTP 403 |
| TC-03   | PBC-09 → PBC-10(F) → PBC-11(F) → PBC-12(T) → PBC-13 | BR-01(F), BR-02(F), BR-03(T) | Ubah booking yang sudah di-assign trip | Ditolak, status tidak diizinkan |
| TC-04   | ... → PBC-14(T) → PBC-15(T) → PBC-16 | BR-04(T), BR-05(T) | Ubah jumlah penumpang melebihi kuota sisa | Ditolak, pesan kuota tidak cukup |
| TC-05   | ... → PBC-14(T) → PBC-15(F) → PBC-17 → PBC-18 | BR-04(T), BR-05(F) | Ubah jumlah penumpang dalam batas kuota | Berhasil update, redirect sukses |
| TC-06   | ... → PBC-14(F) → PBC-17 → PBC-18 | BR-04(F) | Ubah alamat jemput saja (tanpa ubah jumlah penumpang) | Berhasil update alamat, redirect sukses |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Kode booking salah | Redirect error | Sesuai Harapan | PASS |
| TC-02   | Booking milik orang lain | HTTP 403 | Sesuai Harapan | PASS |
| TC-03   | Booking sudah di-assign trip | Ditolak | Sesuai Harapan | PASS |
| TC-04   | Jumlah penumpang > kuota sisa | Ditolak, error kuota | Sesuai Harapan | PASS |
| TC-05   | Jumlah penumpang dalam batas | Berhasil update | Sesuai Harapan | PASS |
| TC-06   | Ubah alamat saja | Berhasil update | Sesuai Harapan | PASS |

---

## 15. HomeController.php — Function index()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | HMC-01 → HMC-02 → HMC-03 → HMC-04(F) → HMC-05 → HMC-06(F) → HMC-09 | BR-01(F), BR-02(F) | Tidak ada ulasan published dan tidak ada trip completed | Landing page dengan rating default 4.9, on-time 99% |
| TC-02   | HMC-01 → HMC-02 → HMC-03 → HMC-04(T) → HMC-05 → HMC-06(T) → HMC-07 → HMC-08(T) → HMC-09 | BR-01(T), BR-02(T), BR-03(T) | Ada ulasan published dan trip completed tepat waktu | Landing page dengan rating rerata aktual, on-time dihitung |
| TC-03   | HMC-01 → ... → HMC-08(F) → HMC-09 | BR-03(F) | Trip completed terlambat lebih dari 3 jam | Trip tidak dihitung sebagai on-time |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | DB kosong (belum ada ulasan/trip) | Rating default 4.9, on-time 99% | Sesuai Harapan | PASS |
| TC-02   | Ada ulasan dan trip tepat waktu | Statistik dihitung aktual | Sesuai Harapan | PASS |
| TC-03   | Trip terlambat > 3 jam | Tidak dihitung on-time | Sesuai Harapan | PASS |

---

## 16. JadwalPublicController.php — Function index()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | JPC-01 → JPC-02 → JPC-03(F) → JPC-05(F) → JPC-07(F) → JPC-09 → JPC-10 | BR-01(F), BR-02(F), BR-03(F) | Request kosong (tanpa filter) | Tampil semua jadwal mendatang |
| TC-02   | JPC-01 → JPC-02 → JPC-03(T) → JPC-04 → JPC-05(T) → JPC-06 → JPC-07(T) → JPC-08 → JPC-09 → JPC-10 | BR-01(T), BR-02(T), BR-03(T) | Mengisi filter asal, tujuan, dan tanggal | Tampil data terfilter spesifik |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Tanpa filter | Semua jadwal mendatang tampil | Sesuai Harapan | PASS |
| TC-02   | Asal, tujuan, tanggal diisi | Data jadwal terfilter | Sesuai Harapan | PASS |

---

## 17. PembayaranController.php (Public) — Function store()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | PPC-01 → PPC-02(T) | BR-01(T) | Kode booking tidak ditemukan | Redirect dengan pesan error |
| TC-02   | PPC-01 → PPC-02(F) → PPC-03(T) | BR-01(F), BR-02(T) | Upload bukti bayar booking orang lain | HTTP 403 |
| TC-03   | PPC-01 → PPC-02(F) → PPC-03(F) → PPC-04(T) | BR-01(F), BR-02(F), BR-03(T) | Upload bukti bayar booking yang sudah kedaluwarsa | Booking di-expire otomatis, redirect error |
| TC-04   | PPC-01 → PPC-02(F) → PPC-03(F) → PPC-04(F) → PPC-05(T) → PPC-06 | BR-01(F), BR-02(F), BR-03(F), BR-04(T) | Upload bukti bayar booking yang sudah diverifikasi | Redirect info, sudah diproses |
| TC-05   | PPC-01 → PPC-02(F) → PPC-03(F) → PPC-04(F) → PPC-05(F) → PPC-07 → PPC-08 → PPC-09 → PPC-10 → PPC-11 | BR-01(F), BR-02(F), BR-03(F), BR-04(F) | Upload bukti bayar booking baru valid | Bukti tersimpan, status menunggu verifikasi |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Kode booking salah | Redirect error | Sesuai Harapan | PASS |
| TC-02   | Booking milik orang lain | HTTP 403 | Sesuai Harapan | PASS |
| TC-03   | Booking kedaluwarsa | Expire otomatis, redirect error | Sesuai Harapan | PASS |
| TC-04   | Booking sudah diverifikasi | Redirect info | Sesuai Harapan | PASS |
| TC-05   | Upload bukti valid | Status menunggu verifikasi | Sesuai Harapan | PASS |

---

## 18. CekBookingController.php — Function index()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Buka halaman cek pertama kali | Form pencarian kosong | Sesuai Harapan | PASS |
| TC-02   | Kode booking salah | Kembali ke form, alert error | Sesuai Harapan | PASS |
| TC-03   | Kode booking benar | Info manifest & status pembayaran tampil | Sesuai Harapan | PASS |

---

## 19. ProfileController.php — Function edit()

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | PFC-01 → PFC-02(admin) → PFC-03 | BR-01(admin) | Admin membuka halaman profil | Render view profile admin |
| TC-02   | PFC-01 → PFC-02(driver) → PFC-03 | BR-01(driver) | Driver membuka halaman profil | Render view profile driver |
| TC-03   | PFC-01 → PFC-02(pelanggan) → PFC-03 | BR-01(pelanggan) | Pelanggan membuka halaman profil | Render view profile pelanggan |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Login sebagai Admin | View admin-edit tampil | Sesuai Harapan | PASS |
| TC-02   | Login sebagai Driver | View driver-edit tampil | Sesuai Harapan | PASS |
| TC-03   | Login sebagai Pelanggan | View public-edit tampil | Sesuai Harapan | PASS |

---

## 20. RatingController.php (Public) — Function store()

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Kode booking salah | Redirect index dengan error | Sesuai Harapan | PASS |
| TC-02   | Rating pada booking yang masih aktif | Dicegat, harus selesai dulu | Sesuai Harapan | PASS |
| TC-03   | Rating kedua kali pada trip yang sama | Dicegat, ulasan hanya bisa sekali | Sesuai Harapan | PASS |
| TC-04   | Rating pertama kali setelah trip selesai | Ulasan tersimpan, status menunggu moderasi | Sesuai Harapan | PASS |
