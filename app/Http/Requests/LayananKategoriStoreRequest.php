<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LayananKategoriStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'layanan_id' => 'required|exists:tb_layanan,id',
            'nama_kategori' => 'required|string|max:255',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ];
    }
}
