<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitipPropertiRequest extends FormRequest
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
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:50',
            'owner_email' => 'required|email|max:255',
            'ownership_status' => 'nullable|string',
            'transaction_type' => 'nullable|string',
            'property_category' => 'nullable|string',
            'property_title' => 'required|string|max:255',
            'city_region' => 'required|string',
            'specific_area' => 'required|string|max:255',
            'full_address' => 'required|string|max:1000',
            'asking_price' => 'required|string|max:100',
            'is_negotiable' => 'nullable',
            'term_rate' => 'nullable|string',
            'land_size' => 'nullable|numeric',
            'building_size' => 'nullable|numeric',
            'bedrooms' => 'nullable|string',
            'bathrooms' => 'nullable|string',
            'facilities' => 'nullable|array',
            'description' => 'required|string|min:10',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:10240',
            'legal_consent' => 'accepted',
        ];
    }

    /**
     * Custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'owner_name.required' => 'Nama lengkap pemilik atau kuasa hukum wajib diisi.',
            'owner_phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'owner_email.required' => 'Alamat email aktif wajib diisi.',
            'owner_email.email' => 'Format email tidak valid.',
            'property_title.required' => 'Nama atau judul properti wajib diisi.',
            'city_region.required' => 'Provinsi dan kota/kabupaten wajib dipilih.',
            'specific_area.required' => 'Area atau kawasan spesifik wajib diisi.',
            'full_address.required' => 'Alamat lengkap properti wajib diisi.',
            'asking_price.required' => 'Nilai penawaran atau harga wajib diisi.',
            'description.required' => 'Deskripsi properti wajib diisi.',
            'description.min' => 'Deskripsi properti minimal 10 karakter.',
            'photos.*.mimes' => 'Format foto harus berupa JPG, PNG, atau WEBP.',
            'photos.*.max' => 'Ukuran setiap berkas foto maksimal 10 MB.',
            'legal_consent.accepted' => 'Anda harus menyetujui pernyataan keabsahan kepemilikan aset.',
        ];
    }
}
