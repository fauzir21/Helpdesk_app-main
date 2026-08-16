<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimKerjaUpdateRequest extends FormRequest
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
        $id = $this->route('tim_kerja')->id_tim_kerja;

        return [
            'nama_timkerja' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tb_tim_kerja', 'nama_timkerja')->ignore($id, 'id_tim_kerja'),
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
            'nama_timkerja.max' => 'Nama tim kerja maksimal 100 karakter',
            'nama_timkerja.unique' => 'Nama tim kerja sudah terdaftar',
            'ketua_id.required' => 'Ketua tim wajib diisi',
            'ketua_id.exists' => 'Ketua tim tidak ditemukan',
            'anggota_ids.array' => 'Format anggota tim tidak valid',
            'anggota_ids.*.exists' => 'Salah satu anggota tim tidak ditemukan',
        ];
    }
}
