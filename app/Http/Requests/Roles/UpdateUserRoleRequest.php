<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRoleRequest extends FormRequest
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
            'role' => ['required', 'string', 'in:superadmin,teacher,student,technician'],
            'id_technician' => ['nullable', 'string', 'max:255', 'required_if:role,technician'],
            'nuptk' => ['nullable', 'string', 'max:255', 'required_if:role,teacher'],
            'nik' => ['nullable', 'string', 'max:255', 'required_if:role,student'],
            // Validasi date_of_birth telah dihapus agar selaras dengan controller dan view
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