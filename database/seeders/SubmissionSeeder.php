<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\SubmissionChecklist;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $submissions = [
            [
                'id' => '#TP-204',
                'title' => 'Modern Tropical Villa Canggu',
                'owner_name' => 'Hendra Wijaya',
                'owner_phone' => '+62 812-8899-2345',
                'owner_email' => 'hendra@canggurealty.id',
                'owner_status' => 'Pemilik Langsung',
                'trx_type' => 'Jual',
                'category' => 'Villa Mewah',
                'location' => 'Bali - Canggu, Badung',
                'address' => 'Jl. Pantai Batu Mejan No. 88, Canggu, Kuta Utara',
                'asking_price' => 'Rp 2.850.000.000',
                'asking_val' => 2850000000,
                'valuation_range' => 'Rp 2.8 M - 3.1 Miliar',
                'land_size' => 140,
                'building_size' => 180,
                'bedrooms' => '3+1 KT',
                'bathrooms' => '3 KM',
                'electricity' => '3500 VA',
                'legal' => 'SHM (Hak Milik)',
                'priority' => 'P1 - Hot Asset',
                'photos_count' => 6,
                'status' => 'pending',
                'notes' => 'Aset lokasi sangat prime dekat Echo Beach. Foto interior dapur perlu peningkatan pencahayaan standar Kumala sebelum diterbitkan.',
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => true,
                    'photos_hd' => false,
                ],
            ],
            [
                'id' => '#TP-205',
                'title' => 'Colonial Executive Residence Kemang',
                'owner_name' => 'Ibu Farida Siregar',
                'owner_phone' => '+62 811-3344-5566',
                'owner_email' => 'farida.s@siregarholding.com',
                'owner_status' => 'Pemilik Langsung',
                'trx_type' => 'Jual',
                'category' => 'Rumah Mewah',
                'location' => 'DKI Jakarta - Kemang, Jaksel',
                'address' => 'Jl. Kemang Timur Raya No. 14, Bangka, Jakarta Selatan',
                'asking_price' => 'Rp 38.000.000.000',
                'asking_val' => 38000000000,
                'valuation_range' => 'Rp 36 M - 39 Miliar',
                'land_size' => 750,
                'building_size' => 550,
                'bedrooms' => '5 KT',
                'bathrooms' => '5 KM',
                'electricity' => '13200 VA',
                'legal' => 'SHM Clear',
                'priority' => 'P2 - High Priority',
                'photos_count' => 12,
                'status' => 'pending',
                'notes' => 'Legalitas sertifikat SHM sudah diverifikasi notaris rekanan. Menunggu persetujuan final Super Admin.',
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => true,
                    'photos_hd' => true,
                ],
            ],
            [
                'id' => '#TP-206',
                'title' => 'Beachside Cliff Pavillion Uluwatu',
                'owner_name' => 'Bpk. Kenneth Lee (Kuasa Jual)',
                'owner_phone' => '+62 817-9000-1122',
                'owner_email' => 'k.lee@pacificwealth.sg',
                'owner_status' => 'Kuasa Jual Resmi',
                'trx_type' => 'Sewa',
                'category' => 'Villa Mewah',
                'location' => 'Bali - Uluwatu, Badung',
                'address' => 'Jl. Pantai Suluban No. 12, Pecatu, Uluwatu',
                'asking_price' => 'Rp 650.000.000 / Thn',
                'asking_val' => 650000000,
                'valuation_range' => 'Rp 600 Jt - 700 Jt / Thn',
                'land_size' => 600,
                'building_size' => 450,
                'bedrooms' => '4 KT',
                'bathrooms' => '4 KM',
                'electricity' => '7700 VA',
                'legal' => 'HGB Murni',
                'priority' => 'P2 - High Priority',
                'photos_count' => 8,
                'status' => 'pending',
                'notes' => 'Surat kuasa notarial dari pemilik Singapura sedang diproses legalisasi Kemenkumham.',
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => false,
                    'photos_hd' => true,
                ],
            ],
        ];

        foreach ($submissions as $sub) {
            $checklistData = $sub['checklist'];
            unset($sub['checklist']);

            $submission = Submission::updateOrCreate(
                ['id' => $sub['id']],
                $sub
            );

            SubmissionChecklist::updateOrCreate(
                ['submission_id' => $submission->id],
                $checklistData
            );
        }
    }
}
