<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuteRequest extends FormRequest
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
            'asal' => ['required', 'string', 'max:255'],
            'tujuan' => ['required', 'string', 'max:255'],
            'tarif' => ['required', 'integer', 'min:0'],
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
            'asal.required' => 'Kota asal wajib diisi.',
            'asal.string' => 'Kota asal harus berupa teks.',
            'asal.max' => 'Kota asal tidak boleh lebih dari 255 karakter.',
            'tujuan.required' => 'Kota tujuan wajib diisi.',
            'tujuan.string' => 'Kota tujuan harus berupa teks.',
            'tujuan.max' => 'Kota tujuan tidak boleh lebih dari 255 karakter.',
            'tarif.required' => 'Tarif rute wajib diisi.',
            'tarif.integer' => 'Tarif harus berupa angka.',
            'tarif.min' => 'Tarif tidak boleh kurang dari 0.',
        ];
    }
}
