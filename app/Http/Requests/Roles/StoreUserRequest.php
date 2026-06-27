<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
            'role' => ['required', 'string', Rule::in(['superadmin', 'teacher', 'student', 'technician'])],
            'nuptk' => ['nullable', 'string', 'max:255', Rule::requiredIf($this->input('role') === 'teacher')],
            'nik' => ['nullable', 'string', 'max:255', Rule::requiredIf($this->input('role') === 'student')],
            'id_technician' => ['nullable', 'string', 'max:255', Rule::requiredIf($this->input('role') === 'technician')],
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
            // Pesan error dasar
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'role.required' => 'Peran (Role) wajib dipilih.',
            
            // Pesan error kondisional berdasarkan role
            'nuptk.required' => 'Kolom NUPTK wajib diisi untuk peran Guru.',
            'nik.required' => 'Kolom NIK wajib diisi untuk peran Siswa.',
            'id_technician.required' => 'Kolom ID Teknisi wajib diisi untuk peran Teknisi.',
        ];
    }
}