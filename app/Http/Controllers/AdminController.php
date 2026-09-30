<?php

namespace App\Http\Controllers;

use App\Services\PropertyDataService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Page 7: Login Admin & Security Gateway
     */
    public function login()
    {
        // If already logged in to mock session, redirect to dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle Admin Authentication
     */
    public function authenticate(Request $request)
    {
        $email = $request->input('email', 'admin@kumalaproperty.com');
        $password = $request->input('password', '');

        // Accept demo admin credentials
        if ($email === 'admin@kumalaproperty.com' || !empty($password)) {
            session([
                'admin_logged_in' => true,
                'admin_user' => [
                    'name' => 'Aditya Pratama',
                    'role' => 'Senior Curator & Super Admin',
                    'email' => $email,
                    'avatar' => 'AP',
                ]
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali di Portal Kontrol Kumala Property.');
        }

        return back()->withErrors(['email' => 'Kredensial login tidak cocok dengan data personel terdaftar.']);
    }

    /**
     * Page 8: Admin Operational Dashboard
     */
    public function dashboard()
    {
        $properties = PropertyDataService::getAll();
        
        // Operational Metrics Data (aligned with PRD Section 6.2)
        $metrics = [
            'active_units' => 148,
            'active_jual' => 96,
            'active_sewa' => 52,
            'active_growth' => '+4 unit minggu ini',
            'pending_review' => 3,
            'new_inquiries' => 5,
            'avg_sla_minutes' => 18,
            'closing_month_units' => 12,
            'closing_month_val' => 'Rp 18.4 Miliar',
            'closing_target_pct' => 85,
        ];

        // Priority Action Inbox (Perlu Ditindaklanjuti Segera)
        $priorityActions = [
            [
                'id' => '#TP-204',
                'type' => 'listing',
                'title' => 'Pengajuan Konsinyasi Baru: Modern Tropical Villa Canggu',
                'owner' => 'Hendra Wijaya',
                'phone' => '+62 812-8899-2345',
                'time_ago' => '45 menit yang lalu',
                'sla_status' => 'warning',
                'sla_badge' => 'SLA Respon < 4 Jam',
                'detail' => 'Harga pengajuan Rp 2.85 M. Dokumen SHM dan KTP telah diunggah. Membutuhkan verifikasi appraisal & persetujuan tayang.',
                'btn_action_1' => 'Verifikasi SHM BPN',
                'btn_action_2' => 'Tinjau & Kurasi Unit',
            ],
            [
                'id' => '#IQ-2209',
                'type' => 'inquiry',
                'title' => 'Inquiry Beli Segera: Villa Cliffside Horizon Uluwatu (#KP-8821)',
                'owner' => 'Bpk. Richard Tan (Investor)',
                'phone' => '+62 811-9234-5678',
                'time_ago' => '12 menit yang lalu',
                'sla_status' => 'urgent',
                'sla_badge' => 'SLA Respon Hijau (12 mnt)',
                'detail' => 'Meminta jadwal survei privat Sabtu pagi dengan penjemputan bandara Ngurah Rai. Skema pembayaran Cash Keras.',
                'btn_action_1' => 'Hubungi via WhatsApp',
                'btn_action_2' => 'Jadwalkan Survei',
            ],
            [
                'id' => '#CLS-2025-031',
                'type' => 'closing',
                'title' => 'Draft Final PPJB: Menteng Heritage Colonial Mansion (#KP-8822)',
                'owner' => 'Notaris Rekanan: Dra. Sri Wahyuni, S.H.',
                'phone' => '+62 813-7722-1100',
                'time_ago' => '2 jam yang lalu',
                'sla_status' => 'normal',
                'sla_badge' => 'Jadwal Closing: 14:00 WIB',
                'detail' => 'Nilai deal disepakati Rp 85 Miliar. Salinan draft PPJB dan bukti validasi PPh/BPHTB siap ditandatangani.',
                'btn_action_1' => 'Cetak Draft PPJB',
                'btn_action_2' => 'Verifikasi Pajak',
            ],
            [
                'id' => '#TP-198',
                'type' => 'listing',
                'title' => 'Permintaan Revisi Foto: Lavish Executive Penthouse SCBD (#KP-8824)',
                'owner' => 'Ibu Maya Santoso',
                'phone' => '+62 818-4455-6677',
                'time_ago' => '4 jam yang lalu',
                'sla_status' => 'normal',
                'sla_badge' => 'Menunggu Dokumen Tambahan',
                'detail' => 'Resolusi denah arsitektur belum terbaca jelas. Tim fotografer Kumala dijadwalkan ulang besok siang.',
                'btn_action_1' => 'Kirim Pengingat WA',
                'btn_action_2' => 'Buka Form Edit',
            ],
        ];

        // Audit Trail Activity Log
        $auditLogs = [
            [
                'timestamp' => '11:42 WIB',
                'action' => 'Persetujuan Listing Baru #KP-8826',
                'user' => 'Aditya Pratama (Super Admin)',
                'status' => 'Published',
            ],
            [
                'timestamp' => '10:15 WIB',
                'action' => 'Penyesuaian Harga Asking #KP-8821 (Rp 45M -> Rp 42M)',
                'user' => 'Farhan Kamil (Curator)',
                'status' => 'Updated',
            ],
            [
                'timestamp' => '09:30 WIB',
                'action' => 'Inquiry Baru #IQ-2209 Dialokasikan ke Concierge Bali',
                'user' => 'Sistem Otomatis (Rule Engine)',
                'status' => 'Assigned',
            ],
            [
                'timestamp' => '08:50 WIB',
                'action' => 'Unduhan Factsheet PDF #KP-8822 oleh Klien VIP',
                'user' => 'Portal Publik (IP 182.253.xx.xx)',
                'status' => 'Downloaded',
            ],
        ];

        return view('admin.dashboard', compact('properties', 'metrics', 'priorityActions', 'auditLogs'));
    }

    /**
     * Page 9: Manajemen Properti (Inventory Hub)
     */
    public function properties(Request $request)
    {
        $search = $request->query('search', '');
        $status = $request->query('status', 'Semua');
        $type = $request->query('type', 'Semua');
        $region = $request->query('region', 'Semua');

        $allProperties = PropertyDataService::getAll();

        $metrics = [
            'total_active' => 112,
            'draft_review' => 24,
            'leads_total' => 842,
            'portfolio_val' => 'Rp 482 M',
        ];

        return view('admin.properties', compact('allProperties', 'metrics', 'search', 'status', 'type', 'region'));
    }

    /**
     * Page 10: Manajemen Listing Masuk (Gatekeeper Verification)
     */
    public function submissions()
    {
        $queueCounts = [
            'all' => 28,
            'pending' => 3,
            'revision' => 2,
            'approved' => 19,
            'rejected' => 4,
        ];

        $submissions = [
            [
                'id' => '#TP-204',
                'title' => 'Modern Tropical Villa Canggu',
                'owner' => 'Hendra Wijaya',
                'phone' => '+62 812-8899-2345',
                'email' => 'hendra@canggurealty.id',
                'price' => 'Rp 2.850.000.000',
                'asking_val' => 2850000000,
                'valuation_range' => 'Rp 2.8 M - 3.1 Miliar',
                'trx_type' => 'Jual',
                'category' => 'Villa Mewah',
                'location' => 'Bali - Canggu, Badung',
                'address' => 'Jl. Pantai Batu Mejan No. 88, Canggu, Kuta Utara',
                'land_size' => 140,
                'building_size' => 180,
                'bedrooms' => '3+1 KT',
                'bathrooms' => '3 KM',
                'electricity' => '3500 VA',
                'legal' => 'SHM (Hak Milik)',
                'ownership' => 'Pemilik Langsung',
                'time_ago' => '45 menit lalu',
                'status' => 'pending',
                'priority' => 'P1 - Hot Asset',
                'photos_count' => 6,
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => true,
                    'photos_hd' => false,
                ],
                'notes' => 'Aset lokasi sangat prime dekat Echo Beach. Foto interior dapur perlu peningkatan pencahayaan standar Kumala sebelum diterbitkan.',
            ],
            [
                'id' => '#TP-205',
                'title' => 'Colonial Executive Residence Kemang',
                'owner' => 'Ibu Farida Siregar',
                'phone' => '+62 811-3344-5566',
                'email' => 'farida.s@siregarholding.com',
                'price' => 'Rp 38.000.000.000',
                'asking_val' => 38000000000,
                'valuation_range' => 'Rp 36 M - 39 Miliar',
                'trx_type' => 'Jual',
                'category' => 'Rumah Mewah',
                'location' => 'DKI Jakarta - Kemang, Jaksel',
                'address' => 'Jl. Kemang Timur Raya No. 14, Bangka, Jakarta Selatan',
                'land_size' => 750,
                'building_size' => 550,
                'bedrooms' => '5 KT',
                'bathrooms' => '5 KM',
                'electricity' => '13200 VA',
                'legal' => 'SHM Clear',
                'ownership' => 'Pemilik Langsung',
                'time_ago' => '2 jam lalu',
                'status' => 'pending',
                'priority' => 'P2 - High Priority',
                'photos_count' => 12,
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => true,
                    'photos_hd' => true,
                ],
                'notes' => 'Legalitas sertifikat SHM sudah diverifikasi notaris rekanan. Menunggu persetujuan final Super Admin.',
            ],
            [
                'id' => '#TP-206',
                'title' => 'Beachside Cliff Pavillion Uluwatu',
                'owner' => 'Bpk. Kenneth Lee (Kuasa Jual)',
                'phone' => '+62 817-9000-1122',
                'email' => 'k.lee@pacificwealth.sg',
                'price' => 'Rp 650.000.000 / Thn',
                'asking_val' => 650000000,
                'valuation_range' => 'Rp 600 Jt - 700 Jt / Thn',
                'trx_type' => 'Sewa',
                'category' => 'Villa Mewah',
                'location' => 'Bali - Uluwatu, Badung',
                'address' => 'Jl. Pantai Suluban No. 12, Pecatu, Uluwatu',
                'land_size' => 600,
                'building_size' => 450,
                'bedrooms' => '4 KT',
                'bathrooms' => '4 KM',
                'electricity' => '7700 VA',
                'legal' => 'HGB Murni',
                'ownership' => 'Kuasa Jual Resmi',
                'time_ago' => '3 jam lalu',
                'status' => 'pending',
                'priority' => 'P2 - High Priority',
                'photos_count' => 8,
                'checklist' => [
                    'identity' => true,
                    'maps' => true,
                    'shm_match' => false,
                    'photos_hd' => true,
                ],
                'notes' => 'Surat kuasa notarial dari pemilik Singapura sedang diproses legalisasi Kemenkumham.',
            ],
        ];

        $selectedSubmission = $submissions[0];

        return view('admin.submissions', compact('queueCounts', 'submissions', 'selectedSubmission'));
    }

    /**
     * Page 11: Riwayat Terjual & Tersewa (Closing Archive)
     */
    public function transactions()
    {
        $metrics = [
            'total_volume' => 'Rp 184.5 Miliar',
            'total_units' => 38,
            'total_jual' => 26,
            'total_sewa' => 12,
            'avg_dom' => '42 Hari',
            'commission_total' => 'Rp 4.6 Miliar',
        ];

        $transactions = [
            [
                'id' => '#CLS-2025-031',
                'date' => '14 Mar 2025',
                'property_id' => '#KP-8822',
                'property_title' => 'Menteng Heritage Colonial Mansion',
                'property_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                'location' => 'Menteng, Jakarta Pusat',
                'trx_type' => 'TERJUAL - JUAL BELI',
                'asking_price' => 'Rp 88.000.000.000',
                'deal_price' => 'Rp 85.000.000.000',
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
                'property_id' => '#KP-8824',
                'property_title' => 'Lavish Executive Penthouse SCBD',
                'property_image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=400&q=80',
                'location' => 'SCBD Senayan, Jakarta Selatan',
                'trx_type' => 'TERSEWA - 2 TAHUN',
                'asking_price' => 'Rp 900.000.000 / Thn',
                'deal_price' => 'Rp 1.700.000.000 (2 Thn)',
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
                'property_id' => '#KP-8823',
                'property_title' => 'Tropical Modern Sanctuary Canggu',
                'property_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                'location' => 'Canggu, Badung, Bali',
                'trx_type' => 'TERSEWA - 1 TAHUN',
                'asking_price' => 'Rp 750.000.000 / Thn',
                'deal_price' => 'Rp 720.000.000 / Thn',
                'diff_pct' => '-4.0%',
                'parties' => 'Pemilik: Bpk. Wayan / Penyewa: Mr. David Miller',
                'commission_val' => 'Rp 36.000.000 (5%)',
                'notary' => 'Notaris Internal Rekanan Kumala',
                'status_doc' => 'Perjanjian Sewa Sah',
                'days_on_market' => '18 Hari',
            ],
        ];

        return view('admin.transactions', compact('metrics', 'transactions'));
    }

    /**
     * Page 12: Manajemen Data Inquiry (Lead Pipeline CRM)
     */
    public function inquiries()
    {
        $stageCounts = [
            'all' => 42,
            'new' => 5,
            'follow_up' => 18,
            'survey' => 9,
            'closing' => 10,
        ];

        $inquiries = [
            [
                'id' => '#IQ-2209',
                'client_name' => 'Bpk. Richard Tan',
                'phone' => '+62 811-9234-5678',
                'email' => 'richard.tan@capitalasia.com',
                'buyer_type' => 'Investor Repeat (HNWIs)',
                'time_ago' => '12 menit lalu',
                'sla_badge' => 'SLA Hijau (12 mnt)',
                'sla_status' => 'urgent',
                'stage' => 'Baru',
                'property_id' => '#KP-8821',
                'property_title' => 'Villa Cliffside Horizon Uluwatu',
                'property_price' => 'Rp 42 Miliar',
                'quote' => 'Halo tim Kumala, kami berencana survei privat Sabtu pagi ini. Mohon info apakah tersedia helipad transfer atau penjemputan bandara Ngurah Rai?',
                'assigned_to' => 'Gusti Ayu (Concierge Bali)',
            ],
            [
                'id' => '#IQ-2208',
                'client_name' => 'Ibu Stephanie Halim',
                'phone' => '+62 812-7788-9900',
                'email' => 'stephanie.h@halimholdings.id',
                'buyer_type' => 'Pembeli Rumah Tinggal',
                'time_ago' => '45 menit lalu',
                'sla_badge' => 'SLA Aman (45 mnt)',
                'sla_status' => 'normal',
                'stage' => 'Follow-Up',
                'property_id' => '#KP-8822',
                'property_title' => 'Menteng Heritage Colonial Mansion',
                'property_price' => 'Rp 85 Miliar',
                'quote' => 'Apakah berkas sertifikat SHM sudah diaudit BPN tanpa riwayat sengketa waris? Kami ingin menjadwalkan pertemuan di kantor Menteng.',
                'assigned_to' => 'Farhan Kamil (Curator Jaksel)',
            ],
            [
                'id' => '#IQ-2207',
                'client_name' => 'Mr. Thomas Vance',
                'phone' => '+62 819-2233-4455',
                'email' => 'tvance@singaporeventures.sg',
                'buyer_type' => 'Sewa Korporat Ekspatriat',
                'time_ago' => '2 jam lalu',
                'sla_badge' => 'Survei Terjadwal',
                'sla_status' => 'normal',
                'stage' => 'Survei',
                'property_id' => '#KP-8824',
                'property_title' => 'Lavish Executive Penthouse SCBD',
                'property_price' => 'Rp 850 Jt / Thn',
                'quote' => 'Survei dikonfirmasi untuk besok pukul 15.00 WIB bersama tim legal perusahaan kami.',
                'assigned_to' => 'Aditya Pratama (Super Admin)',
            ],
        ];

        $surveysToday = [
            [
                'time' => '10:30 WIB',
                'property' => 'Villa Cliffside Horizon Uluwatu',
                'client' => 'Bpk. Richard Tan',
                'agent' => 'Gusti Ayu',
                'status' => 'Terkonfirmasi',
            ],
            [
                'time' => '14:00 WIB',
                'property' => 'Menteng Heritage Mansion',
                'client' => 'Ibu Stephanie Halim',
                'agent' => 'Farhan Kamil',
                'status' => 'Menunggu Klien',
            ],
            [
                'time' => '16:30 WIB',
                'property' => 'Lavish Penthouse SCBD',
                'client' => 'Mr. Thomas Vance',
                'agent' => 'Aditya Pratama',
                'status' => 'Terkonfirmasi',
            ],
        ];

        return view('admin.inquiries', compact('stageCounts', 'inquiries', 'surveysToday'));
    }

    /**
     * Page 13: Pengaturan Sistem & Master Data
     */
    public function settings()
    {
        $regions = [
            ['name' => 'DKI Jakarta - Jakarta Selatan', 'active' => true, 'listings' => 48],
            ['name' => 'Bali - Badung (Canggu, Uluwatu, Seminyak)', 'active' => true, 'listings' => 52],
            ['name' => 'Banten - Tangerang Selatan (BSD City)', 'active' => true, 'listings' => 24],
            ['name' => 'DKI Jakarta - Jakarta Pusat (Menteng)', 'active' => true, 'listings' => 12],
            ['name' => 'Jawa Timur - Surabaya Barat', 'active' => true, 'listings' => 8],
            ['name' => 'Jawa Barat - Bandung Utara', 'active' => false, 'listings' => 4],
        ];

        $categories = [
            ['name' => 'Rumah Tapak Mewah', 'subtypes' => 'Heritage, Colonial, Modern Minimalis', 'active' => true, 'count' => 64],
            ['name' => 'Villa Mewah & Tropis', 'subtypes' => 'Cliffside, Beachfront, Ricefield View', 'active' => true, 'count' => 52],
            ['name' => 'Ruko & Komersial', 'subtypes' => 'Shophouse, Butik, Kantor Representatif', 'active' => true, 'count' => 18],
            ['name' => 'Apartemen & Penthouse', 'subtypes' => 'Sky Villa, Penthouse SCBD, High-rise', 'active' => true, 'count' => 14],
        ];

        return view('admin.settings', compact('regions', 'categories'));
    }

    /**
     * Admin Logout
     */
    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_user']);
        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari Portal Kontrol Admin.');
    }
}

