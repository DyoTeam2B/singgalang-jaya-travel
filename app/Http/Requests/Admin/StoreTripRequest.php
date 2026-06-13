<?php

namespace App\Http\Requests\Admin;

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
            'jadwal_id' => [
                'required',
                'exists:jadwal,id',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Trip::where('jadwal_id', $value)
                        ->whereNotIn('status_trip', ['cancelled'])
                        ->exists();
                    if ($exists) {
                        $fail('Jadwal keberangkatan ini sudah memiliki trip aktif.');
                    }
                }
            ],
            'driver_id' => [
                'required',
                'exists:drivers,id',
                function ($attribute, $value, $fail) {
                    $jadwalId = $this->input('jadwal_id');
                    $jadwal = \App\Models\Jadwal::find($jadwalId);
                    if ($jadwal) {
                        $exists = \App\Models\Trip::where('driver_id', $value)
                            ->whereNotIn('status_trip', ['cancelled'])
                            ->whereHas('jadwal', function ($q) use ($jadwal) {
                                $q->where('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan->toDateString())
                                  ->where('shift', $jadwal->shift);
                            })
                            ->exists();
                        if ($exists) {
                            $fail('Driver ini sudah memiliki tugas trip aktif di hari dan shift yang sama.');
                        }
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
