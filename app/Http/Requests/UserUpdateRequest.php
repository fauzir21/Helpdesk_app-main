<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'password' => 'nullable|string|min:8',
            'tipe' => 'required|in:admin,users,pegawai,helpdesk',
            'kategori_user' => 'nullable|required_if:tipe,users|in:umum,pemerintah',
            'tim_kerja_id' => [
                'nullable',
                'array',
            ],
            'tim_kerja_id.*' => [
                'exists:tb_tim_kerja,id_tim_kerja',
            ],
            'status' => 'required|string|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama user wajib diisi.',
            'email.required' => 'Email user wajib diisi.',
            'email.email' => 'Email user tidak valid.',
            'email.unique' => 'Email user sudah terdaftar.',
            'password.min' => 'Password user minimal 8 karakter.',
            'tim_kerja_id.required' => 'Tim kerja user wajib diisi.',
            'status.required' => 'Status user wajib diisi.',
            'status.in' => 'Status user tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'tim_kerja_id' => 'Tim Kerja',
            'status' => 'Status',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->password && strlen($this->password) < 8) {
                $validator->errors()->add('password', 'Password user minimal 8 karakter.');
            }
        });
    }
}
