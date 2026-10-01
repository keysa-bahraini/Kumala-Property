<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            [
                'timestamp_display' => '11:42 WIB',
                'action' => 'Persetujuan Listing Baru #KP-8826',
                'user' => 'Aditya Pratama (Super Admin)',
                'status' => 'Published',
                'details' => ['property_id' => '#KP-8826', 'action_type' => 'publish'],
            ],
            [
                'timestamp_display' => '10:15 WIB',
                'action' => 'Penyesuaian Harga Asking #KP-8821 (Rp 45M -> Rp 42M)',
                'user' => 'Farhan Kamil (Curator)',
                'status' => 'Updated',
                'details' => ['property_id' => '#KP-8821', 'old_price' => 45000000000, 'new_price' => 42000000000],
            ],
            [
                'timestamp_display' => '09:30 WIB',
                'action' => 'Inquiry Baru #IQ-2209 Dialokasikan ke Concierge Bali',
                'user' => 'Sistem Otomatis (Rule Engine)',
                'status' => 'Assigned',
                'details' => ['lead_id' => '#IQ-2209', 'assigned_to' => 'Gusti Ayu'],
            ],
            [
                'timestamp_display' => '08:50 WIB',
                'action' => 'Unduhan Factsheet PDF #KP-8822 oleh Klien VIP',
                'user' => 'Portal Publik (IP 182.253.xx.xx)',
                'status' => 'Downloaded',
                'details' => ['property_id' => '#KP-8822', 'format' => 'pdf'],
            ],
        ];

        foreach ($logs as $lg) {
            AuditLog::updateOrCreate(
                [
                    'timestamp_display' => $lg['timestamp_display'],
                    'action' => $lg['action'],
                ],
                $lg
            );
        }
    }
}
