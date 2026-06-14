# Laravel Standard - Singgalang Jaya Travel

Dokumen ini merangkum standar coding Laravel untuk project Singgalang Jaya Travel. Basis utamanya adalah praktik dari repo `alexeymezenin/laravel-best-practices`, lalu disesuaikan dengan struktur, bahasa, dan kebutuhan aplikasi travel ini.

Sumber utama:
- https://github.com/alexeymezenin/laravel-best-practices
- https://github.com/alexeymezenin/laravel-best-practices/blob/master/indonesia.md

## Tujuan

- Menjaga kode Laravel tetap mudah dibaca, dites, dan dikembangkan oleh tim.
- Mengurangi logic yang menumpuk di controller, route, dan Blade.
- Membuat pola implementasi fitur booking, jadwal, pembayaran, armada, driver, dan trip tetap konsisten.
- Menghindari bug umum seperti N+1 query, validasi tersebar, mass assignment tidak aman, dan pemakaian `.env` langsung di kode aplikasi.

## Prinsip Wajib

1. Satu class punya satu tanggung jawab utama.
2. Satu method mengerjakan satu hal yang jelas.
3. Controller dibuat tipis: terima request, validasi/otorisasi, panggil service/model, lalu return response.
4. Business logic dipindahkan ke service class, action class, atau model sesuai konteks.
5. Validasi form memakai Form Request.
6. Query database tidak boleh dijalankan di Blade.
7. Gunakan Eloquent, relationship, scope, collection, dan eager loading sebelum memilih raw SQL.
8. Gunakan fitur standar Laravel sebelum menambah package pihak ketiga.
9. Hindari duplikasi dengan scope, component, partial, constant, config, dan helper yang benar-benar diperlukan.
10. Ikuti konvensi penamaan Laravel dan aturan project ini.

## Bahasa Dan Penamaan Project

Ikuti aturan lokal project Singgalang Jaya Travel:

| Area | Standar |
|------|---------|
| Class, method, variable | English, `camelCase` untuk method/variable |
| Model | Singular, `PascalCase`, contoh `Booking`, `Jadwal`, `Trip`, `Armada` |
| Controller | Singular + `Controller`, contoh `BookingController`, `JadwalController`, `ArmadaController` |
| Form Request | Singular dan spesifik, contoh `StoreBookingRequest`, `UpdateJadwalRequest`, `StoreArmadaRequest` |
| Service | Singular dan spesifik, contoh `BookingService`, `PaymentVerificationService` |
| Tabel dan kolom operasional | Bahasa Indonesia sesuai ERD project |
| Kolom default Breeze/users | Biarkan English, contoh `name`, `email`, `password`, `role` |
| Route URI | Bahasa Indonesia untuk resource utama, contoh `/admin/jadwal`, `/admin/armada` |
| Route name | Dot notation, contoh `admin.jadwal.index`, `admin.armada.index`, `driver.trips.show` |
| View Blade | `kebab-case`, contoh `cek-booking.blade.php` |
| UI label dan pesan validasi | Bahasa Indonesia |
| Komentar kode | English, singkat, hanya jika membantu |
| Commit message | English |

## Controller

Controller tidak boleh menjadi tempat utama business logic.

Controller boleh:
- menerima Form Request;
- menjalankan authorization/policy;
- memanggil service, action, atau method model;
- memilih view atau redirect;
- mengirim response JSON jika endpoint memang API.

Controller tidak boleh:
- berisi proses panjang untuk booking, pembayaran, atau assign trip;
- menjalankan query kompleks berulang;
- memproses file upload secara detail;
- memuat perhitungan tarif, kuota, status, atau workflow operasional yang rumit;
- mengandung HTML, CSS, atau JavaScript.

Contoh pola yang disarankan:

```php
public function store(StoreBookingRequest $request, BookingService $bookingService)
{
    $booking = $bookingService->createBooking($request->validated());

    return redirect()
        ->route('booking.show', $booking)
        ->with('success', 'Booking berhasil dibuat.');
}
```

