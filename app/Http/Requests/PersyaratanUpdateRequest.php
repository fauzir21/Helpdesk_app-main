<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PersyaratanUpdateRequest extends FormRequest
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
            'nama_persyaratan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:file,text',
            'wajib' => 'required|boolean',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_persyaratan.required' => 'Nama persyaratan wajib diisi.',
            'nama_persyaratan.max' => 'Nama persyaratan maksimal 100 karakter.',
            'tipe.required' => 'Tipe persyaratan wajib diisi.',
            'tipe.in' => 'Tipe persyaratan harus file atau text.',
            'wajib.required' => 'Kewajiban persyaratan harus diisi.',
            'status.required' => 'Status persyaratan wajib diisi.',
            'status.in' => 'Status persyaratan harus aktif atau nonaktif.',
        ];
    }
}
