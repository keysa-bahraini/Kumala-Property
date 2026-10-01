<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'id' => '#CLS-2025-031',
                'date' => '14 Mar 2025',
                'property_id' => '#KP-8830',
                'property_title' => 'Menteng Heritage Colonial Mansion',
                'property_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                'location' => 'Menteng, Jakarta Pusat',
                'trx_type' => 'TERJUAL - JUAL BELI',
                'asking_price' => 'Rp 88.000.000.000',
                'deal_price' => 'Rp 85.000.000.000',
                'deal_val' => 85000000000,
                'diff_pct' => '-3.4%',
                'parties' => 'Penjual: Bpk. Tanu / Pembeli: Konsorsium Artha',
                'commission_val' => 'Rp 2.125.000.000 (2.5%)',
                'notary' => 'Dra. Sri Wahyuni, S.H., M.Kn. (Jaksel)',
                'status_doc' => 'AJB Selesai',
                'days_on_market' => '38 Hari',
            ],
            [
                'id' => '#CLS-2025-030',
                'date' => '02 Mar 2025',
                'property_id' => '#KP-8821',
                'property_title' => 'Villa Cliffside Horizon Uluwatu',
                'property_image' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=400&q=80',
                'location' => 'Uluwatu, Badung, Bali',
                'trx_type' => 'TERJUAL - JUAL BELI',
                'asking_price' => 'Rp 45.000.000.000',
                'deal_price' => 'Rp 42.000.000.000',
                'deal_val' => 42000000000,
                'diff_pct' => '-6.6%',
                'parties' => 'Penjual: PT Bali Vista / Pembeli: Keluarga Gunawan',
                'commission_val' => 'Rp 1.050.000.000 (2.5%)',
                'notary' => 'I Made Suardana, S.H. (Denpasar)',
                'status_doc' => 'AJB Selesai',
                'days_on_market' => '45 Hari',
            ],
            [
                'id' => '#CLS-2025-029',
                'date' => '22 Feb 2025',
                'property_id' => '#KP-8832',
                'property_title' => 'Lavish Executive Penthouse SCBD',
                'property_image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=400&q=80',
                'location' => 'SCBD Senayan, Jakarta Selatan',
                'trx_type' => 'TERSEWA - 2 TAHUN',
                'asking_price' => 'Rp 900.000.000 / Thn',
                'deal_price' => 'Rp 1.700.000.000 (2 Thn)',
                'deal_val' => 1700000000,
                'diff_pct' => '-5.5%',
                'parties' => 'Pemilik: Ibu Maya / Penyewa: Tech Global Corp',
                'commission_val' => 'Rp 85.000.000 (5%)',
                'notary' => 'Notaris Internal Rekanan Kumala',
                'status_doc' => 'Perjanjian Sewa Sah',
                'days_on_market' => '24 Hari',
            ],
            [
                'id' => '#CLS-2025-028',
                'date' => '10 Feb 2025',
                'property_id' => '#KP-8831',
                'property_title' => 'Tropical Modern Sanctuary Canggu',
                'property_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                'location' => 'Canggu, Badung, Bali',
                'trx_type' => 'TERSEWA - 1 TAHUN',
                'asking_price' => 'Rp 750.000.000 / Thn',
                'deal_price' => 'Rp 720.000.000 / Thn',
                'deal_val' => 720000000,
                'diff_pct' => '-4.0%',
                'parties' => 'Pemilik: Bpk. Wayan / Penyewa: Mr. David Miller',
                'commission_val' => 'Rp 36.000.000 (5%)',
                'notary' => 'Notaris Internal Rekanan Kumala',
                'status_doc' => 'Perjanjian Sewa Sah',
                'days_on_market' => '18 Hari',
            ],
        ];

        foreach ($transactions as $tx) {
            Transaction::updateOrCreate(['id' => $tx['id']], $tx);
        }
    }
}
