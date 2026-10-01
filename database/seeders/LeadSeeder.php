<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leads = [
            [
                'id' => '#IQ-2209',
                'property_id' => '#KP-8821',
                'property_title' => 'Villa Cliffside Horizon Uluwatu',
                'property_price' => 'Rp 42 Miliar',
                'client_name' => 'Bpk. Richard Tan',
                'client_phone' => '+62 811-9234-5678',
                'client_email' => 'richard.tan@capitalasia.com',
                'buyer_category' => 'Investor Repeat (HNWIs)',
                'message' => 'Halo tim Kumala, kami berencana survei privat Sabtu pagi ini. Mohon info apakah tersedia helipad transfer atau penjemputan bandara Ngurah Rai?',
                'quote' => 'Halo tim Kumala, kami berencana survei privat Sabtu pagi ini. Mohon info apakah tersedia helipad transfer atau penjemputan bandara Ngurah Rai?',
                'inquiry_type' => 'beli',
                'payment_scheme' => 'Cash Keras',
                'survey_date_preference' => 'Sabtu pagi (Pukul 10:30 WIB)',
                'assigned_agent' => 'Gusti Ayu (Concierge Bali)',
                'stage' => 'Baru',
                'sla_minutes' => 12,
                'sla_badge' => 'SLA Hijau (12 mnt)',
                'sla_status' => 'urgent',
                'time_ago' => '12 menit lalu',
            ],
            [
                'id' => '#IQ-2208',
                'property_id' => '#KP-8830',
                'property_title' => 'Menteng Heritage Colonial Mansion',
                'property_price' => 'Rp 85 Miliar',
                'client_name' => 'Ibu Stephanie Halim',
                'client_phone' => '+62 812-7788-9900',
                'client_email' => 'stephanie.h@halimholdings.id',
                'buyer_category' => 'Pembeli Rumah Tinggal',
                'message' => 'Apakah berkas sertifikat SHM sudah diaudit BPN tanpa riwayat sengketa waris? Kami ingin menjadwalkan pertemuan di kantor Menteng.',
                'quote' => 'Apakah berkas sertifikat SHM sudah diaudit BPN tanpa riwayat sengketa waris? Kami ingin menjadwalkan pertemuan di kantor Menteng.',
                'inquiry_type' => 'beli',
                'payment_scheme' => 'Cash Bertahap',
                'survey_date_preference' => 'Kamis siang (Pukul 14:00 WIB)',
                'assigned_agent' => 'Farhan Kamil (Curator Jaksel)',
                'stage' => 'Follow-Up',
                'sla_minutes' => 45,
                'sla_badge' => 'SLA Aman (45 mnt)',
                'sla_status' => 'normal',
                'time_ago' => '45 menit lalu',
            ],
            [
                'id' => '#IQ-2207',
                'property_id' => '#KP-8832',
                'property_title' => 'Lavish Executive Penthouse SCBD',
                'property_price' => 'Rp 850 Jt / Thn',
                'client_name' => 'Mr. Thomas Vance',
                'client_phone' => '+62 819-2233-4455',
                'client_email' => 'tvance@singaporeventures.sg',
                'buyer_category' => 'Sewa Korporat Ekspatriat',
                'message' => 'Survei dikonfirmasi untuk besok pukul 15.00 WIB bersama tim legal perusahaan kami.',
                'quote' => 'Survei dikonfirmasi untuk besok pukul 15.00 WIB bersama tim legal perusahaan kami.',
                'inquiry_type' => 'sewa',
                'payment_scheme' => 'Sewa Korporat 2 Tahun',
                'survey_date_preference' => 'Besok (Pukul 16:30 WIB)',
                'assigned_agent' => 'Aditya Pratama (Super Admin)',
                'stage' => 'Survei',
                'sla_minutes' => 120,
                'sla_badge' => 'Survei Terjadwal',
                'sla_status' => 'normal',
                'time_ago' => '2 jam lalu',
            ],
        ];

        foreach ($leads as $lead) {
            Lead::updateOrCreate(['id' => $lead['id']], $lead);
        }
    }
}
