<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- WAJIB ADA

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Menangkap ID user yang sedang di-edit dari URL route
        $userId = $this->route('id') ?? $this->route('hakakse') ?? $this->route('user');

        return [
            'name' => 'required|string|max:255',
            
            // PERBAIKAN EMAIL: Mengabaikan email milik user ini sendiri agar tidak dianggap duplikat
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($userId)
            ],
            
            // Password dibuat opsional saat edit (isi jika ingin mengganti saja)
            'password' => 'nullable|string|min:8', 
            
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
            // Pesan error dalam bahasa Indonesia agar konsisten
            'role.required' => 'Peran (Role) wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid dalam sistem.',
            'id_technician.required_if' => 'Kolom ID Teknisi wajib diisi jika peran diubah menjadi Teknisi.',
            'nuptk.required_if' => 'Kolom NUPTK wajib diisi jika peran diubah menjadi Guru.',
            'nik.required_if' => 'Kolom NIK wajib diisi jika peran diubah menjadi Siswa.',
        ];
    }
}