<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArmadaRequest extends FormRequest
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
        $armadaId = $this->route('armada') instanceof \App\Models\Armada ? $this->route('armada')->id : $this->route('armada');
        
        return [
            'nama_mobil' => ['required', 'string', 'max:100'],
            'nomor_plat' => ['required', 'string', 'max:20', 'unique:armada,nomor_plat,' . $armadaId],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:20'],
            'status_armada' => ['required', 'in:aktif,nonaktif'],
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
            'nama_mobil.required' => 'Nama mobil wajib diisi.',
            'nama_mobil.string' => 'Nama mobil harus berupa teks.',
            'nama_mobil.max' => 'Nama mobil tidak boleh lebih dari 100 karakter.',
            'nomor_plat.required' => 'Nomor plat wajib diisi.',
            'nomor_plat.string' => 'Nomor plat harus berupa teks.',
            'nomor_plat.max' => 'Nomor plat tidak boleh lebih dari 20 karakter.',
            'nomor_plat.unique' => 'Nomor plat sudah terdaftar.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1.',
            'kapasitas.max' => 'Kapasitas maksimal 20.',
            'status_armada.required' => 'Status armada wajib diisi.',
            'status_armada.in' => 'Status armada tidak valid.',
        ];
    }
}
