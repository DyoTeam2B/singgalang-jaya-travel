# Statement Coverage – Rayfo (Admin Core)

> **PIC**: Rayfo  
> **Module**: Admin Dashboard, Manajemen Rute, Manajemen Jadwal  
> **Tanggal**: 10 Juni 2026

---

## Daftar 10 Fitur yang Diuji

| No | Fitur | File Sumber | Metode |
|----|-------|-------------|--------|
| 1 | Pencarian Rute (filter search) | `RuteController.php` | `index()` |
| 2 | Simpan Rute Baru (validasi) | `StoreRuteRequest.php` + `RuteController.php` | `store()` |
| 3 | Update Rute (validasi) | `UpdateRuteRequest.php` + `RuteController.php` | `update()` |
| 4 | Hapus Rute (cek jadwal terkait) | `RuteController.php` | `destroy()` |
| 5 | Pencarian Jadwal (filter search) | `JadwalController.php` | `index()` |
| 6 | Simpan Jadwal Baru (validasi) | `StoreJadwalRequest.php` + `JadwalController.php` | `store()` |
| 7 | Update Jadwal (cek kuota vs booked) | `JadwalController.php` | `update()` |
| 8 | Hapus Jadwal (cek booking aktif) | `JadwalController.php` | `destroy()` |
| 9 | Toggle Status Jadwal (aktif/nonaktif/penuh) | `JadwalController.php` | `toggleStatus()` |
| 10 | Display Status Jadwal di View (dynamic badge) | `jadwal/index.blade.php` | Blade `@php` + `@if` |

---

## Fitur 1: Pencarian Rute (Filter Search)

### Tabel 1 – Identifikasi Statement

