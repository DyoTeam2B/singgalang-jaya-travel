<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchJadwalRequest extends FormRequest
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
            'asal' => ['nullable', 'string', 'max:100'],
            'tujuan' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['nullable', 'date', 'after_or_equal:today'],
            'select_mode' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'asal.string' => 'Kota asal harus berupa teks.',
            'asal.max' => 'Kota asal maksimal 100 karakter.',
            'tujuan.string' => 'Kota tujuan harus berupa teks.',
            'tujuan.max' => 'Kota tujuan maksimal 100 karakter.',
            'tanggal.date' => 'Format tanggal perjalanan tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal perjalanan tidak boleh di masa lalu.',
        ];
    }
}
