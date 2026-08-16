<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DokumenStoreRequest extends FormRequest
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
            'id_pengajuan' => 'required|exists:tb_pengajuan,id',
            'id_layanan_persyaratan' => 'required|exists:tb_layanan_persyaratan,id',
            'file' => 'nullable|file|max:5120', // 5MB limit
            'text' => 'nullable|string',
        ];
    }
}
