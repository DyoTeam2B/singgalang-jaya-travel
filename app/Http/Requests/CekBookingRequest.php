<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekBookingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'kode_booking' => ['required', 'string', 'exists:bookings,kode_booking'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'kode_booking.required' => 'Kode booking wajib diisi.',
            'kode_booking.exists' => 'Kode booking tidak ditemukan dalam sistem.',
        ];
    }
}