**File**: [RuteController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/RuteController.php#L16-L30)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `$search = $request->input('search');` | S1.1 |
| 2 | `$rute = Rute::query()` | S1.2 |
| 3 | `->when($search, function ($query) use ($search) {` | S1.3 |
| 4 | `$query->where('asal', 'like', "%{$search}%")->orWhere('tujuan', 'like', "%{$search}%");` | S1.4 |
| 5 | `->latest()->paginate(10)->withQueryString();` | S1.5 |
| 6 | `return view('admin.rute.index', compact('rute', 'search'));` | S1.6 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Akses halaman tanpa keyword search | `search = null` | Semua rute tampil (paginated), S1.4 tidak dieksekusi |
| TC-02 | Search dengan keyword valid | `search = "Padang"` | Hanya rute dengan asal/tujuan mengandung "Padang" tampil |
| TC-03 | Search dengan keyword tidak ditemukan | `search = "XYZ999"` | Tabel kosong, pesan "Tidak ada rute perjalanan ditemukan" |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | `search = null` | Semua rute tampil | ✅ Semua rute tampil |
| TC-02 | `search = "Padang"` | Rute dengan "Padang" tampil | ✅ Rute dengan "Padang" tampil |
| TC-03 | `search = "XYZ999"` | Tabel kosong | ✅ Tabel kosong, pesan muncul |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S1.1 | ✅ | ✅ | ✅ |
| S1.2 | ✅ | ✅ | ✅ |
| S1.3 | ✅ | ✅ | ✅ |
| S1.4 | ❌ | ✅ | ✅ |
| S1.5 | ✅ | ✅ | ✅ |
| S1.6 | ✅ | ✅ | ✅ |

> **Coverage**: 6/6 = **100%** (semua statement tereksekusi minimal 1×)

---

## Fitur 2: Simpan Rute Baru (Validasi)

### Tabel 1 – Identifikasi Statement

**File**: [StoreRuteRequest.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Requests/Admin/StoreRuteRequest.php#L22-L29) + [RuteController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/RuteController.php#L43-L50)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `return true;` (authorize) | S2.1 |
| 2 | `'asal' => ['required', 'string', 'max:255']` | S2.2 |
| 3 | `'tujuan' => ['required', 'string', 'max:255']` | S2.3 |
| 4 | `'tarif' => ['required', 'integer', 'min:0']` | S2.4 |
| 5 | `Rute::create($request->validated());` | S2.5 |
| 6 | `return redirect()->route('admin.rute.index')->with('success', ...);` | S2.6 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Simpan rute dengan data lengkap valid | `asal="Padang", tujuan="Jakarta", tarif=350000` | Redirect ke index + flash success |
| TC-02 | Simpan rute dengan field asal kosong | `asal="", tujuan="Jakarta", tarif=350000` | Validasi gagal, pesan "Kota asal wajib diisi." |
| TC-03 | Simpan rute dengan tarif negatif | `asal="Padang", tujuan="Jakarta", tarif=-5000` | Validasi gagal, pesan "Tarif tidak boleh kurang dari 0." |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | `asal="Padang", tujuan="Jakarta", tarif=350000` | Redirect + success | ✅ Redirect + flash "Rute baru berhasil ditambahkan." |
| TC-02 | `asal="", tujuan="Jakarta", tarif=350000` | Validasi error asal | ✅ Error "Kota asal wajib diisi." |
| TC-03 | `asal="Padang", tujuan="Jakarta", tarif=-5000` | Validasi error tarif | ✅ Error "Tarif tidak boleh kurang dari 0." |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S2.1 | ✅ | ✅ | ✅ |
| S2.2 | ✅ | ✅ | ✅ |
| S2.3 | ✅ | ✅ | ✅ |
| S2.4 | ✅ | ✅ | ✅ |
| S2.5 | ✅ | ❌ | ❌ |
| S2.6 | ✅ | ❌ | ❌ |

> **Coverage**: 6/6 = **100%**

---

## Fitur 3: Update Rute (Validasi)

### Tabel 1 – Identifikasi Statement

**File**: [UpdateRuteRequest.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Requests/Admin/UpdateRuteRequest.php#L22-L29) + [RuteController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/RuteController.php#L71-L78)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `return true;` (authorize) | S3.1 |
| 2 | `'asal' => ['required', 'string', 'max:255']` | S3.2 |
| 3 | `'tujuan' => ['required', 'string', 'max:255']` | S3.3 |
| 4 | `'tarif' => ['required', 'integer', 'min:0']` | S3.4 |
| 5 | `$rute->update($request->validated());` | S3.5 |
| 6 | `return redirect()->route('admin.rute.index')->with('success', ...);` | S3.6 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Update rute dengan data valid | `asal="Bukittinggi", tujuan="Medan", tarif=400000` | Redirect + flash success |
| TC-02 | Update rute dengan tujuan kosong | `asal="Bukittinggi", tujuan="", tarif=400000` | Validasi gagal, pesan "Kota tujuan wajib diisi." |
| TC-03 | Update rute dengan tarif bukan angka | `asal="Bukittinggi", tujuan="Medan", tarif="abc"` | Validasi gagal, pesan "Tarif harus berupa angka." |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | `asal="Bukittinggi", tujuan="Medan", tarif=400000` | Redirect + success | ✅ Redirect + flash "Data rute berhasil diperbarui." |
| TC-02 | `asal="Bukittinggi", tujuan="", tarif=400000` | Error tujuan | ✅ Error "Kota tujuan wajib diisi." |
| TC-03 | `asal="Bukittinggi", tujuan="Medan", tarif="abc"` | Error tarif | ✅ Error "Tarif harus berupa angka." |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S3.1 | ✅ | ✅ | ✅ |
| S3.2 | ✅ | ✅ | ✅ |
| S3.3 | ✅ | ✅ | ✅ |
| S3.4 | ✅ | ✅ | ✅ |
| S3.5 | ✅ | ❌ | ❌ |
| S3.6 | ✅ | ❌ | ❌ |

> **Coverage**: 6/6 = **100%**

---

## Fitur 4: Hapus Rute (Cek Jadwal Terkait)

### Tabel 1 – Identifikasi Statement

**File**: [RuteController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/RuteController.php#L83-L97)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `if ($rute->jadwal()->exists())` | S4.1 |
| 2 | `return redirect()->route('admin.rute.index')->with('error', 'Rute tidak dapat dihapus...');` | S4.2 |
| 3 | `$rute->delete();` | S4.3 |
| 4 | `return redirect()->route('admin.rute.index')->with('success', 'Rute berhasil dihapus.');` | S4.4 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Hapus rute yang TIDAK punya jadwal | Rute tanpa relasi jadwal | Rute terhapus + flash success |
| TC-02 | Hapus rute yang PUNYA jadwal | Rute dengan 1+ jadwal | Redirect + flash error "Rute tidak dapat dihapus..." |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | Rute ID tanpa jadwal | Terhapus + success | ✅ Terhapus + flash "Rute berhasil dihapus." |
| TC-02 | Rute ID dengan jadwal | Error, tidak terhapus | ✅ Flash "Rute tidak dapat dihapus karena memiliki jadwal keberangkatan terkait." |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 |
|--------------|-------|-------|
| S4.1 | ✅ | ✅ |
| S4.2 | ❌ | ✅ |
| S4.3 | ✅ | ❌ |
| S4.4 | ✅ | ❌ |

> **Coverage**: 4/4 = **100%**

---

## Fitur 5: Pencarian Jadwal (Filter Search)

### Tabel 1 – Identifikasi Statement

**File**: [JadwalController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/JadwalController.php#L17-L38)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `$search = $request->input('search');` | S5.1 |
| 2 | `$jadwal = Jadwal::query()->with(['rute'])` | S5.2 |
| 3 | `->withSum(['bookings' => ...], 'jumlah_penumpang')` | S5.3 |
| 4 | `->when($search, function ($query) use ($search) {` | S5.4 |
| 5 | `$query->whereHas('rute', ...)->orWhere('tanggal_keberangkatan', ...)->orWhere('shift', ...);` | S5.5 |
| 6 | `->latest()->paginate(9)->withQueryString();` | S5.6 |
| 7 | `return view('admin.jadwal.index', compact('jadwal', 'search'));` | S5.7 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Akses halaman tanpa keyword | `search = null` | Semua jadwal tampil, S5.5 tidak dieksekusi |
| TC-02 | Search dengan nama rute | `search = "Padang"` | Jadwal dengan rute Padang tampil |
| TC-03 | Search dengan shift | `search = "pagi"` | Jadwal shift pagi tampil |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | `search = null` | Semua jadwal tampil | ✅ Semua jadwal tampil |
| TC-02 | `search = "Padang"` | Jadwal rute Padang | ✅ Jadwal rute Padang tampil |
| TC-03 | `search = "pagi"` | Jadwal shift pagi | ✅ Jadwal shift pagi tampil |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S5.1 | ✅ | ✅ | ✅ |
| S5.2 | ✅ | ✅ | ✅ |
| S5.3 | ✅ | ✅ | ✅ |
| S5.4 | ✅ | ✅ | ✅ |
| S5.5 | ❌ | ✅ | ✅ |
| S5.6 | ✅ | ✅ | ✅ |
| S5.7 | ✅ | ✅ | ✅ |

> **Coverage**: 7/7 = **100%**

---

## Fitur 6: Simpan Jadwal Baru (Validasi)

### Tabel 1 – Identifikasi Statement

**File**: [StoreJadwalRequest.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Requests/Admin/StoreJadwalRequest.php#L22-L32) + [JadwalController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/JadwalController.php#L52-L59)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `return true;` (authorize) | S6.1 |
| 2 | `'rute_id' => ['required', 'exists:rute,id']` | S6.2 |
| 3 | `'tanggal_keberangkatan' => ['required', 'date']` | S6.3 |
| 4 | `'shift' => ['required', 'in:pagi,malam']` | S6.4 |
| 5 | `'jam_berangkat' => ['required', 'date_format:H:i']` | S6.5 |
| 6 | `'kuota' => ['required', 'integer', 'min:1']` | S6.6 |
| 7 | `'status_jadwal' => ['required', 'in:aktif,nonaktif,penuh']` | S6.7 |
| 8 | `Jadwal::create($request->validated());` | S6.8 |
| 9 | `return redirect()->route('admin.jadwal.index')->with('success', ...);` | S6.9 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Simpan jadwal data lengkap valid | `rute_id=1, tanggal=2026-06-15, shift=pagi, jam=08:00, kuota=15, status=aktif` | Redirect + flash success |
| TC-02 | Simpan jadwal tanpa rute_id | `rute_id=null, ...rest valid` | Error "Rute perjalanan wajib dipilih." |
| TC-03 | Simpan jadwal shift tidak valid | `shift="siang", ...rest valid` | Error "Shift harus berupa Pagi atau Malam." |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | Data lengkap valid | Redirect + success | ✅ Redirect + flash "Jadwal keberangkatan baru berhasil ditambahkan." |
| TC-02 | `rute_id = null` | Error rute | ✅ Error "Rute perjalanan wajib dipilih." |
| TC-03 | `shift = "siang"` | Error shift | ✅ Error "Shift harus berupa Pagi atau Malam." |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S6.1 | ✅ | ✅ | ✅ |
| S6.2 | ✅ | ✅ | ✅ |
| S6.3 | ✅ | ✅ | ✅ |
| S6.4 | ✅ | ✅ | ✅ |
| S6.5 | ✅ | ✅ | ✅ |
| S6.6 | ✅ | ✅ | ✅ |
| S6.7 | ✅ | ✅ | ✅ |
| S6.8 | ✅ | ❌ | ❌ |
| S6.9 | ✅ | ❌ | ❌ |

> **Coverage**: 9/9 = **100%**

---

## Fitur 7: Update Jadwal (Cek Kuota vs Booked)

### Tabel 1 – Identifikasi Statement

**File**: [JadwalController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/JadwalController.php#L81-L108)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `$data = $request->validated();` | S7.1 |
| 2 | `$booked = $jadwal->bookings()->where('status_booking', '!=', 'dibatalkan')->sum('jumlah_penumpang');` | S7.2 |
| 3 | `if ($data['kuota'] < $booked)` | S7.3 |
| 4 | `return redirect()->back()->withInput()->with('error', 'Kapasitas tidak boleh lebih kecil...');` | S7.4 |
| 5 | `if ($booked >= $data['kuota'] && $data['status_jadwal'] === 'aktif')` | S7.5 |
| 6 | `$data['status_jadwal'] = 'penuh';` | S7.6 |
| 7 | `$jadwal->update($data);` | S7.7 |
| 8 | `return redirect()->route('admin.jadwal.index')->with('success', ...);` | S7.8 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Update kuota valid (kuota > booked) | `kuota=20, status=aktif` (booked=5) | Redirect + success, status tetap aktif |
| TC-02 | Update kuota < booked (ditolak) | `kuota=3, status=aktif` (booked=5) | Redirect back + error "Kapasitas tidak boleh lebih kecil..." |
| TC-03 | Update kuota = booked + status aktif (auto penuh) | `kuota=5, status=aktif` (booked=5) | Status otomatis jadi "penuh" + redirect success |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | `kuota=20` (booked=5) | Success, status aktif | ✅ Redirect + flash success, status aktif |
| TC-02 | `kuota=3` (booked=5) | Error kapasitas | ✅ Flash "Kapasitas tidak boleh lebih kecil dari jumlah kursi yang sudah dipesan (5 kursi)." |
| TC-03 | `kuota=5` (booked=5) | Auto penuh + success | ✅ Status berubah ke "penuh" + flash success |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S7.1 | ✅ | ✅ | ✅ |
| S7.2 | ✅ | ✅ | ✅ |
| S7.3 | ✅ | ✅ | ✅ |
| S7.4 | ❌ | ✅ | ❌ |
| S7.5 | ✅ | ❌ | ✅ |
| S7.6 | ❌ | ❌ | ✅ |
| S7.7 | ✅ | ❌ | ✅ |
| S7.8 | ✅ | ❌ | ✅ |

> **Coverage**: 8/8 = **100%**

---

## Fitur 8: Hapus Jadwal (Cek Booking Aktif)

### Tabel 1 – Identifikasi Statement

**File**: [JadwalController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/JadwalController.php#L113-L127)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `if ($jadwal->bookings()->where('status_booking', '!=', 'dibatalkan')->exists())` | S8.1 |
| 2 | `return redirect()->route('admin.jadwal.index')->with('error', 'Jadwal tidak dapat dihapus...');` | S8.2 |
| 3 | `$jadwal->delete();` | S8.3 |
| 4 | `return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');` | S8.4 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Hapus jadwal tanpa booking aktif | Jadwal tanpa booking / semua dibatalkan | Jadwal terhapus + flash success |
| TC-02 | Hapus jadwal dengan booking aktif | Jadwal memiliki 1+ booking aktif | Redirect + flash error |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | Jadwal tanpa booking aktif | Terhapus + success | ✅ Terhapus + flash "Jadwal keberangkatan berhasil dihapus." |
| TC-02 | Jadwal dengan booking aktif | Error, tidak terhapus | ✅ Flash "Jadwal tidak dapat dihapus karena memiliki booking aktif." |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 |
|--------------|-------|-------|
| S8.1 | ✅ | ✅ |
| S8.2 | ❌ | ✅ |
| S8.3 | ✅ | ❌ |
| S8.4 | ✅ | ❌ |

> **Coverage**: 4/4 = **100%**

---

## Fitur 9: Toggle Status Jadwal (Aktif ↔ Nonaktif/Penuh)

### Tabel 1 – Identifikasi Statement

**File**: [JadwalController.php](file:///d:/pbl/singgalang-jaya-travel/app/Http/Controllers/Admin/JadwalController.php#L132-L152)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `$newStatus = $jadwal->status_jadwal === 'aktif' ? 'nonaktif' : 'aktif';` | S9.1 |
| 2 | `if ($newStatus === 'aktif')` | S9.2 |
| 3 | `$booked = $jadwal->bookings()->where(...)->sum('jumlah_penumpang');` | S9.3 |
| 4 | `if ($booked >= $jadwal->kuota)` | S9.4 |
| 5 | `$newStatus = 'penuh';` | S9.5 |
| 6 | `$jadwal->update(['status_jadwal' => $newStatus]);` | S9.6 |
| 7 | `return redirect()->route('admin.jadwal.index')->with('success', ...);` | S9.7 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Toggle dari aktif ke nonaktif | Jadwal status `aktif` | Status berubah jadi `nonaktif` |
| TC-02 | Toggle dari nonaktif ke aktif (kuota masih tersedia) | Jadwal status `nonaktif`, booked < kuota | Status berubah jadi `aktif` |
| TC-03 | Toggle dari nonaktif ke aktif (kuota penuh) | Jadwal status `nonaktif`, booked >= kuota | Status berubah jadi `penuh` (bukan aktif) |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | Status `aktif` | → `nonaktif` | ✅ Status "Nonaktif" + flash success |
| TC-02 | Status `nonaktif`, booked=3, kuota=15 | → `aktif` | ✅ Status "Aktif" + flash success |
| TC-03 | Status `nonaktif`, booked=15, kuota=15 | → `penuh` | ✅ Status "Penuh" + flash success |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S9.1 | ✅ | ✅ | ✅ |
| S9.2 | ✅ | ✅ | ✅ |
| S9.3 | ❌ | ✅ | ✅ |
| S9.4 | ❌ | ✅ | ✅ |
| S9.5 | ❌ | ❌ | ✅ |
| S9.6 | ✅ | ✅ | ✅ |
| S9.7 | ✅ | ✅ | ✅ |

> **Coverage**: 7/7 = **100%**

---

## Fitur 10: Display Status Jadwal di View (Dynamic Badge + Icon)

### Tabel 1 – Identifikasi Statement

**File**: [jadwal/index.blade.php](file:///d:/pbl/singgalang-jaya-travel/resources/views/admin/jadwal/index.blade.php#L49-L96)

| NO | Statement (Kode Program) | Statement ID |
|----|--------------------------|--------------|
| 1 | `$bookedSum = $item->bookings_sum_jumlah_penumpang ?? 0;` | S10.1 |
| 2 | `$displayStatus = $item->status_jadwal;` | S10.2 |
| 3 | `if ($item->status_jadwal === 'aktif' && $bookedSum >= $item->kuota)` | S10.3 |
| 4 | `$displayStatus = 'penuh';` | S10.4 |
| 5 | `@if(strtolower($item->shift) === 'pagi')` — tampilkan icon matahari | S10.5 |
| 6 | `@else` — tampilkan icon bulan | S10.6 |
| 7 | `{{ $bookedSum >= $item->kuota ? 'text-amber-600' : 'text-slate-800' }}` | S10.7 |
| 8 | `@if($item->status_jadwal === 'nonaktif')` — tombol "Aktifkan" | S10.8 |
| 9 | `@else` — tombol "Nonaktifkan" | S10.9 |

### Tabel 2 – Test Case

| ID TEST | Skenario Pengujian | Input | Output Diharapkan |
|---------|--------------------|-------|-------------------|
| TC-01 | Jadwal aktif, shift pagi, kuota tersedia | `status=aktif, shift=pagi, booked=3, kuota=15` | Badge "Aktif", icon matahari, warna normal |
| TC-02 | Jadwal aktif, shift malam, kuota penuh | `status=aktif, shift=malam, booked=15, kuota=15` | Badge "Penuh" (dynamic), icon bulan, warna amber |
| TC-03 | Jadwal nonaktif, shift pagi | `status=nonaktif, shift=pagi` | Badge "Nonaktif", icon matahari, tombol "Aktifkan" |

### Tabel 3 – Hasil Pengujian

| ID TEST | Input | Output Diharapkan | Output Aktual |
|---------|-------|-------------------|---------------|
| TC-01 | aktif, pagi, 3/15 | Badge Aktif, 🌞, warna normal | ✅ Badge Aktif hijau, icon matahari, text-slate-800 |
| TC-02 | aktif, malam, 15/15 | Badge Penuh, 🌙, warna amber | ✅ Badge Penuh kuning, icon bulan, text-amber-600 |
| TC-03 | nonaktif, pagi | Badge Nonaktif, 🌞, tombol Aktifkan | ✅ Badge Nonaktif merah, icon matahari, tombol hijau "Aktifkan" |

### Tabel 4 – Coverage Matrix

| Statement ID | TC-01 | TC-02 | TC-03 |
|--------------|-------|-------|-------|
| S10.1 | ✅ | ✅ | ✅ |
| S10.2 | ✅ | ✅ | ✅ |
| S10.3 | ✅ | ✅ | ✅ |
| S10.4 | ❌ | ✅ | ❌ |
| S10.5 | ✅ | ❌ | ✅ |
| S10.6 | ❌ | ✅ | ❌ |
| S10.7 | ✅ | ✅ | ✅ |
| S10.8 | ❌ | ❌ | ✅ |
| S10.9 | ✅ | ✅ | ❌ |

> **Coverage**: 9/9 = **100%**

---

## Ringkasan Coverage Keseluruhan

| No | Fitur | Total Statement | Statement Covered | Coverage |
|----|-------|-----------------|-------------------|----------|
| 1 | Pencarian Rute | 6 | 6 | **100%** |
| 2 | Simpan Rute Baru | 6 | 6 | **100%** |
| 3 | Update Rute | 6 | 6 | **100%** |
| 4 | Hapus Rute (cek jadwal) | 4 | 4 | **100%** |
| 5 | Pencarian Jadwal | 7 | 7 | **100%** |
| 6 | Simpan Jadwal Baru | 9 | 9 | **100%** |
| 7 | Update Jadwal (kuota) | 8 | 8 | **100%** |
| 8 | Hapus Jadwal (booking) | 4 | 4 | **100%** |
| 9 | Toggle Status Jadwal | 7 | 7 | **100%** |
| 10 | Display Status View | 9 | 9 | **100%** |
| | **TOTAL** | **66** | **66** | **100%** |
