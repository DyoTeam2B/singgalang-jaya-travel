<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $phoneRules = ['string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'];
        $requiresPhone = in_array($this->user()->role, ['pelanggan', 'driver'], true)
            && ($this->has('no_hp') || $this->user()->pelanggan || $this->user()->driver);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'no_hp' => [$requiresPhone ? 'required' : 'nullable', ...$phoneRules],
        ];
    }

    /**
     * Get custom validation messages for profile fields.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.max' => 'Nomor telepon maksimal 20 karakter.',
            'no_hp.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda plus, tanda minus, dan tanda kurung.',
        ];
    }
}
