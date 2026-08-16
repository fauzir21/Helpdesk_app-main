<?php

namespace App\Http\Requests;

use App\Models\Layanan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PengajuanStoreRequest extends FormRequest
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
            'id_layanan' => 'required|exists:tb_layanan,id',
            'detail_tambahan' => 'nullable|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $layananId = $this->input('id_layanan');
            if (! $layananId) {
                return;
            }

            $layanan = Layanan::find($layananId);
            if (! $layanan || ! $layanan->input_tambahan) {
                return;
            }

            foreach ($layanan->input_tambahan as $field) {
                $slug = Str::slug($field['label'], '_');
                $value = $this->input("detail_tambahan.{$slug}");

                if (($field['required'] ?? false) && empty($value)) {
                    $validator->errors()->add("detail_tambahan.{$slug}", "Field {$field['label']} wajib diisi.");
                }
            }
        });
    }
}
