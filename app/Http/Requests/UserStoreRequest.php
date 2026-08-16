<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'tipe' => 'nullable|in:admin,users,pegawai,helpdesk',
            'kategori_user' => 'nullable|required_if:tipe,users|in:umum,pemerintah',
            'status' => 'required|in:aktif,nonaktif',
            'tim_kerja_id' => [
                'nullable',
                'array',
            ],
            'tim_kerja_id.*' => [
                'exists:tb_tim_kerja,id_tim_kerja',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'tipe.required' => 'Tipe wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'tim_kerja_id.required' => 'Tim kerja wajib diisi.',
            'tim_kerja_id.exists' => 'Tim kerja tidak valid.',
        ];
    }
}
