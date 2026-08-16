<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TimKerjaStoreRequest extends FormRequest
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
            'nama_timkerja' => [
                'required',
                'string',
                'max:100',
                'unique:tb_tim_kerja,nama_timkerja',
            ],
            'ketua_id' => [
                'required',
                'exists:users,id',
            ],
            'anggota_ids' => [
                'nullable',
                'array',
            ],
            'anggota_ids.*' => [
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_timkerja.required' => 'Nama tim kerja wajib diisi',
            'nama_timkerja.string' => 'Nama tim kerja harus berupa string',
            'nama_timkerja.max' => 'Nama tim kerja maksimal 100 karakter',
            'nama_timkerja.unique' => 'Nama tim kerja sudah terdaftar',
            'ketua_id.required' => 'Ketua tim wajib diisi',
            'ketua_id.exists' => 'Ketua tim tidak ditemukan',
            'anggota_ids.array' => 'Format anggota tim tidak valid',
            'anggota_ids.*.exists' => 'Salah satu anggota tim tidak ditemukan',
        ];
    }
}
