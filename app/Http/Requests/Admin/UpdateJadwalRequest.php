<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
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
            'rute_id' => ['required', 'exists:rute,id'],
            'tanggal_keberangkatan' => ['required', 'date'],
            'shift' => ['required', 'in:pagi,malam'],
            'jam_berangkat' => ['required', 'date_format:H:i'],
            'kuota' => ['required', 'integer', 'min:1'],
            'status_jadwal' => ['required', 'in:aktif,nonaktif,penuh'],
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
            'rute_id.required' => 'Rute perjalanan wajib dipilih.',
            'rute_id.exists' => 'Rute yang dipilih tidak valid.',
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan wajib diisi.',
            'tanggal_keberangkatan.date' => 'Format tanggal tidak valid.',
            'shift.required' => 'Shift keberangkatan wajib dipilih.',
            'shift.in' => 'Shift harus berupa Pagi atau Malam.',
            'jam_berangkat.required' => 'Jam berangkat wajib diisi.',
            'jam_berangkat.date_format' => 'Format jam berangkat harus HH:MM.',
            'kuota.required' => 'Kuota penumpang wajib diisi.',
            'kuota.integer' => 'Kuota harus berupa angka.',
            'kuota.min' => 'Kuota minimal 1 penumpang.',
            'status_jadwal.required' => 'Status jadwal wajib ditentukan.',
            'status_jadwal.in' => 'Status jadwal yang dipilih tidak valid.',
        ];
    }
}
