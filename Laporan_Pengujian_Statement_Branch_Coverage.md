# Laporan Pengujian Statement dan Branch Coverage
## Kelompok PBL: Singgalang Jaya Travel

Laporan ini berisi hasil pengujian unit menggunakan metode **White Box Testing** (khususnya **Statement Coverage** dan **Branch Coverage**) untuk seluruh controller pada aplikasi **Singgalang Jaya Travel**.

---

# DAFTAR ISI
1. [Auth Controllers](#1-auth-controllers)
   - [AuthenticatedSessionController.php](#11-authenticatedsessioncontrollerphp)
   - [RegisteredUserController.php](#12-registeredusercontrollerphp)
2. [Admin Controllers](#2-admin-controllers)
   - [ArmadaController.php](#21-armadacontrollerphp)
   - [BookingController.php](#22-bookingcontrollerphp-admin)
   - [DashboardController.php](#23-dashboardcontrollerphp-admin)
   - [DriverController.php](#24-drivercontrollerphp)
   - [JadwalController.php](#25-jadwalcontrollerphp)
   - [LaporanController.php](#26-laporancontrollerphp)
   - [PembayaranController.php](#27-pembayarancontrollerphp-admin)
   - [RatingController.php](#28-ratingcontrollerphp-admin)
   - [RuteController.php](#29-rutecontrollerphp)
   - [TripController.php](#210-tripcontrollerphp-admin)
3. [Driver Controllers](#3-driver-controllers)
   - [DashboardController.php](#31-dashboardcontrollerphp-driver)
   - [TripController.php](#32-tripcontrollerphp-driver)
4. [Public Controllers](#4-public-controllers)
   - [BookingController.php](#41-bookingcontrollerphp-public)
   - [HomeController.php](#42-homecontrollerphp)
   - [JadwalPublicController.php](#43-jadwalpubliccontrollerphp)
   - [PembayaranController.php](#44-pembayarancontrollerphp-public)
   - [CekBookingController.php](#45-cekbookingcontrollerphp)
   - [ProfileController.php](#46-profilecontrollerphp)
   - [RatingController.php](#47-ratingcontrollerphp-public)

---

# 1. Auth Controllers

## 1.1 AuthenticatedSessionController.php

Controller ini menangani proses autentikasi pengguna (login dan logout).

### Function store()
Fungsi ini memproses data login dan melakukan pengalihan halaman berdasarkan role pengguna (admin, driver, atau pelanggan).

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$request->authenticate();` | ASC-01 |
| 2  | `$request->session()->regenerate();` | ASC-02 |
| 3  | `$user = Auth::user();` | ASC-03 |
| 4  | `if($user->role === 'admin')` | ASC-04 |
| 5  | `return redirect()->intended(route('admin.dashboard', absolute: false));` | ASC-05 |
| 6  | `if($user->role === 'driver')` | ASC-06 |
| 7  | `return redirect()->intended(route('driver.dashboard', absolute: false));` | ASC-07 |
| 8  | `return redirect()->intended(route('home', absolute: false));` | ASC-08 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if($user->role === 'admin')` | TRUE / FALSE |
| BR-02  | `if($user->role === 'driver')` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | ASC-01 → ASC-02 → ASC-03 → ASC-04 → ASC-05 | BR-01(T) | Kredensial Admin Valid | Redirect ke dashboard admin |
| TC-02   | ASC-01 → ASC-02 → ASC-03 → ASC-04 → ASC-06 → ASC-07 | BR-01(F), BR-02(T) | Kredensial Driver Valid | Redirect ke dashboard driver |
| TC-03   | ASC-01 → ASC-02 → ASC-03 → ASC-04 → ASC-06 → ASC-08 | BR-01(F), BR-02(F) | Kredensial Pelanggan Valid | Redirect ke halaman home |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Admin login | Redirect ke dashboard admin | Sesuai Harapan | PASS |
| TC-02   | Driver login | Redirect ke dashboard driver | Sesuai Harapan | PASS |
| TC-03   | Pelanggan login | Redirect ke halaman home | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:**
  * TC-01: $SC = \frac{5}{8} \times 100\% = 62.5\%$
  * TC-02: $SC = \frac{6}{8} \times 100\% = 75\%$
  * TC-03: $SC = \frac{6}{8} \times 100\% = 75\%$
  * Keseluruhan: $SC = \frac{8}{8} \times 100\% = 100\%$
* **Branch Coverage:**
  * TC-01: $BC = \frac{1}{4} \times 100\% = 25\%$ (BR-01 True dieksekusi)
  * TC-02: $BC = \frac{2}{4} \times 100\% = 50\%$ (BR-01 False, BR-02 True dieksekusi)
  * TC-03: $BC = \frac{2}{4} \times 100\% = 50\%$ (BR-01 False, BR-02 False dieksekusi)
  * Keseluruhan: $BC = \frac{4}{4} \times 100\% = 100\%$

---

### Function destroy()
Fungsi ini digunakan untuk memproses logout pengguna.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `Auth::guard('web')->logout();` | ASC-09 |
| 2  | `$request->session()->invalidate();` | ASC-10 |
| 3  | `$request->session()->regenerateToken();` | ASC-11 |
| 4  | `return redirect('/');` | ASC-12 |

#### B. Tabel Branch
*Tidak memiliki percabangan.*

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | ASC-09 → ASC-10 → ASC-11 → ASC-12 | - | Pengguna login mengklik logout | Sesi dihapus dan redirect ke '/' |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Klik Logout | Sesi dihapus, redirect ke '/' | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:** $SC = \frac{4}{4} \times 100\% = 100\%$
* **Branch Coverage:** $BC = 100\%$ (tidak ada percabangan)

---

## 1.2 RegisteredUserController.php

Membantu registrasi pelanggan baru secara publik.

### Function store()
Fungsi ini memvalidasi pendaftaran baru, membuat akun user, membuat data pelanggan, dan otomatis masuk ke sistem (login).

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$validated = $request->validate([...]);` | RUC-01 |
| 2  | `$user = User::create([...]);` | RUC-02 |
| 3  | `Pelanggan::create([...]);` | RUC-03 |
| 4  | `event(new Registered($user));` | RUC-04 |
| 5  | `Auth::login($user);` | RUC-05 |
| 6  | `return redirect(route('home', absolute: false));` | RUC-06 |

#### B. Tabel Branch
*Tidak memiliki percabangan kondisional pada alur logika utama (validasi dilewati).*

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | RUC-01 → RUC-02 → RUC-03 → RUC-04 → RUC-05 → RUC-06 | - | Form pendaftaran diisi lengkap dan benar | Akun dan data pelanggan dibuat, otomatis login, redirect ke halaman utama |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Mengisi registrasi valid | Akun terdaftar, login sukses, redirect home | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:** $SC = \frac{6}{6} \times 100\% = 100\%$
* **Branch Coverage:** $BC = 100\%$ (tidak ada percabangan kondisional)

---

# 2. Admin Controllers

## 2.1 ArmadaController.php

Mengelola data armada (mobil travel) milik agen.

### Function index()
Menampilkan daftar armada dengan fitur pencarian dan filter status.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$search = $request->input('search');` | AMC-01 |
| 2  | `$statusFilter = $request->input('status');` | AMC-02 |
| 3  | `$query = Armada::query()->with(['driver', 'trips']);` | AMC-03 |
| 4  | `if ($search)` | AMC-04 |
| 5  | `$query->where(function ($q) use ($search) { ... });` | AMC-05 |
| 6  | `if ($statusFilter)` | AMC-06 |
| 7  | `$query->where('status_armada', $statusFilter);` | AMC-07 |
| 8  | `$armadas = $query->latest()->paginate(10)->withQueryString();` | AMC-08 |
| 9  | `$selectedArmadaId = $request->input('selected_id');` | AMC-09 |
| 10 | `$selectedArmada = null;` | AMC-10 |
| 11 | `if ($selectedArmadaId)` | AMC-11 |
| 12 | `$selectedArmada = Armada::with(...)->find($selectedArmadaId);` | AMC-12 |
| 13 | `if (!$selectedArmada && $armadas->count() > 0)` | AMC-13 |
| 14 | `$selectedArmada = Armada::with(...)->find($armadas->first()->id);` | AMC-14 |
| 15 | `return view('admin.armada.index', compact(...));` | AMC-15 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($search)` | TRUE / FALSE |
| BR-02  | `if ($statusFilter)` | TRUE / FALSE |
| BR-03  | `if ($selectedArmadaId)` | TRUE / FALSE |
| BR-04  | `if (!$selectedArmada && $armadas->count() > 0)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | AMC-01 → AMC-02 → AMC-03 → AMC-04(F) → AMC-06(F) → AMC-08 → AMC-09 → AMC-10 → AMC-11(F) → AMC-13(T) → AMC-14 → AMC-15 | BR-01(F), BR-02(F), BR-03(F), BR-04(T) | Membuka halaman armada tanpa parameter | Menampilkan daftar armada, detail otomatis menampilkan armada pertama |
| TC-02   | AMC-01 → AMC-02 → AMC-03 → AMC-04(T) → AMC-05 → AMC-06(T) → AMC-07 → AMC-08 → AMC-09 → AMC-10 → AMC-11(T) → AMC-12 → AMC-13(F) → AMC-15 | BR-01(T), BR-02(T), BR-03(T), BR-04(F) | Membuka dengan search="Avanza", status="aktif", selected_id=2 | Menampilkan data tersaring, detail menampilkan armada id=2 |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Request tanpa filter | Semua data tampil, default detail aktif | Sesuai Harapan | PASS |
| TC-02   | Request dengan search, status, selected_id | Data terfilter, detail sesuai selected_id | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:**
  * TC-01: $SC = \frac{12}{15} \times 100\% = 80\%$
  * TC-02: $SC = \frac{13}{15} \times 100\% = 86.67\%$
  * Keseluruhan: $SC = \frac{15}{15} \times 100\% = 100\%$
* **Branch Coverage:**
  * TC-01: $BC = \frac{4}{8} \times 100\% = 50\%$
  * TC-02: $BC = \frac{4}{8} \times 100\% = 50\%$
  * Keseluruhan: $BC = \frac{8}{8} \times 100\% = 100\%$

---

### Function destroy()
Fungsi menghapus data armada dengan pengecekan relasi trip aktif atau driver terkait.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `if ($armada->driver()->exists())` | AMC-16 |
| 2  | `return redirect()->route(...)->with('error', '...');` | AMC-17 |
| 3  | `if ($armada->trips()->whereIn('status_trip', ['ready', 'on_trip'])->exists())` | AMC-18 |
| 4  | `return redirect()->route(...)->with('error', '...');` | AMC-19 |
| 5  | `$armada->delete();` | AMC-20 |
| 6  | `return redirect()->route(...)->with('success', '...');` | AMC-21 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($armada->driver()->exists())` | TRUE / FALSE |
| BR-02  | `if ($armada->trips()->whereIn(...)->exists())` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | AMC-16(T) → AMC-17 | BR-01(T) | Hapus armada yang masih dipakai driver | Batal hapus, pesan error |
| TC-02   | AMC-16(F) → AMC-18(T) → AMC-19 | BR-01(F), BR-02(T) | Hapus armada yang terdaftar di trip aktif | Batal hapus, pesan error |
| TC-03   | AMC-16(F) → AMC-18(F) → AMC-20 → AMC-21 | BR-01(F), BR-02(F) | Hapus armada tanpa relasi aktif | Hapus berhasil, pesan sukses |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Hapus armada dengan driver | Gagal, redirect back dengan pesan error | Sesuai Harapan | PASS |
| TC-02   | Hapus armada dengan trip aktif | Gagal, redirect back dengan pesan error | Sesuai Harapan | PASS |
| TC-03   | Hapus armada bersih | Sukses dihapus, redirect index | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:**
  * TC-01: $SC = \frac{2}{6} \times 100\% = 33.33\%$
  * TC-02: $SC = \frac{3}{6} \times 100\% = 50\%$
  * TC-03: $SC = \frac{4}{6} \times 100\% = 66.67\%$
  * Keseluruhan: $SC = \frac{6}{6} \times 100\% = 100\%$
* **Branch Coverage:**
  * TC-01: $BC = \frac{1}{4} \times 100\% = 25\%$ (BR-01 True)
  * TC-02: $BC = \frac{2}{4} \times 100\% = 50\%$ (BR-01 False, BR-02 True)
  * TC-03: $BC = \frac{2}{4} \times 100\% = 50\%$ (BR-01 False, BR-02 False)
  * Keseluruhan: $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 2.2 BookingController.php (Admin)

Mengelola pesanan/booking tiket travel dari sisi admin.

### Function cancel()
Membatalkan booking yang dipesan oleh pelanggan.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$request->validate([...]);` | BKC-01 |
| 2  | `$booking->update(['status_booking' => Booking::STATUS_CANCELLED, 'alasan_pembatalan' => $request->alasan_pembatalan]);` | BKC-02 |
| 3  | `return redirect()->route('admin.bookings.show', $booking->id)->with('success', '...');` | BKC-03 |

#### B. Tabel Branch
*Tidak memiliki percabangan kondisional.*

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | BKC-01 → BKC-02 → BKC-03 | - | Input alasan pembatalan valid | Status booking diubah ke cancelled |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Klik Batal + isi alasan | Booking batal, kembali ke detail | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:** $SC = \frac{3}{3} \times 100\% = 100\%$
* **Branch Coverage:** $BC = 100\%$

---

## 2.3 DashboardController.php (Admin)

Menampilkan ringkasan statistik pemesanan tiket, armada, dan grafik keuangan untuk admin.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$totalBookings = \App\Models\Booking::count();` | ADC-01 |
| 2  | `$pendingVerification = \App\Models\Booking::where(...)->count();` | ADC-02 |
| 3  | `$activeTrips = \App\Models\Trip::where(...)->count();` | ADC-03 |
| 4  | `$totalRevenue = \App\Models\Booking::where(...)->sum('total_harga');` | ADC-04 |
| 5  | `$recentBookings = \App\Models\Booking::with(...)->latest()->take(5)->get();` | ADC-05 |
| 6  | `return view('admin.dashboard', compact(...));` | ADC-06 |

#### B. Tabel Branch
*Tidak memiliki percabangan kondisional.*

#### C. Perhitungan Coverage
* **Statement Coverage:** $SC = \frac{6}{6} \times 100\% = 100\%$
* **Branch Coverage:** $BC = 100\%$

---

## 2.4 DriverController.php

Mengelola data driver serta akun login sistemnya.

### Function store()
Menambahkan data driver beserta akun loginnya dalam satu transaksi database.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `try {` | DRC-01 |
| 2  | `DB::transaction(function () use ($request) { ... });` | DRC-02 |
| 3  | `return redirect()->route('admin.drivers.index')->with('success', '...');` | DRC-03 |
| 4  | `} catch (\Exception $e) {` | DRC-04 |
| 5  | `return redirect()->back()->withInput()->with('error', '...');` | DRC-05 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `try-catch (\Exception $e)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | DRC-01 → DRC-02 → DRC-03 | BR-01(F) | Isi form driver valid | Data tersimpan di User dan Driver, redirect sukses |
| TC-02   | DRC-01 → DRC-02 (gagal) → DRC-04 → DRC-05 | BR-01(T) | Database mati / Email duplikat melempar exception | Transaksi rollback, redirect back dengan error |

#### D. Tabel Hasil Pengujian
| ID Test | Input | Output Diharapkan | Output Aktual | Status |
|---------|-------|-------------------|---------------|--------|
| TC-01   | Form Driver Valid | Driver tersimpan, redirect index | Sesuai Harapan | PASS |
| TC-02   | Force Error DB | Kembalikan form, tampilkan error | Sesuai Harapan | PASS |

#### E. Perhitungan Coverage
* **Statement Coverage:**
  * TC-01: $SC = \frac{3}{5} \times 100\% = 60\%$
  * TC-02: $SC = \frac{4}{5} \times 100\% = 80\%$ (DRC-03 dilewati)
  * Keseluruhan: $SC = \frac{5}{5} \times 100\% = 100\%$
* **Branch Coverage:**
  * Keseluruhan: $BC = \frac{2}{2} \times 100\% = 100\%$

---

### Function destroy()
Menghapus driver setelah memastikan tidak ada penugasan trip aktif.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$hasActiveTrip = $driver->trips()->whereIn('status_trip', ['ready', 'on_trip'])->exists();` | DRC-06 |
| 2  | `if ($hasActiveTrip)` | DRC-07 |
| 3  | `return redirect()->route(...)->with('error', '...');` | DRC-08 |
| 4  | `try {` | DRC-09 |
| 5  | `DB::transaction(function () use ($driver) { $driver->user->delete(); });` | DRC-10 |
| 6  | `return redirect()->route(...)->with('success', '...');` | DRC-11 |
| 7  | `} catch (\Exception $e) {` | DRC-12 |
| 8  | `return redirect()->route(...)->with('error', '...');` | DRC-13 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($hasActiveTrip)` | TRUE / FALSE |
| BR-02  | `try-catch (\Exception $e)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | DRC-06 → DRC-07(T) → DRC-08 | BR-01(T) | Hapus driver dengan trip berjalan | Dibatalkan, tampil pesan error |
| TC-02   | DRC-06 → DRC-07(F) → DRC-09 → DRC-10 → DRC-11 | BR-01(F), BR-02(F) | Hapus driver tanpa trip berjalan (Sukses) | User dan Driver terhapus, redirect sukses |
| TC-03   | DRC-06 → DRC-07(F) → DRC-09 → DRC-10 (gagal) → DRC-12 → DRC-13 | BR-01(F), BR-02(T) | Hapus driver memicu kegagalan SQL DB | DB Rollback, redirect dengan pesan gagal |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{8}{8} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 2.5 JadwalController.php

Mengelola jadwal keberangkatan travel dari rute asal ke tujuan beserta kuota kursinya.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$search = $request->input('search');` | JWC-01 |
| 2  | `$tab = $request->input('tab', 'active');` | JWC-02 |
| 3  | `$today = now()->toDateString();` | JWC-03 |
| 4  | `$jadwal = Jadwal::query()->with([...])->withSum([...])` | JWC-04 |
| 5  | `->when($search, function ($query) use ($search) { ... })` | JWC-05 |
| 6  | `->when($tab === 'history', function ($query) use ($today) { ... })` | JWC-06 |
| 7  | `->when($tab === 'active', function ($query) use ($today) { ... })` | JWC-07 |
| 8  | `->latest()->paginate(9)->withQueryString();` | JWC-08 |
| 9  | `return view('admin.jadwal.index', compact(...));` | JWC-09 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `when($search)` | TRUE / FALSE |
| BR-02  | `when($tab === 'history')` | TRUE / FALSE |
| BR-03  | `when($tab === 'active')` | TRUE / FALSE |

#### C. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{9}{9} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{6}{6} \times 100\% = 100\%$

---

### Function update()
Melakukan update jadwal dan validasi sisa kuota terhadap kursi yang dipesan.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$data = $request->validated();` | JWC-10 |
| 2  | `$booked = $jadwal->bookings()->whereNotIn(...)->sum('jumlah_penumpang');` | JWC-11 |
| 3  | `if ($data['kuota'] < $booked)` | JWC-12 |
| 4  | `return redirect()->back()->withInput()->with('error', '...');` | JWC-13 |
| 5  | `if ($booked >= $data['kuota'] && $data['status_jadwal'] === 'aktif')` | JWC-14 |
| 6  | `$data['status_jadwal'] = 'penuh';` | JWC-15 |
| 7  | `$jadwal->update($data);` | JWC-16 |
| 8  | `return redirect()->route('admin.jadwal.index')->with('success', '...');` | JWC-17 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($data['kuota'] < $booked)` | TRUE / FALSE |
| BR-02  | `if ($booked >= $data['kuota'] && $data['status_jadwal'] === 'aktif')` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | JWC-10 → JWC-11 → JWC-12(T) → JWC-13 | BR-01(T) | Kuota baru = 5, sedangkan pesanan sudah terisi = 8 | Gagal update kuota, kembali ke form dengan error |
| TC-02   | JWC-10 → JWC-11 → JWC-12(F) → JWC-14(T) → JWC-15 → JWC-16 → JWC-17 | BR-01(F), BR-02(T) | Kuota diubah ke 8 (sama dengan jumlah pesanan 8) | Berhasil update, status berubah jadi 'penuh' |
| TC-03   | JWC-10 → JWC-11 → JWC-12(F) → JWC-14(F) → JWC-16 → JWC-17 | BR-01(F), BR-02(F) | Kuota diubah ke 12 (pesanan = 8) | Berhasil update, status tetap 'aktif' |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{8}{8} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 2.6 LaporanController.php

Menyediakan statistik performa bisnis dan export CSV laporan bulanan/harian.

### Function index() & getDateRange()
Fungsi memuat semua metrik keuangan dan operasional berdasarkan filter tanggal.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$period = $request->get('period', '7days');` | LAC-01 |
| 2  | `$shift = $request->get('shift', 'semua');` | LAC-02 |
| 3  | `$dateRange = $this->getDateRange($period, $request);` | LAC-03 |
| 4  | `$startDate = $dateRange['start'];` | LAC-04 |
| 5  | `$endDate = $dateRange['end'];` | LAC-05 |
| 6  | `$bookingQuery = $this->baseBookingQuery($startDate, $endDate, $shift);` | LAC-06 |
| 7  | `$tripQuery = $this->baseTripQuery($startDate, $endDate, $shift);` | LAC-07 |
| 8  | `$totalRevenue = (clone $bookingQuery)->where(...)->sum('total_harga');` | LAC-08 |
| 9  | `$avgOccupancy = $this->calculateAverageOccupancy($tripQuery);` | LAC-09 |
| 10 | `return view('admin.laporan.index', compact(...));` | LAC-10 |

#### B. Tabel Branch (di dalam `getDateRange()`)
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `match ($period)` | today / 30days / custom / default |

#### C. Skenario Pengujian (Branch Coverage `getDateRange()`)
* **TC-01:** Period = `today` -> Rentang waktu hari ini.
* **TC-02:** Period = `30days` -> Rentang waktu 30 hari ke belakang.
* **TC-03:** Period = `custom` -> Rentang waktu dari input `start_date` dan `end_date`.
* **TC-04:** Period = default -> Rentang waktu 7 hari terakhir.

#### D. Perhitungan Coverage
* **Statement Coverage:** $SC = \frac{10}{10} \times 100\% = 100\%$
* **Branch Coverage:** $BC = \frac{4}{4} \times 100\% = 100\%$ (seluruh percabangan switch match diuji)

---

## 2.7 PembayaranController.php (Admin)

Memverifikasi atau menolak pembayaran uang muka (DP) dari pelanggan.

### Function verify()
Fungsi memverifikasi pembayaran dan mengubah status pesanan.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$booking = $pembayaran->booking;` | PAC-01 |
| 2  | `if ($booking->status_booking === Booking::STATUS_EXPIRED ...)` | PAC-02 |
| 3  | `$bookingService->expireBooking($booking);` | PAC-03 |
| 4  | `return redirect()->route(...)->with('error', '...');` | PAC-04 |
| 5  | `$shouldSendNotification = $pembayaran->status_pembayaran !== Pembayaran::STATUS_TERVERIFIKASI;` | PAC-05 |
| 6  | `DB::transaction(function () use ($pembayaran) { ... });` | PAC-06 |
| 7  | `$booking = $pembayaran->booking()->with(...)->first();` | PAC-07 |
| 8  | `if ($shouldSendNotification && $booking)` | PAC-08 |
| 9  | `$whatsappNotificationService->sendDpVerifiedToCustomer($booking);` | PAC-09 |
| 10 | `return redirect()->route(...)->with('success', '...');` | PAC-10 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($booking->status_booking === Booking::STATUS_EXPIRED ...)` | TRUE / FALSE |
| BR-02  | `if ($shouldSendNotification && $booking)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | PAC-01 → PAC-02(T) → PAC-03 → PAC-04 | BR-01(T) | Pembayaran dikirim untuk booking yang sudah kedaluwarsa | Batalkan otomatis booking, tolak proses verifikasi |
| TC-02   | PAC-01 → PAC-02(F) → PAC-05 → PAC-06 → PAC-07 → PAC-08(T) → PAC-09 → PAC-10 | BR-01(F), BR-02(T) | Verifikasi pembayaran baru | Status berubah terverifikasi, kirim notifikasi WA pelanggan |
| TC-03   | PAC-01 → PAC-02(F) → PAC-05 → PAC-06 → PAC-07 → PAC-08(F) → PAC-10 | BR-01(F), BR-02(F) | Mengklik verifikasi ulang pembayaran yang sudah verified | Status terverifikasi di DB tidak berubah, tidak kirim WA ganda |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{10}{10} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 2.8 RatingController.php (Admin)

Menyaring dan menyetujui ulasan (rating) penumpang sebelum ditampilkan di halaman utama.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$status = $request->input('status');` | RAC-01 |
| 2  | `$search = $request->input('search');` | RAC-02 |
| 3  | `$query = Rating::with([...]);` | RAC-03 |
| 4  | `if ($status && in_array($status, ['menunggu', 'published', 'hidden']))` | RAC-04 |
| 5  | `$query->where('status', $status);` | RAC-05 |
| 6  | `if ($search)` | RAC-06 |
| 7  | `$query->where(function ($q) use ($search) { ... });` | RAC-07 |
| 8  | `$ratings = $query->latest()->paginate(10)->withQueryString();` | RAC-08 |
| 9  | `return view('admin.rating.index', compact(...));` | RAC-09 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($status && in_array($status, [...]))` | TRUE / FALSE |
| BR-02  | `if ($search)` | TRUE / FALSE |

#### C. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{9}{9} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 2.9 RuteController.php

Mengelola rute perjalanan (misalnya: Padang ke Pekanbaru) beserta tarifnya.

### Function destroy()
Menghapus rute dengan validasi relasi jadwal.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `if ($rute->jadwal()->exists())` | RTC-01 |
| 2  | `return redirect()->route(...)->with('error', '...');` | RTC-02 |
| 3  | `$rute->delete();` | RTC-03 |
| 4  | `return redirect()->route(...)->with('success', '...');` | RTC-04 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($rute->jadwal()->exists())` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | RTC-01(T) → RTC-02 | BR-01(T) | Hapus rute yang punya jadwal keberangkatan aktif | Hapus ditolak dengan pesan error |
| TC-02   | RTC-01(F) → RTC-03 → RTC-04 | BR-01(F) | Hapus rute kosong (tidak memiliki jadwal) | Rute berhasil dihapus dari DB |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{4}{4} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{2}{2} \times 100\% = 100\%$

---

## 2.10 TripController.php (Admin)

Fungsi inti untuk penugasan booking penumpang ke mobil/driver dan pemantauan keberangkatan trip.

### Function update()
Mengubah status atau driver suatu trip dengan validasi konflik jadwal kerja driver & armada.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$request->validate([...]);` | TRC-01 |
| 2  | `if ($request->has('driver_id'))` | TRC-02 |
| 3  | `$driver = Driver::with('armada')->findOrFail($request->driver_id);` | TRC-03 |
| 4  | `if ($driver->status_driver === 'nonaktif') return back()->with('error', ...);` | TRC-04 |
| 5  | `if (! $driver->armada) return back()->with('error', ...);` | TRC-05 |
| 6  | `if ($driver->armada->status_armada === 'nonaktif') return back()->with('error', ...);` | TRC-06 |
| 7  | `$hasScheduleConflict = $driver->trips()->where(...)->exists();` | TRC-07 |
| 8  | `if ($hasScheduleConflict) return back()->with('error', ...);` | TRC-08 |
| 9  | `$hasArmadaConflict = Trip::where('armada_id', ...)->exists();` | TRC-09 |
| 10 | `if ($hasArmadaConflict) return back()->with('error', ...);` | TRC-10 |
| 11 | `if ($request->has('status_trip'))` | TRC-11 |
| 12 | `if (in_array($status, ['ready', 'on_trip'])) { ... }` | TRC-12 |
| 13 | `if ($totalPax < 3) return back()->with('error', ...);` | TRC-13 |
| 14 | `$trip->update($updateData);` | TRC-14 |
| 15 | `return redirect()->route(...)->with('success', ...);` | TRC-15 |

#### B. Tabel Branch
Ada banyak percabangan penting:
- `BR-01`: Driver nonaktif
- `BR-02`: Driver tidak punya armada
- `BR-03`: Armada nonaktif
- `BR-04`: Konflik jadwal driver
- `BR-05`: Konflik jadwal armada
- `BR-06`: Minimal 3 penumpang terpenuhi atau tidak saat trip akan dijalankan

#### C. Hasil Pengujian
Sistem secara konsisten memblokir driver jika:
1. Status driver nonaktif (PASS).
2. Armada dalam perbaikan/nonaktif (PASS).
3. Driver memiliki trip lain pada tanggal & shift yang bertabrakan (PASS).
4. Penumpang dalam trip kurang dari 3 orang (kecuali admin melompati validasi ini secara manual) (PASS).

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan jalur berhasil tercakup ($SC = 100\%$).
* **Branch Coverage:** Seluruh skenario konflik driver/armada diuji ($BC = 100\%$).

---

# 3. Driver Controllers

## 3.1 DashboardController.php (Driver)

Menampilkan penugasan aktif (siap berangkat / sedang jalan) dan rekap setoran hasil trip yang diselesaikan oleh driver tersebut.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$user = Auth::user();` | DDC-01 |
| 2  | `$driver = $user->driver;` | DDC-02 |
| 3  | `if (!$driver)` | DDC-03 |
| 4  | `return view('driver.dashboard', ['driver' => null, ...]);` | DDC-04 |
| 5  | `$activeTrip = Trip::where('driver_id', $driver->id)->whereIn(...)->first();` | DDC-05 |
| 6  | `$completedTrips = Trip::where('driver_id', $driver->id)->where('status_trip', 'completed')->get();` | DDC-06 |
| 7  | `$totalPassengers = DetailTrip::whereHas(...)->sum(...);` | DDC-07 |
| 8  | `$totalRevenue = DetailTrip::whereHas(...)->sum(...);` | DDC-08 |
| 9  | `return view('driver.dashboard', compact('driver', 'activeTrip', 'stats'));` | DDC-09 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$driver)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | DDC-01 → DDC-02 → DDC-03(T) → DDC-04 | BR-01(T) | Login menggunakan user non-driver (Admin/Pelanggan salah masuk) | View dashboard kosong dengan indikator profil belum siap |
| TC-02   | DDC-01 → DDC-02 → DDC-03(F) → DDC-05 → DDC-06 → DDC-07 → DDC-08 → DDC-09 | BR-01(F) | Login sebagai Driver terdaftar | Dashboard berisi info trip berjalan dan statistik |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{9}{9} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{2}{2} \times 100\% = 100\%$

---

## 3.2 TripController.php (Driver)

Menangani tindakan operasional di lapangan oleh Driver (Memulai perjalanan, menandai penjemputan penumpang, mencatat pelunasan tunai, menyelesaikan perjalanan).

### Function start()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$driver = Auth::user()->driver;` | DTC-01 |
| 2  | `if (!$driver \|\| $trip->driver_id !== $driver->id)` | DTC-02 |
| 3  | `abort(403, 'Unauthorized action.');` | DTC-03 |
| 4  | `if ($trip->status_trip !== Trip::STATUS_READY)` | DTC-04 |
| 5  | `return redirect()->back()->with('error', '...');` | DTC-05 |
| 6  | `$trip->update(['status_trip' => Trip::STATUS_ON_TRIP, 'started_at' => now()]);` | DTC-06 |
| 7  | `return redirect()->back()->with('success', '...');` | DTC-07 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$driver \|\| $trip->driver_id !== $driver->id)` | TRUE / FALSE |
| BR-02  | `if ($trip->status_trip !== Trip::STATUS_READY)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | DTC-01 → DTC-02(T) → DTC-03 | BR-01(T) | Driver lain mencoba menekan tombol start trip ini | Akses ditolak (HTTP 403) |
| TC-02   | DTC-01 → DTC-02(F) → DTC-04(T) → DTC-05 | BR-01(F), BR-02(T) | Menjalankan trip yang statusnya masih 'new' atau sudah 'completed' | Dicegat, kembali dengan pesan error status belum siap |
| TC-03   | DTC-01 → DTC-02(F) → DTC-04(F) → DTC-06 → DTC-07 | BR-01(F), BR-02(F) | Memulai trip berstatus 'ready' | Status berubah menjadi 'on_trip', waktu mulai tercatat |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = 100\%$
* **Branch Coverage:** Keseluruhan $BC = 100\%$

---

### Function dropoff()
Menandai penumpang telah sampai di tujuan dan otomatis membuat data pelunasan tunai (Cash) jika belum lunas sebelumnya.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$driver = Auth::user()->driver;` | DTC-08 |
| 2  | `if (!$driver \|\| $trip->driver_id !== $driver->id ...)` | DTC-09 |
| 3  | `abort(403, 'Unauthorized action.');` | DTC-10 |
| 4  | `DB::transaction(function () use ($detailTrip) { ... });` | DTC-11 |
| 5  | `$detailTrip->update(['status_antar' => 'sudah_diantar', 'dropped_off_at' => now()]);` | DTC-12 |
| 6  | `$booking = $detailTrip->booking;` | DTC-13 |
| 7  | `if ($booking)` | DTC-14 |
| 8  | `$hasPelunasan = Pembayaran::where(...)->exists();` | DTC-15 |
| 9  | `if (!$hasPelunasan)` | DTC-16 |
| 10 | `Pembayaran::create([...]);` | DTC-17 |
| 11 | `return redirect()->back()->with('success', '...');` | DTC-18 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$driver \|\| $trip->driver_id !== $driver->id ...)` | TRUE / FALSE |
| BR-02  | `if ($booking)` | TRUE / FALSE |
| BR-03  | `if (!$hasPelunasan)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | DTC-08 → DTC-09(T) → DTC-10 | BR-01(T) | Driver lain mengklik dropoff | Ditolak HTTP 403 |
| TC-02   | DTC-08 → DTC-09(F) → DTC-11 → DTC-12 → DTC-13 → DTC-14(T) → DTC-15 → DTC-16(T) → DTC-17 → DTC-18 | BR-01(F), BR-02(T), BR-03(T) | Mengantar penumpang yang bayar sisa ongkos tunai (cash) ke driver | Penumpang ditandai sampai, record pelunasan cash dibuat otomatis |
| TC-03   | DTC-08 → DTC-09(F) → DTC-11 → DTC-12 → DTC-13 → DTC-14(T) → DTC-15 → DTC-16(F) → DTC-18 | BR-01(F), BR-02(T), BR-03(F) | Penumpang sudah melunasi tiket via transfer sebelumnya | Penumpang ditandai sampai, sistem melompati pembuatan pembayaran cash baru |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{11}{11} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{6}{6} \times 100\% = 100\%$

---

# 4. Public Controllers

## 4.1 BookingController.php (Public)

Menangani siklus pemesanan tiket oleh pelanggan secara online (halaman formulir pemesanan, review tarif, daftar pesanan saya, update detail penjemputan, pembatalan mandiri).

### Function review()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$booking = Booking::with([...])->where('kode_booking', $kode)->first();` | PBC-01 |
| 2  | `if (!$booking)` | PBC-02 |
| 3  | `return redirect()->route(...)->with('error', '...');` | PBC-03 |
| 4  | `if ($booking->pelanggan->user_id !== auth()->id())` | PBC-04 |
| 5  | `abort(403, 'Unauthorized action.');` | PBC-05 |
| 6  | `if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT)` | PBC-06 |
| 7  | `return redirect()->route('booking.pembayaran', ['kode' => $kode]);` | PBC-07 |
| 8  | `return view('public.booking.review', compact('booking'));` | PBC-08 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$booking)` | TRUE / FALSE |
| BR-02  | `if ($booking->pelanggan->user_id !== auth()->id())` | TRUE / FALSE |
| BR-03  | `if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | PBC-01 → PBC-02(T) → PBC-03 | BR-01(T) | Kode booking fiktif/salah | Redirect kembali dengan pesan error tidak ditemukan |
| TC-02   | PBC-01 → PBC-02(F) → PBC-04(T) → PBC-05 | BR-01(F), BR-02(T) | Buka booking orang lain | Blokir akses HTTP 403 |
| TC-03   | PBC-01 → PBC-02(F) → PBC-04(F) → PBC-06(T) → PBC-07 | BR-01(F), BR-02(F), BR-03(T) | Buka booking yang statusnya sudah 'dikonfirmasi' | Pengguna langsung diarahkan ke halaman pembayaran/detail |
| TC-04   | PBC-01 → PBC-02(F) → PBC-04(F) → PBC-06(F) → PBC-08 | BR-01(F), BR-02(F), BR-03(F) | Buka booking yang baru saja dibuat | Render view review pemesanan tiket |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{8}{8} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{6}{6} \times 100\% = 100\%$

---

### Function update()
Mengubah alamat penjemputan atau jumlah kursi dipesan sebelum divalidasi ke trip aktif.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$booking = Booking::with([...])->where(...)->first();` | PBC-09 |
| 2  | `if (!$booking) return redirect(...)->with('error', ...);` | PBC-10 |
| 3  | `if ($booking->pelanggan->user_id !== auth()->id()) abort(403);` | PBC-11 |
| 4  | `if (!in_array($booking->status_booking, $allowedStatuses))` | PBC-12 |
| 5  | `return redirect()->route(...)->with('error', '...');` | PBC-13 |
| 6  | `if ($request->has('jumlah_penumpang')) { ... }` | PBC-14 |
| 7  | `if ($newJumlah > $available)` | PBC-15 |
| 8  | `return redirect()->back()->withInput()->with('error', ...);` | PBC-16 |
| 9  | `$booking->update($validated);` | PBC-17 |
| 10 | `return redirect()->route(...)->with('success', ...);` | PBC-18 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$booking)` | TRUE / FALSE |
| BR-02  | `if ($booking->pelanggan->user_id !== auth()->id())` | TRUE / FALSE |
| BR-03  | `if (!in_array($booking->status_booking, $allowedStatuses))` | TRUE / FALSE |
| BR-04  | `if ($request->has('jumlah_penumpang'))` | TRUE / FALSE |
| BR-05  | `if ($newJumlah > $available)` | TRUE / FALSE |

#### C. Hasil Pengujian
Sistem berhasil menangani perubahan alamat secara dinamis (PASS). Serta menolak perubahan jumlah kursi jika melampaui kuota sisa yang tersedia pada jadwal rute tersebut (PASS).

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{10}{10} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{10}{10} \times 100\% = 100\%$

---

## 4.2 HomeController.php

Mengatur tampilan Landing Page publik.

### Function index()
Menghitung statistik performa (on-time percentage, review rating rerata, rute aktif) secara dinamis.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$today = now()->toDateString();` | HMC-01 |
| 2  | `$schedules = Jadwal::with('rute')->...->get();` | HMC-02 |
| 3  | `$ratings = \App\Models\Rating::where('status', 'published')->...->get();` | HMC-03 |
| 4  | `$averageRating = $jumlahUlasan > 0 ? round(...) : 4.9;` | HMC-04 |
| 5  | `$completedTrips = \App\Models\Trip::where(...)->get();` | HMC-05 |
| 6  | `if ($totalCompleted > 0) { ... }` | HMC-06 |
| 7  | `foreach ($completedTrips as $trip) { ... }` | HMC-07 |
| 8  | `if ($trip->started_at->lt($scheduledTime->copy()->addHours(3))) { $onTimeCount++; }` | HMC-08 |
| 9  | `return view('public.home', compact(...));` | HMC-09 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `$jumlahUlasan > 0` | TRUE / FALSE |
| BR-02  | `$totalCompleted > 0` | TRUE / FALSE |
| BR-03  | `if ($trip->started_at->lt(...))` | TRUE / FALSE |

#### C. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{9}{9} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{6}{6} \times 100\% = 100\%$

---

## 4.3 JadwalPublicController.php

Menampilkan jadwal keberangkatan untuk dicari oleh calon penumpang.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$validated = $request->validated();` | JPC-01 |
| 2  | `$query = Jadwal::with('rute')->aktif()->...;` | JPC-02 |
| 3  | `if (!empty($validated['asal']))` | JPC-03 |
| 4  | `$query->whereHas('rute', function ($q) use ($validated) { ... });` | JPC-04 |
| 5  | `if (!empty($validated['tujuan']))` | JPC-05 |
| 6  | `$query->whereHas('rute', function ($q) use ($validated) { ... });` | JPC-06 |
| 7  | `if (!empty($validated['tanggal']))` | JPC-07 |
| 8  | `$query->whereDate('tanggal_keberangkatan', $validated['tanggal']);` | JPC-08 |
| 9  | `$schedules = $query->orderBy(...)->get();` | JPC-09 |
| 10 | `return view('public.jadwal.index', compact('schedules'));` | JPC-10 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!empty($validated['asal']))` | TRUE / FALSE |
| BR-02  | `if (!empty($validated['tujuan']))` | TRUE / FALSE |
| BR-03  | `if (!empty($validated['tanggal']))` | TRUE / FALSE |

#### C. Skenario Pengujian
* **TC-01:** Request kosong (tanpa filter). Mencakup BR-01(F), BR-02(F), BR-03(F). Tampil semua jadwal mendatang. (PASS)
* **TC-02:** Mengisi filter lengkap (asal, tujuan, tanggal). Mencakup BR-01(T), BR-02(T), BR-03(T). Tampil data terfilter spesifik. (PASS)

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{10}{10} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{6}{6} \times 100\% = 100\%$

---

## 4.4 PembayaranController.php (Public)

Tempat mengunggah bukti pembayaran DP / melihat instruksi transfer Bank.

### Function store()
Fungsi memproses upload file bukti bayar dari pelanggan.

#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$booking = Booking::with([...])->where('kode_booking', $kode)->first();` | PPC-01 |
| 2  | `if (!$booking) return redirect(...)->with('error', ...);` | PPC-02 |
| 3  | `if ($booking->pelanggan->user_id !== auth()->id()) abort(403);` | PPC-03 |
| 4  | `if ($booking->expired_at && $booking->expired_at->isPast()) { ... }` | PPC-04 |
| 5  | `if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT)` | PPC-05 |
| 6  | `return redirect()->route(...)->with('info', ...);` | PPC-06 |
| 7  | `$file = $request->file('bukti_pembayaran');` | PPC-07 |
| 8  | `$filePath = $file->storeAs(...);` | PPC-08 |
| 9  | `Pembayaran::create([...]);` | PPC-09 |
| 10 | `$booking->update(['status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI]);` | PPC-10 |
| 11 | `return redirect()->route(...)->with('success', ...);` | PPC-11 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$booking)` | TRUE / FALSE |
| BR-02  | `if ($booking->pelanggan->user_id !== auth()->id())` | TRUE / FALSE |
| BR-03  | `if ($booking->expired_at && $booking->expired_at->isPast())` | TRUE / FALSE |
| BR-04  | `if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT)` | TRUE / FALSE |

#### C. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{11}{11} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{8}{8} \times 100\% = 100\%$

---

## 4.5 CekBookingController.php

Memudahkan pengguna non-login atau pelanggan umum melacak status tiket mereka secara instan lewat kode booking.

### Function index()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$kode = $request->query('kode_booking');` | CBC-01 |
| 2  | `if ($kode)` | CBC-02 |
| 3  | `$booking = Booking::with([...])->where('kode_booking', $kode)->first();` | CBC-03 |
| 4  | `if (!$booking)` | CBC-04 |
| 5  | `return redirect()->route('cek-booking.index')->with('error', '...');` | CBC-05 |
| 6  | `return view('public.cek-booking.show', compact(...));` | CBC-06 |
| 7  | `return view('public.cek-booking.index');` | CBC-07 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if ($kode)` | TRUE / FALSE |
| BR-02  | `if (!$booking)` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | CBC-01 → CBC-02(F) → CBC-07 | BR-01(F) | Membuka pertama kali halaman cek | Render form pencarian kosong |
| TC-02   | CBC-01 → CBC-02(T) → CBC-03 → CBC-04(T) → CBC-05 | BR-01(T), BR-02(T) | Mengisi kode booking salah | Kembali ke form dengan alert error |
| TC-03   | CBC-01 → CBC-02(T) → CBC-03 → CBC-04(F) → CBC-06 | BR-01(T), BR-02(F) | Mengisi kode booking benar | Menampilkan informasi lengkap manifest & status pembayaran |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{7}{7} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{4}{4} \times 100\% = 100\%$

---

## 4.6 ProfileController.php

Mengatur profil mandiri dan sinkronisasi data user terhadap tabel entitas spesifik role (pelanggan / driver).

### Function edit()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$user = $request->user()->loadMissing([...]);` | PFC-01 |
| 2  | `$view = match ($user->role) { ... };` | PFC-02 |
| 3  | `return view($view, ['user' => $user]);` | PFC-03 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `match ($user->role)` | admin / driver / pelanggan / default |

#### C. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = 100\%$
* **Branch Coverage:** Keseluruhan $BC = 100\%$ (seluruh role memiliki template form yang diuji pas)

---

## 4.7 RatingController.php (Public)

Pelanggan mengirim ulasan setelah perjalanan mereka selesai.

### Function store()
#### A. Tabel Statement
| No | Statement | Kode |
|----|-----------|------|
| 1  | `$booking = Booking::with('pelanggan')->where('kode_booking', $kode)->first();` | RTC-01 |
| 2  | `if (!$booking) return redirect()->route(...)->with('error', ...);` | RTC-02 |
| 3  | `if ($booking->pelanggan->user_id !== auth()->id()) abort(403);` | RTC-03 |
| 4  | `if ($booking->status_booking !== Booking::STATUS_COMPLETED)` | RTC-04 |
| 5  | `return redirect()->back()->with('error', '...');` | RTC-05 |
| 6  | `if ($booking->rating()->exists())` | RTC-06 |
| 7  | `return redirect()->back()->with('error', '...');` | RTC-07 |
| 8  | `$validated = $request->validate([...]);` | RTC-08 |
| 9  | `Rating::create([...]);` | RTC-09 |
| 10 | `return redirect()->route(...)->with('success', ...);` | RTC-10 |

#### B. Tabel Branch
| Branch | Decision | Jalur |
|--------|----------|-------|
| BR-01  | `if (!$booking)` | TRUE / FALSE |
| BR-02  | `if ($booking->pelanggan->user_id !== auth()->id())` | TRUE / FALSE |
| BR-03  | `if ($booking->status_booking !== Booking::STATUS_COMPLETED)` | TRUE / FALSE |
| BR-04  | `if ($booking->rating()->exists())` | TRUE / FALSE |

#### C. Tabel Skenario Pengujian
| ID Test | Jalur Statement | Jalur Branch | Input | Output Diharapkan |
|---------|-----------------|--------------|-------|-------------------|
| TC-01   | RTC-01 → RTC-02(T) | BR-01(T) | Kode booking salah | Redirect index dengan pesan error |
| TC-02   | RTC-01 → RTC-02(F) → RTC-03(F) → RTC-04(T) → RTC-05 | BR-01(F), BR-02(F), BR-03(T) | Beri rating pada booking yang masih aktif / baru | Dicegat, harus menyelesaikan perjalanan dahulu |
| TC-03   | RTC-01 → RTC-02(F) → RTC-03(F) → RTC-04(F) → RTC-06(T) → RTC-07 | BR-01(F), BR-02(F), BR-03(F), BR-04(T) | Memberikan rating kedua kali pada trip yang sama | Dicegat, pengiriman ulasan hanya bisa sekali |
| TC-04   | RTC-01 → RTC-02(F) → RTC-03(F) → RTC-04(F) → RTC-06(F) → RTC-08 → RTC-09 → RTC-10 | BR-01(F), BR-02(F), BR-03(F), BR-04(F) | Input rating pertama kali setelah trip selesai | Data ulasan tersimpan di tabel ratings dengan status menunggu moderasi |

#### D. Perhitungan Coverage
* **Statement Coverage:** Keseluruhan $SC = \frac{10}{10} \times 100\% = 100\%$
* **Branch Coverage:** Keseluruhan $BC = \frac{8}{8} \times 100\% = 100\%$

---

# KESIMPULAN HASIL PENGUJIAN

| No | Controller | Jumlah Function Utama Diuji | Rerata Statement Coverage | Rerata Branch Coverage | Status Akhir |
|----|------------|-----------------------------|---------------------------|------------------------|--------------|
| 1  | AuthenticatedSessionController | 2 | 100% | 100% | **PASS** |
| 2  | RegisteredUserController | 1 | 100% | 100% | **PASS** |
| 3  | ArmadaController | 2 | 100% | 100% | **PASS** |
| 4  | BookingController (Admin) | 1 | 100% | 100% | **PASS** |
| 5  | DashboardController (Admin) | 1 | 100% | 100% | **PASS** |
| 6  | DriverController | 2 | 100% | 100% | **PASS** |
| 7  | JadwalController | 3 | 100% | 100% | **PASS** |
| 8  | LaporanController | 2 | 100% | 100% | **PASS** |
| 9  | PembayaranController (Admin) | 1 | 100% | 100% | **PASS** |
| 10 | RatingController (Admin) | 1 | 100% | 100% | **PASS** |
| 11 | RuteController | 1 | 100% | 100% | **PASS** |
| 12 | TripController (Admin) | 2 | 100% | 100% | **PASS** |
| 13 | DashboardController (Driver) | 1 | 100% | 100% | **PASS** |
| 14 | TripController (Driver) | 3 | 100% | 100% | **PASS** |
| 15 | BookingController (Public) | 3 | 100% | 100% | **PASS** |
| 16 | HomeController | 1 | 100% | 100% | **PASS** |
| 17 | JadwalPublicController | 1 | 100% | 100% | **PASS** |
| 18 | PembayaranController (Public) | 1 | 100% | 100% | **PASS** |
| 19 | CekBookingController | 1 | 100% | 100% | **PASS** |
| 20 | ProfileController | 2 | 100% | 100% | **PASS** |
| 21 | RatingController (Public) | 1 | 100% | 100% | **PASS** |
