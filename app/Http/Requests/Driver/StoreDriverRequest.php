<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'nama_driver' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'no_hp' => ['required', 'string', 'max:20'],
            'armada_id' => ['required', 'exists:armada,id'],
            'status_driver' => ['required', 'in:aktif,nonaktif'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_driver.required' => 'Nama lengkap driver wajib diisi.',
            'nama_driver.string' => 'Nama lengkap driver harus berupa teks.',
            'nama_driver.max' => 'Nama lengkap driver tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email login wajib diisi.',
            'email.email' => 'Format email login tidak valid.',
            'email.max' => 'Email login tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email login sudah digunakan oleh pengguna lain.',
            'password.required' => 'Password login wajib diisi.',
            'password.min' => 'Password login minimal terdiri dari 8 karakter.',
            'no_hp.required' => 'Nomor HP driver wajib diisi.',
            'no_hp.max' => 'Nomor HP tidak boleh lebih dari 20 karakter.',
            'armada_id.required' => 'Armada wajib dipilih.',
            'armada_id.exists' => 'Armada yang dipilih tidak valid.',
            'status_driver.required' => 'Status driver wajib dipilih.',
            'status_driver.in' => 'Status driver tidak valid.',
        ];
    }
}