## Form Request Dan Validasi

Semua validasi input user wajib dipindahkan ke Form Request untuk fitur yang sudah permanen.

Gunakan Form Request untuk:
- booking pelanggan;
- upload bukti pembayaran;
- CRUD jadwal;
- CRUD armada;
- CRUD driver (dengan assign armada);
- assign penumpang ke trip;
- update status penjemputan/pengantaran.

Standar Form Request:
- method `rules()` berisi aturan validasi;
- method `messages()` boleh dipakai untuk pesan Bahasa Indonesia;
- method `authorize()` dipakai jika ada aturan role/policy;
- controller memakai `$request->validated()`, bukan `$request->all()`.

Contoh:

```php
class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'pelanggan';
    }

    public function rules(): array
    {
        return [
            'jadwal_id' => ['required', 'exists:jadwal,id'],
            'alamat_jemput' => ['required', 'string', 'max:500'],
            'alamat_tujuan' => ['required', 'string', 'max:500'],
            'jumlah_penumpang' => ['required', 'integer', 'min:1'],
            'latitude_jemput' => ['nullable', 'numeric'],
            'longitude_jemput' => ['nullable', 'numeric'],
            'latitude_tujuan' => ['nullable', 'numeric'],
            'longitude_tujuan' => ['nullable', 'numeric'],
        ];
    }
}
```

## Service Class

Gunakan service class saat proses bisnis memiliki beberapa langkah atau dipakai ulang.

Contoh kandidat service:
- `BookingService`: membuat booking, membuat kode booking, menghitung total (tarif rute × jumlah penumpang).
- `PaymentVerificationService`: validasi bukti DP dan update status pembayaran.
- `TripAssignmentService`: mengelompokkan booking ke trip dan menjaga kapasitas armada.
- `DriverTripService`: update status jemput, antar, konfirmasi pelunasan, dan selesai trip.
- `FonnteService`: kirim pesan WhatsApp via FonnteAPI (konfirmasi, notifikasi pembatalan).

Service tidak boleh bergantung pada request HTTP mentah. Kirim data bersih dari `$request->validated()`.

```php
class BookingService
{
    public function createBooking(array $data): Booking
    {
        return Booking::create([
            ...$data,
            'kode_booking' => $this->generateBookingCode(),
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);
    }
}
```

## Model, Eloquent, Dan Relationship

Gunakan model untuk logic yang dekat dengan data dan relationship.

Model boleh berisi:
- relationship Eloquent;
- local scope;
- accessor/mutator;
- casts;
- constant status/type;
- method kecil yang menjelaskan state model.

Contoh:

```php
class Booking extends Model
{
    public const STATUS_BOOKING_DIBUAT = 'booking_dibuat';
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    public const STATUS_ASSIGNED_TO_TRIP = 'assigned_to_trip';
    public const STATUS_ON_TRIP = 'on_trip';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'kode_booking',
        'pelanggan_id',
        'jadwal_id',
        'alamat_jemput',
        'latitude_jemput',
        'longitude_jemput',
        'alamat_tujuan',
        'latitude_tujuan',
        'longitude_tujuan',
        'jumlah_penumpang',
        'total_harga',
        'status_booking',
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status_booking', self::STATUS_DIKONFIRMASI);
    }

    public function isConfirmed(): bool
    {
        return $this->status_booking === self::STATUS_DIKONFIRMASI;
    }
}
```

## Query Database

Utamakan Eloquent dan relationship agar kode tetap ekspresif.

Standar query:
- gunakan eager loading dengan `with()` saat menampilkan relasi;
- gunakan `whereHas()` untuk filter berdasarkan relasi;
- gunakan local scope untuk filter yang sering dipakai;
- gunakan `chunk()`, `lazy()`, atau pagination untuk data besar;
- hindari raw SQL kecuali ada alasan performa yang jelas dan terdokumentasi.

Contoh menghindari N+1 query:

