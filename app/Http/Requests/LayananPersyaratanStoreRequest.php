<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LayananPersyaratanStoreRequest extends FormRequest
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
            'layanan_id' => 'required|exists:tb_layanan,id',
            'persyaratan_ids' => 'required|array',
            'persyaratan_ids.*' => 'exists:tb_persyaratan,id',
        ];
    }

    public function messages(): array
    {
        return [
            'layanan_id.required' => 'Layanan harus diisi.',
            'layanan_id.exists' => 'Layanan tidak ditemukan.',
            'persyaratan_ids.required' => 'Persyaratan harus diisi.',
            'persyaratan_ids.array' => 'Persyaratan harus berupa array.',
            'persyaratan_ids.*.exists' => 'Persyaratan tidak ditemukan.',
        ];
    }
}
