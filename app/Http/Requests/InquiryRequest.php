<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'nullable|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'inquiry_type' => 'nullable|string',
            'payment_scheme' => 'nullable|string',
            'survey_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'phone.required' => 'Nomor WhatsApp atau telepon wajib diisi.',
            'phone.string' => 'Nomor WhatsApp atau telepon harus berupa teks yang valid.',
            'phone.max' => 'Nomor WhatsApp atau telepon maksimal 50 karakter.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'survey_date.date' => 'Format tanggal preferensi survei tidak valid.',
            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