```php
$bookings = Booking::with(['jadwal.rute', 'pembayaran'])
    ->latest()
    ->paginate(20);
```

Jangan lakukan ini di Blade:

```blade
@foreach (Booking::all() as $booking)
    {{ $booking->jadwal->tanggal_berangkat }}
@endforeach
```

Blade hanya menerima data yang sudah disiapkan controller/service.

## Mass Assignment

Gunakan `$request->validated()` saat membuat atau memperbarui model.

Wajib:
- definisikan `$fillable` atau `$guarded` secara sadar di model;
- jangan pakai `$request->all()` untuk `create()` atau `update()`;
- jangan menerima status sensitif dari input publik tanpa validasi role.

Contoh:

```php
$booking = Booking::create($request->validated());
```

Jika ada field sistem, set di service:

```php
$booking = Booking::create([
    ...$data,
    'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
]);
```

## Blade, CSS, Dan JavaScript

Blade dipakai untuk render tampilan, bukan tempat logic berat.

Standar Blade:
- jangan query database di Blade;
- jangan menaruh business logic di Blade;
- gunakan component atau partial untuk UI yang berulang;
- gunakan directive Blade bawaan seperti `@auth`, `@guest`, `@can`, `@csrf`, `@method`;
- gunakan `@json()` saat mengirim data PHP ke JavaScript;
- hindari inline CSS/JS kecuali snippet kecil yang benar-benar lokal dan sementara.

Asset frontend:
- gunakan Vite untuk CSS/JS utama;
- gunakan TailwindCSS sesuai konfigurasi project;
- gunakan Alpine.js untuk interaksi ringan;
- jangan membuat HTML di class PHP.

## Config, Constant, Dan Language File

Jangan tulis magic string berulang di banyak tempat.

Gunakan:
- constant di model untuk status/type yang dekat dengan domain model;
- config file untuk nilai yang dapat berubah per environment;
- language file untuk teks yang reusable;
- helper hanya jika dipakai lintas modul dan benar-benar stabil.

Jangan akses `.env` langsung di controller, service, model, Blade, atau route.

Benar:

```php
// config/services.php
'maps' => [
    'key' => env('MAPS_API_KEY'),
],

// pemakaian
$apiKey = config('services.maps.key');
```

Salah:

```php
$apiKey = env('MAPS_API_KEY');
```

## Date, Time, Dan Format Tampilan

Simpan tanggal dalam format database standar dan gunakan cast Eloquent.

Wajib:
- kolom tanggal memakai tipe date/datetime/time yang sesuai;
- model memakai `$casts` untuk field tanggal;
- format tampilan dilakukan di Blade, accessor, presenter, atau helper view;
- jangan parse string tanggal manual berulang di controller/Blade.

Contoh:

```php
protected $casts = [
    'tanggal_berangkat' => 'date',
    'jam_berangkat' => 'datetime:H:i',
];
```

## Route

Route harus tipis dan tidak berisi logic bisnis.

Standar route:
- gunakan controller method, bukan closure panjang;
- kelompokkan route admin dan driver dengan prefix dan middleware;
- gunakan route name yang konsisten;
- jangan membuat query atau proses bisnis di file route;
- cek route name sebelum dipakai di Blade agar tidak memicu `Route Not Defined`.

Contoh:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('jadwal', Admin\JadwalController::class);
        Route::resource('armada', Admin\ArmadaController::class);
    });
