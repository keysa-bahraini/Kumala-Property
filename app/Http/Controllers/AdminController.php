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
     * Admin Logout
     */
    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_user']);
        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari Portal Kontrol Admin.');
    }
}
