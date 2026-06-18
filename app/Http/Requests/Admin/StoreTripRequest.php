<?php

namespace App\Http\Requests\Admin;

use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jadwal_id' => ['required', 'exists:jadwal,id'],
            'driver_id' => [
                'required',
                'exists:drivers,id',
                function ($attribute, $value, $fail) {
                    $jadwalId = $this->input('jadwal_id');
                    $jadwal = Jadwal::find($jadwalId);
                    $driver = Driver::with('armada')->find($value);

                    if (! $jadwal || ! $driver) {
                        return;
                    }

                    if ($driver->status_driver !== 'aktif') {
                        $fail('Driver ini tidak aktif.');
                        return;
                    }

                    if (! $driver->armada) {
                        $fail('Driver ini belum memiliki armada.');
                        return;
                    }

                    if ($driver->armada->status_armada !== 'aktif') {
                        $fail('Armada milik driver ini tidak aktif.');
                        return;
                    }

                    $driverBusy = Trip::where('driver_id', $value)
                        ->where('status_trip', '!=', Trip::STATUS_CANCELLED)
                        ->whereHas('jadwal', function ($q) use ($jadwal) {
                            $q->where('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan->toDateString())
                                ->where('shift', $jadwal->shift);
                        })
                        ->exists();

                    if ($driverBusy) {
                        $fail('Driver ini sudah memiliki tugas trip aktif di hari dan shift yang sama.');
                        return;
                    }

                    $armadaBusy = Trip::where('armada_id', $driver->armada_id)
                        ->where('status_trip', '!=', Trip::STATUS_CANCELLED)
                        ->whereHas('jadwal', function ($q) use ($jadwal) {
                            $q->where('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan->toDateString())
                                ->where('shift', $jadwal->shift);
                        })
                        ->exists();

                    if ($armadaBusy) {
                        $fail('Armada milik driver ini sudah digunakan pada hari dan shift yang sama.');
                    }
                }
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal keberangkatan wajib dipilih.',
            'jadwal_id.exists' => 'Jadwal keberangkatan yang dipilih tidak valid.',
            'driver_id.required' => 'Driver wajib dipilih.',
            'driver_id.exists' => 'Driver yang dipilih tidak valid.',
        ];
    }
}
