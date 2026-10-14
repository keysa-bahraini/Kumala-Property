<?php

namespace App\Http\Controllers;

use App\Services\KumalaDataHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Page 7: Login Admin & Security Gateway
     */
    public function login()
    {
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan masuk gagal. Silakan coba lagi dalam {$seconds} detik demi keamanan sistem.",
            ])->onlyInput('email');
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki hak akses administratif.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '. Sesi terverifikasi.');
        }

        RateLimiter::hit($throttleKey, 300);

        return back()->withErrors([
            'email' => 'Kombinasi email atau kata sandi tidak cocok dengan data personel terdaftar.',
        ])->onlyInput('email');
    }

    /**
     * Page 8: Admin Operational Dashboard
     * Tersambung langsung ke metrik dinamis dari User submissions & inquiries!
     */
    public function dashboard()
    {
        $properties = KumalaDataHub::getAllProperties();
        $metrics = KumalaDataHub::getDashboardMetrics();
        $priorityActions = KumalaDataHub::getPriorityActions();
        $auditLogs = KumalaDataHub::getAuditLogs();

        return view('admin.dashboard', compact('properties', 'metrics', 'priorityActions', 'auditLogs'));
    }

    /**
     * Page 9: Master Inventory Properti
     */
    public function properties(Request $request)
    {
        $search = $request->query('search', '');
        $status = $request->query('status', 'Semua');
        $type = $request->query('type', 'Semua');
        $region = $request->query('region', 'Semua');

        $allProperties = KumalaDataHub::getAllProperties();

        // Apply filters
        if (!empty($search)) {
            $allProperties = array_values(array_filter($allProperties, function ($p) use ($search) {
                return stripos($p['title'] ?? '', $search) !== false || stripos($p['id'] ?? '', $search) !== false;
            }));
        }

        if ($status !== 'Semua') {
            $allProperties = array_values(array_filter($allProperties, function ($p) use ($status) {
                return ($p['status'] ?? 'ACTIVE') === $status;
            }));
        }

        if ($type !== 'Semua') {
            $allProperties = array_values(array_filter($allProperties, function ($p) use ($type) {
                return strtolower($p['transaction_type'] ?? '') === strtolower($type);
            }));
        }

        $metrics = [
            'total_active' => count(array_filter(KumalaDataHub::getAllProperties(), fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE')),
            'draft_review' => count(KumalaDataHub::getSubmissions('pending')),
            'leads_total' => count(KumalaDataHub::getInquiries()),
            'portfolio_val' => 'Rp 482 M',
        ];

        return view('admin.properties', compact('allProperties', 'metrics', 'search', 'status', 'type', 'region'));
    }

    /**
     * Add new property from Admin
     */
    public function storeProperty(Request $request)
    {
        $prop = KumalaDataHub::addProperty($request->all());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'property' => $prop]);
        }

        return redirect()->route('admin.properties')->with('success', "Properti {$prop['id']} berhasil diterbitkan ke katalog publik!");
    }

    /**
     * Delete property
     */
    public function deleteProperty(string $id)
    {
        KumalaDataHub::deleteProperty($id);
        return redirect()->route('admin.properties')->with('success', "Properti {$id} berhasil dihapus dari inventory.");
    }

    /**
     * Update Property Status
     */
    public function updatePropertyStatus(Request $request, string $id)
    {
        $status = $request->input('status', 'ACTIVE');
        KumalaDataHub::updatePropertyStatus($id, $status);
        return back()->with('success', "Status properti {$id} berhasil diperbarui.");
    }

    /**
     * Page 10: Manajemen Listing Masuk (Gatekeeper Verification)
     * Langsung menampilkan kiriman form Titip Properti dari user!
     */
    public function submissions()
    {
        $submissions = KumalaDataHub::getSubmissions();

        $queueCounts = [
            'all' => count($submissions),
            'pending' => count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'pending')),
            'revision' => count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'revision')),
            'approved' => count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'approved')),
            'rejected' => count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'rejected')),
        ];

        $selectedSubmission = $submissions[0] ?? null;

        return view('admin.submissions', compact('queueCounts', 'submissions', 'selectedSubmission'));
    }

    /**
     * Approve submission and PUBLISH TO LIVE USERPAGE!
     */
    public function approveSubmission(Request $request, string $id)
    {
        $notes = $request->input('notes', 'Disetujui oleh tim kurator Kumala.');
        $newProp = KumalaDataHub::approveSubmission($id, $notes);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pengajuan {$id} disetujui dan telah tayang di katalog publik sebagai {$newProp['id']}!",
                'property' => $newProp
            ]);
        }

        return redirect()->route('admin.submissions')->with('success', "Pengajuan {$id} disetujui dan berhasil diterbitkan ke publik!");
    }

    /**
     * Reject or request revision for submission
     */
    public function updateSubmissionStatus(Request $request, string $id)
    {
        $status = $request->input('status', 'revision');
        $notes = $request->input('notes', '');
        KumalaDataHub::updateSubmissionStatus($id, $status, $notes);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Status pengajuan {$id} diperbarui menjadi {$status}."]);
        }

        return redirect()->route('admin.submissions')->with('success', "Status pengajuan {$id} diperbarui menjadi {$status}.");
    }

    /**
     * Page 11: Riwayat Terjual & Tersewa (Closing Archive)
     */
    public function transactions()
    {
        $transactions = KumalaDataHub::getTransactions();

        $metrics = [
            'total_volume' => 'Rp 184.5 Miliar',
            'total_units' => count($transactions),
            'total_jual' => count(array_filter($transactions, fn($t) => stripos($t['trx_type'] ?? '', 'JUAL') !== false)),
            'total_sewa' => count(array_filter($transactions, fn($t) => stripos($t['trx_type'] ?? '', 'SEWA') !== false)),
            'avg_dom' => '38 Hari',
            'commission_total' => 'Rp 4.6 Miliar',
        ];

        return view('admin.transactions', compact('metrics', 'transactions'));
    }

    /**
     * Create Closing Transaction
     */
    public function storeTransaction(Request $request)
    {
        $trx = KumalaDataHub::createTransaction($request->all());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'transaction' => $trx]);
        }

        return redirect()->route('admin.transactions')->with('success', "Transaksi closing {$trx['id']} berhasil dicatatkan.");
    }

    /**
     * Page 12: Manajemen Data Inquiry (Lead Pipeline CRM)
     * Langsung menampilkan inquiry yang dikirim user dari halaman Detail Properti!
     */
    public function inquiries()
    {
        $inquiries = KumalaDataHub::getInquiries();

        $stageCounts = [
            'all' => count($inquiries),
            'new' => count(array_filter($inquiries, fn($i) => strtolower($i['stage'] ?? '') === 'baru')),
            'follow_up' => count(array_filter($inquiries, fn($i) => strtolower($i['stage'] ?? '') === 'follow-up')),
            'survey' => count(array_filter($inquiries, fn($i) => strtolower($i['stage'] ?? '') === 'survei')),
            'closing' => count(array_filter($inquiries, fn($i) => strtolower($i['stage'] ?? '') === 'closing')),
        ];

        $surveysToday = KumalaDataHub::getSurveysToday();

        return view('admin.inquiries', compact('stageCounts', 'inquiries', 'surveysToday'));
    }

    /**
     * Update Inquiry Stage (Baru -> Follow-up -> Survei -> Closing)
     */
    public function updateInquiryStage(Request $request, string $id)
    {
        $stage = $request->input('stage', 'Follow-Up');
        KumalaDataHub::updateInquiryStage($id, $stage);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Tahapan lead {$id} diperbarui ke {$stage}."]);
        }

        return redirect()->route('admin.inquiries')->with('success', "Tahapan lead {$id} diperbarui ke {$stage}.");
    }

    /**
     * Add survey schedule
     */
    public function addSurvey(Request $request)
    {
        $survey = KumalaDataHub::addSurvey($request->all());
        return redirect()->route('admin.inquiries')->with('success', 'Jadwal survei lokasi berhasil ditambahkan.');
    }

    /**
     * Page 13: Pengaturan Sistem & Master Data
     */
    public function settings()
    {
        $regions = KumalaDataHub::getRegions();
        $categories = KumalaDataHub::getCategories();
        $settings = KumalaDataHub::getSettings();

        return view('admin.settings', compact('regions', 'categories', 'settings'));
    }

    /**
     * Save settings from Admin (langsung menyinkronkan logo, teks, dan seluruh userpage secara real-time)
     */
    public function saveSettings(Request $request)
    {
        $payload = $request->except(['_token']);

        // Handle logo file upload if provided
        if ($request->hasFile('brand_logo_file') && $request->file('brand_logo_file')->isValid()) {
            $file = $request->file('brand_logo_file');
            $uploadDir = public_path('uploads/branding');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $payload['brand_logo_url'] = asset('uploads/branding/' . $filename);
        }
        unset($payload['brand_logo_file']);

        $updated = KumalaDataHub::updateSettings($payload);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan sistem & seluruh konten portal publik berhasil diperbarui!',
                'settings' => $updated
            ]);
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan sistem & seluruh konten portal publik berhasil diperbarui secara real-time!');
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari portal admin.');
    }

    /**
     * Store new property category from Admin
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        KumalaDataHub::addCategory($request->all());

        return redirect()->route('admin.settings')->with('success', 'Kategori properti baru berhasil ditambahkan.');
    }

    /**
     * Safe redirect for GET /admin/logout
     */
    public function logoutRedirect()
    {
        return redirect()->route('admin.login');
    }
}