```

## Dependency Injection Dan Service Container

Gunakan dependency injection untuk class yang punya dependency.

Disarankan:
- inject service ke controller method atau constructor;
- inject model/repository/service jika perlu;
- gunakan interface jika implementasi benar-benar bisa berganti;
- hindari `new SomeService()` tersebar di controller.

Contoh:

```php
public function verify(
    Pembayaran $pembayaran,
    PaymentVerificationService $paymentVerificationService
) {
    $paymentVerificationService->verify($pembayaran);

    return back()->with('success', 'Pembayaran berhasil diverifikasi.');
}
```

## Tools Standar Laravel

Utamakan fitur bawaan Laravel dan package yang sudah terpasang di project.

Gunakan:
- Eloquent untuk database;
- Migration untuk perubahan struktur database;
- Seeder dan Factory untuk data dummy;
- Form Request untuk validasi;
- Policy/Gate untuk authorization;
- Blade untuk template;
- Vite untuk asset;
- PHPUnit untuk testing;
- Queue/Scheduler bawaan Laravel jika nanti dibutuhkan.

Jangan tambah package baru jika fitur bawaan sudah cukup atau package belum disepakati tim.

### Package Tambahan (Disetujui)

| Package | Kegunaan | Status |
|---------|----------|--------|
| FonnteAPI (via HTTP client) | WhatsApp notification | ✅ Disetujui |

> FonnteAPI menggunakan Laravel HTTP client bawaan (`Http::post`). **Tidak perlu** install package composer tambahan.

## Komentar, Type Hint, Dan DocBlock

Nama method dan variable harus cukup jelas sehingga komentar panjang tidak diperlukan.

Standar:
- gunakan type hint parameter dan return type jika memungkinkan;
- hindari DocBlock yang hanya mengulang signature method;
- tambahkan komentar singkat hanya untuk keputusan bisnis atau bagian yang tidak langsung terlihat;
- komentar kode ditulis dalam English.

Contoh lebih baik:

```php
public function hasAvailableSeat(): bool
{
    return $this->bookings()->confirmed()->count() < $this->kapasitas;
}
```

## DRY Tanpa Abstraksi Berlebihan

Hindari duplikasi, tetapi jangan membuat abstraksi terlalu dini.

Gunakan:
- Blade component untuk elemen UI berulang;
- partial untuk bagian layout yang dipakai banyak halaman;
- local scope untuk filter query berulang;
- service untuk workflow domain berulang;
- constant untuk status/type yang stabil.

Jangan membuat helper global untuk logic yang hanya dipakai satu modul.

## Testing Minimum

Tambahkan test saat membuat logic penting atau memperbaiki bug.

Prioritas test:
- booking berhasil dibuat dengan data valid;
- booking gagal jika jadwal tidak tersedia;
- upload bukti pembayaran memvalidasi file dan booking;
- admin bisa verifikasi pembayaran;
- driver hanya bisa melihat trip miliknya;
- driver bisa konfirmasi pelunasan;
- route utama dapat diakses sesuai role;
- policy menolak akses yang salah.

Untuk fitur sederhana, minimal pastikan route, controller, dan view tidak error saat dibuka.

## Checklist Sebelum Menyelesaikan Fitur

- Controller tetap tipis.
- Validasi sudah di Form Request.
- Tidak ada query database di Blade.
- Relationship memakai eager loading saat menampilkan list.
- Tidak ada pemakaian `$request->all()` untuk mass assignment.
- Tidak ada akses `env()` langsung selain di file config.
- Status/type memakai constant atau enum yang konsisten.
- Route name yang dipakai di Blade benar-benar terdaftar.
- View dan component yang dipanggil benar-benar ada.
- Pesan validasi dan UI text memakai Bahasa Indonesia.
- Kode, variable, method, dan komentar mengikuti aturan bahasa project.
- Fitur sudah dicek manual atau lewat test sesuai risiko perubahan.

## Catatan Adaptasi Dari Sumber

Repo sumber memakai konvensi Laravel umum. Project ini tetap mengikuti Laravel best practices, tetapi beberapa hal disesuaikan:

- Nama tabel dan kolom operasional mengikuti ERD project dalam Bahasa Indonesia.
- Route URI untuk resource utama memakai Bahasa Indonesia.
- UI label, pesan validasi, dan teks tampilan memakai Bahasa Indonesia.
- Kode PHP tetap memakai English agar konsisten dengan ekosistem Laravel.
- Struktur admin, driver, dan public mengikuti dokumen context project yang sudah ada.
- Armada dikelola sebagai entitas terpisah dari driver (tabel `armada` sendiri).
