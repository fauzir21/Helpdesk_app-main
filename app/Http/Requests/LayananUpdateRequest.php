<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LayananUpdateRequest extends FormRequest
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
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'tim_kerja_id' => 'required|exists:tb_tim_kerja,id_tim_kerja',
            'durasi_hari' => 'required|integer|min:0',
            'user_category' => 'required|in:umum,pemerintah,semua',
            'input_tambahan' => 'nullable|array',
            'input_tambahan.*.label' => 'required|string',
            'input_tambahan.*.type' => 'required|string|in:text,number,date,textarea',
            'input_tambahan.*.required' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.max' => 'Nama layanan maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus Aktif atau Tidak Aktif.',
            'tim_kerja_id.required' => 'Tim kerja wajib diisi.',
            'tim_kerja_id.exists' => 'Tim kerja tidak ditemukan.',
        ];
    }
}
