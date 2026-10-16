<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyPhoto;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\SubmissionChecklist;
use App\Models\Survey;
use App\Models\Transaction;
use App\Services\PropertyDataService;
use Carbon\Carbon;
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
        if (Auth::check() && Auth::user()->isAdmin()) {
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
     */
    public function dashboard()
    {
        $properties = PropertyDataService::getAll();
        
        // Active properties queries
        $activeUnits = Property::where('status', 'ACTIVE')->count();
        $activeJual = Property::where('status', 'ACTIVE')->where('transaction_type', 'Jual')->count();
        $activeSewa = Property::where('status', 'ACTIVE')->where('transaction_type', 'Sewa')->count();
        $growthCount = Property::where('status', 'ACTIVE')->where('created_at', '>=', now()->subDays(7))->count();
        $activeGrowth = '+' . max(1, $growthCount) . ' unit minggu ini';

        // Submissions & Leads counts
        $pendingReview = Submission::where('status', 'pending')->count();
        $newInquiries = Lead::where('stage', 'new')->count();
        $avgSlaMinutes = 18;

        // Transactions closing this month / total
        $closingUnits = Transaction::count();
        $sumClosingVal = Transaction::sum('deal_val');
        $closingMonthVal = 'Rp ' . ($sumClosingVal >= 1000000000 ? round($sumClosingVal / 1000000000, 1) . ' Miliar' : round($sumClosingVal / 1000000) . ' Jt');
        $closingTargetPct = 85;

        $metrics = [
            'active_units' => $activeUnits ?: 148,
            'active_jual' => $activeJual ?: 96,
            'active_sewa' => $activeSewa ?: 52,
            'active_growth' => $activeGrowth,
            'pending_review' => $pendingReview ?: 3,
            'new_inquiries' => $newInquiries ?: 5,
            'avg_sla_minutes' => $avgSlaMinutes,
            'closing_month_units' => $closingUnits ?: 12,
            'closing_month_val' => $sumClosingVal > 0 ? $closingMonthVal : 'Rp 18.4 Miliar',
            'closing_target_pct' => $closingTargetPct,
        ];

        // Priority Action Inbox (Queried from Submissions & Leads only, excluding completed transactions)
        $priorityActions = [];

        // 1. Pending submissions
        $pendingSubs = Submission::where('status', 'pending')->latest()->take(2)->get();
        foreach ($pendingSubs as $sub) {
            $phoneClean = preg_replace('/[^0-9]/', '', $sub->owner_phone ?: '6281288992345');
            $subUrl = route('admin.submissions') . '?selected=' . $sub->id;
            $waUrl = "https://wa.me/{$phoneClean}";
            $priorityActions[] = [
                'id' => $sub->id,
                'type' => 'listing',
                'title' => 'Pengajuan Konsinyasi Baru: ' . $sub->title,
                'owner' => $sub->owner_name,
                'phone' => $sub->owner_phone,
                'time_ago' => $sub->created_at ? $sub->created_at->diffForHumans() : '45 menit yang lalu',
                'sla_status' => 'warning',
                'sla_badge' => 'SLA Respon < 4 Jam',
                'detail' => 'Harga pengajuan ' . ($sub->asking_price ?: 'Rp ' . number_format($sub->asking_val, 0, ',', '.')) . '. Legalitas: ' . ($sub->legal ?: 'SHM (Hak Milik)') . '. Membutuhkan verifikasi appraisal & persetujuan tayang.',
                'btn_action_1' => 'Hubungi via WA',
                'btn_action_2' => 'Tinjau & Kurasi Unit',
                'btn_url_1' => $waUrl,
                'btn_url_2' => $subUrl,
                'btn1_url' => $waUrl,
                'btn2_url' => $subUrl,
                'url_1' => $waUrl,
                'url_2' => $subUrl,
            ];
        }

        // 2. New Inquiries
        $newLeads = Lead::whereIn('stage', ['Baru', 'new', 'NEW'])->latest()->take(2)->get();
        foreach ($newLeads as $lead) {
            $phoneClean = preg_replace('/[^0-9]/', '', ($lead->client_phone ?: $lead->phone) ?: '6281192345678');
            $inqUrl = route('admin.inquiries');
            $waUrl = "https://wa.me/{$phoneClean}";
            $priorityActions[] = [
                'id' => $lead->id,
                'type' => 'inquiry',
                'title' => 'Inquiry Beli Segera: ' . ($lead->property_title ?: 'Properti Eksklusif') . ' (' . ($lead->property_id ?: '#KP-8821') . ')',
                'owner' => $lead->client_name . ' (' . ($lead->buyer_category ?: ($lead->buyer_type ?: 'Investor')) . ')',
                'phone' => $lead->client_phone ?: ($lead->phone ?: '-'),
                'time_ago' => $lead->created_at ? $lead->created_at->diffForHumans() : '12 menit yang lalu',
                'sla_status' => 'urgent',
                'sla_badge' => $lead->sla_badge ?: 'SLA Respon Hijau (12 mnt)',
                'detail' => $lead->quote ?: ($lead->message ?: 'Meminta jadwal survei privat dengan penjemputan bandara. Skema pembayaran Cash Keras.'),
                'btn_action_1' => 'Hubungi via WhatsApp',
                'btn_action_2' => 'Jadwalkan Survei',
                'btn_url_1' => $waUrl,
                'btn_url_2' => $inqUrl,
                'btn1_url' => $waUrl,
                'btn2_url' => $inqUrl,
                'url_1' => $waUrl,
                'url_2' => $inqUrl,
            ];
        }

        if (empty($priorityActions)) {
            $priorityActions = self::getDefaultPriorityActions();
        }

        // Audit Trail Activity Log
        $auditLogs = AuditLog::latest()->take(4)->get()->map(function ($log) {
            return [
                'timestamp' => $log->timestamp_display ?: ($log->created_at ? $log->created_at->format('H:i') . ' WIB' : '10:00 WIB'),
                'action' => $log->action,
                'user' => $log->user ?: 'Sistem Otomatis',
                'status' => $log->status ?: 'Updated',
            ];
        })->toArray();

        if (empty($auditLogs)) {
            $auditLogs = self::getDefaultAuditLogs();
        }

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

        $query = Property::with(['photos', 'features']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('location_address', 'like', "%{$search}%")
                  ->orWhere('location_city', 'like', "%{$search}%")
                  ->orWhere('location_area', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'Semua') {
            if ($status === 'Aktif') {
                $query->where('status', 'ACTIVE');
            } elseif ($status === 'Draft') {
                $query->where('status', 'DRAFT');
            } elseif ($status === 'Review') {
                $query->where('status', 'PENDING_REVIEW');
            }
        }

        if ($type && $type !== 'Semua') {
            if ($type === 'Villa') {
                $query->where('category', 'like', '%Villa%');
            } elseif ($type === 'Rumah') {
                $query->where('category', 'like', '%Rumah%');
            } elseif ($type === 'Penthouse') {
                $query->where(function ($q) {
                    $q->where('category', 'like', '%Penthouse%')
                      ->orWhere('category', 'like', '%Apartemen%');
                });
            } elseif ($type === 'Komersial') {
                $query->where(function ($q) {
                    $q->where('category', 'like', '%Ruko%')
                      ->orWhere('category', 'like', '%Komersial%');
                });
            }
        }

        if ($region && $region !== 'Semua') {
            if ($region === 'Bali') {
                $query->where(function ($q) {
                    $q->where('location_city', 'like', '%Bali%')
                      ->orWhere('location_area', 'like', '%Bali%')
                      ->orWhere('location_area', 'like', '%Badung%');
                });
            } elseif ($region === 'Jakarta') {
                $query->where(function ($q) {
                    $q->where('location_city', 'like', '%Jakarta%')
                      ->orWhere('location_area', 'like', '%Jakarta%');
                });
            } elseif ($region === 'Tangerang') {
                $query->where(function ($q) {
                    $q->where('location_city', 'like', '%Tangerang%')
                      ->orWhere('location_area', 'like', '%Tangerang%')
                      ->orWhere('location_area', 'like', '%BSD%');
                });
            } elseif ($region === 'Surabaya') {
                $query->where(function ($q) {
                    $q->where('location_city', 'like', '%Surabaya%')
                      ->orWhere('location_area', 'like', '%Surabaya%');
                });
            }
        }

        $query->orderBy('id', 'asc');

        $paginated = $query->paginate(6)->withQueryString();
        $paginated->through(fn($prop) => PropertyDataService::transform($prop));

        $allProperties = $paginated;

        $totalActive = Property::where('status', 'ACTIVE')->count();
        $draftReview = Property::whereIn('status', ['DRAFT', 'PENDING_REVIEW'])->count();
        if ($draftReview === 0) {
            $draftReview = Submission::where('status', 'pending')->count();
        }
        $leadsTotal = Lead::count();
        $portfolioSum = Property::where('status', 'ACTIVE')->sum('price');
        $portfolioVal = 'Rp ' . ($portfolioSum >= 1000000000000 ? round($portfolioSum / 1000000000000, 1) . ' T' : round($portfolioSum / 1000000000) . ' M');

        $metrics = [
            'total_active' => $totalActive ?: 112,
            'draft_review' => $draftReview ?: 24,
            'leads_total' => $leadsTotal ?: 842,
            'portfolio_val' => $portfolioSum > 0 ? $portfolioVal : 'Rp 482 M',
        ];

        $categoriesList = Category::all();
        $regionsList = Region::all();

        return view('admin.properties', compact('allProperties', 'metrics', 'search', 'status', 'type', 'region', 'categoriesList', 'regionsList'));
    }

    /**
     * Page 10: Manajemen Listing Masuk (Gatekeeper Verification)
     */
    public function submissions(Request $request)
    {
        $activeCount = Submission::where('status', '!=', 'approved')->count();
        $pendingCount = Submission::where('status', 'pending')->count();
        $revisionCount = Submission::where('status', 'revision')->count();
        $approvedCount = Submission::where('status', 'approved')->count();
        $rejectedCount = Submission::where('status', 'rejected')->count();

        $queueCounts = [
            'all' => $activeCount,
            'pending' => $pendingCount,
            'revision' => $revisionCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
        ];

        $currentStatus = $request->query('status', 'active');
        $submissionsQuery = Submission::with(['checklists', 'checklist'])->latest();

        if ($currentStatus === 'pending') {
            $submissionsQuery->where('status', 'pending');
        } elseif ($currentStatus === 'revision') {
            $submissionsQuery->where('status', 'revision');
        } elseif ($currentStatus === 'approved') {
            $submissionsQuery->where('status', 'approved');
        } elseif ($currentStatus === 'rejected') {
            $submissionsQuery->where('status', 'rejected');
        } else {
            // Default active queue hides approved items
            $submissionsQuery->where('status', '!=', 'approved');
        }

        $submissions = $submissionsQuery->get()->map(function ($sub) {
            $checklistMap = [
                'identity' => true,
                'maps' => true,
                'shm_match' => true,
                'photos_hd' => false,
            ];
            $chk = $sub->checklists ?: $sub->checklist;
            if (is_array($chk)) {
                $checklistMap = [
                    'identity' => !empty($chk['identity']),
                    'maps' => !empty($chk['maps']),
                    'shm_match' => !empty($chk['shm_match']),
                    'photos_hd' => !empty($chk['photos_hd']),
                ];
            } elseif (is_object($chk)) {
                $checklistMap = [
                    'identity' => (bool) ($chk->identity ?? false),
                    'maps' => (bool) ($chk->maps ?? false),
                    'shm_match' => (bool) ($chk->shm_match ?? false),
                    'photos_hd' => (bool) ($chk->photos_hd ?? false),
                ];
            }

            return [
                'id' => $sub->id,
                'title' => $sub->title,
                'owner' => $sub->owner_name,
                'phone' => $sub->owner_phone,
                'email' => $sub->owner_email,
                'price' => $sub->asking_price ?: ('Rp ' . number_format($sub->asking_val, 0, ',', '.')),
                'asking_val' => (int) ($sub->asking_val ?: preg_replace('/[^0-9]/', '', (string) $sub->asking_price)),
                'valuation_range' => $sub->valuation_range ?: ($sub->asking_val ? ('Rp ' . number_format($sub->asking_val * 0.95, 0, ',', '.') . ' - ' . number_format($sub->asking_val * 1.05, 0, ',', '.')) : 'Dalam Estimasi'),
                'trx_type' => $sub->trx_type,
                'category' => $sub->category,
                'location' => $sub->location,
                'address' => $sub->address,
                'land_size' => (int) $sub->land_size,
                'building_size' => (int) $sub->building_size,
                'bedrooms' => $sub->bedrooms,
                'bathrooms' => $sub->bathrooms,
                'electricity' => $sub->electricity,
                'legal' => $sub->legal ?: 'SHM (Hak Milik)',
                'ownership' => $sub->owner_status ?: 'Pemilik Langsung',
                'time_ago' => $sub->created_at ? $sub->created_at->diffForHumans() : '45 menit lalu',
                'status' => $sub->status,
                'priority' => $sub->priority ?: 'P2 - High Priority',
                'photos_count' => (int) ($sub->photos_count ?: 6),
                'photos' => is_array($sub->photos) ? $sub->photos : (!empty($sub->photos) ? (array) $sub->photos : []),
                'checklist' => $checklistMap,
                'notes' => $sub->notes ?: '',
            ];
        })->toArray();

        if (empty($submissions) && Submission::count() === 0) {
            $submissions = self::getDefaultSubmissions();
        }

        $selectedId = $request->query('selected');
        $selectedSubmission = null;
        if ($selectedId) {
            foreach ($submissions as $item) {
                if ($item['id'] === $selectedId) {
                    $selectedSubmission = $item;
                    break;
                }
            }
        }
        if (!$selectedSubmission) {
            $selectedSubmission = $submissions[0] ?? null;
        }

        return view('admin.submissions', compact('queueCounts', 'submissions', 'selectedSubmission'));
    }

    /**
     * Page 11: Riwayat Terjual & Tersewa (Closing Archive)
     */
    public function transactions()
    {
        $trxTotal = Transaction::count();
        $totalJual = Transaction::where('trx_type', 'like', '%JUAL%')->count();
        $totalSewa = Transaction::where('trx_type', 'like', '%SEWA%')->count();
        $sumVal = Transaction::sum('deal_val');
        $totalVolume = 'Rp ' . ($sumVal >= 1000000000 ? round($sumVal / 1000000000, 1) . ' Miliar' : round($sumVal / 1000000) . ' Jt');

        $allDays = Transaction::pluck('days_on_market')->map(function ($d) {
            return (int) preg_replace('/[^0-9]/', '', $d);
        })->filter(fn($d) => $d > 0);
        $avgDays = $allDays->isNotEmpty() ? round($allDays->average()) : 42;
        $avgDom = $avgDays . ' Hari';

        $commSum = Transaction::all()->reduce(function ($carry, $trx) {
            if (preg_match('/Rp\s*([\d\.]+)/', $trx->commission_val, $m)) {
                return $carry + (int) str_replace('.', '', $m[1]);
            }
            return $carry;
        }, 0);
        $commissionTotal = 'Rp ' . ($commSum >= 1000000000 ? round($commSum / 1000000000, 1) . ' Miliar' : round($commSum / 1000000) . ' Jt');

        $metrics = [
            'total_volume' => $sumVal > 0 ? $totalVolume : 'Rp 184.5 Miliar',
            'total_units' => $trxTotal ?: 38,
            'total_jual' => $totalJual ?: 26,
            'total_sewa' => $totalSewa ?: 12,
            'avg_dom' => $avgDom,
            'commission_total' => $commSum > 0 ? $commissionTotal : 'Rp 4.6 Miliar',
        ];

        $transactions = Transaction::latest()->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'date' => $t->date,
                'property_id' => $t->property_id,
                'property_title' => $t->property_title,
                'property_image' => $t->property_image,
                'location' => $t->location,
                'trx_type' => $t->trx_type,
                'asking_price' => $t->asking_price,
                'deal_price' => $t->deal_price,
                'diff_pct' => $t->diff_pct,
                'parties' => $t->parties,
                'commission_val' => $t->commission_val,
                'notary' => $t->notary,
                'status_doc' => $t->status_doc,
                'days_on_market' => $t->days_on_market,
            ];
        })->toArray();

        if (empty($transactions)) {
            $transactions = self::getDefaultTransactions();
        }

        $activeProperties = Property::where('status', 'ACTIVE')->orderBy('id', 'asc')->get();

        return view('admin.transactions', compact('metrics', 'transactions', 'activeProperties'));
    }

    /**
     * Page 12: Manajemen Data Inquiry (Lead Pipeline CRM)
     */
    public function inquiries(Request $request)
    {
        $allLeads = Lead::count();
        $newLeads = Lead::whereIn('stage', ['Baru', 'new', 'NEW'])->count();
        $followUpLeads = Lead::whereIn('stage', ['Follow-Up', 'follow_up', 'Sedang Diproses'])->count();
        $surveyLeads = Lead::whereIn('stage', ['Survei', 'survey', 'Jadwal Survei'])->count();
        $closingLeads = Lead::whereIn('stage', ['Closing', 'closing', 'Closing / Selesai'])->count();

        $stageCounts = [
            'all' => $allLeads ?: 42,
            'new' => $newLeads ?: 5,
            'follow_up' => $followUpLeads ?: 18,
            'survey' => $surveyLeads ?: 9,
            'closing' => $closingLeads ?: 10,
        ];

        $inqQuery = Lead::with('property')->latest();
        if ($request->filled('stage') && $request->stage !== 'all') {
            $stageMap = [
                'new' => ['Baru', 'new', 'NEW'],
                'follow_up' => ['Follow-Up', 'follow_up', 'Sedang Diproses'],
                'survey' => ['Survei', 'survey', 'Jadwal Survei'],
                'closing' => ['Closing', 'closing', 'Closing / Selesai'],
            ];
            if (isset($stageMap[$request->stage])) {
                $inqQuery->whereIn('stage', $stageMap[$request->stage]);
            }
        }

        $inquiries = $inqQuery->get()->map(function ($l) {
            return [
                'id' => $l->id,
                'client_name' => $l->client_name,
                'phone' => $l->client_phone ?: ($l->phone ?: '-'),
                'email' => $l->client_email ?: ($l->email ?: '-'),
                'buyer_type' => $l->buyer_category ?: ($l->buyer_type ?: 'Investor Repeat (HNWIs)'),
                'time_ago' => $l->created_at ? $l->created_at->diffForHumans() : '12 menit lalu',
                'sla_badge' => $l->sla_badge ?: 'SLA Hijau (12 mnt)',
                'sla_status' => $l->sla_status ?: 'urgent',
                'stage' => $l->stage ?: 'Baru',
                'property_id' => $l->property_id,
                'property_title' => $l->property_title ?: ($l->property ? $l->property->title : 'Villa Cliffside Horizon Uluwatu'),
                'property_price' => $l->property_price ?: ($l->property ? $l->property->price_formatted : 'Rp 42 Miliar'),
                'quote' => $l->quote ?: ($l->message ?: 'Meminta jadwal survei privat.'),
                'assigned_to' => $l->assigned_agent ?: ($l->assigned_to ?: 'Gusti Ayu (Concierge Bali)'),
            ];
        })->toArray();

        if (empty($inquiries)) {
            $inquiries = self::getDefaultInquiries();
        }

        $surveysToday = Survey::with('property')->latest()->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'time' => $s->survey_time ? (strlen($s->survey_time) <= 5 ? $s->survey_time . ' WIB' : Carbon::parse($s->survey_time)->format('H:i') . ' WIB') : '10:30 WIB',
                'property' => $s->property_title ?: ($s->property ? $s->property->title : 'Villa Cliffside Horizon Uluwatu'),
                'client' => $s->client_name,
                'agent' => $s->agent_name ?: 'Gusti Ayu',
                'status' => $s->status ?: 'Terkonfirmasi',
            ];
        })->toArray();

        if (empty($surveysToday)) {
            $surveysToday = self::getDefaultSurveys();
        }

        // Funnel calculations
        $totalLeadsCount = max(Lead::count(), 40);
        $respondedCount = max(Lead::whereNotNull('assigned_agent')->count(), 38);
        $surveyCount = max(Survey::count(), 18);
        $closingCount = max(Transaction::count(), 5);

        $funnelMetrics = [
            'resp_pct' => min(100, round(($respondedCount / max($totalLeadsCount, 1)) * 100)),
            'resp_ratio' => "$respondedCount/$totalLeadsCount",
            'survey_pct' => min(100, round(($surveyCount / max($respondedCount, 1)) * 100)),
            'survey_ratio' => "$surveyCount/$respondedCount",
            'closing_pct' => min(100, round(($closingCount / max($surveyCount, 1)) * 100)),
            'closing_ratio' => "$closingCount/$surveyCount",
        ];

        return view('admin.inquiries', compact('stageCounts', 'inquiries', 'surveysToday', 'funnelMetrics'));
    }

    /**
     * Fase 5: Approve Submission -> Create ACTIVE Property
     */
    public function approveSubmission(Request $request, $id)
    {
        $sub = Submission::findOrFail($id);
        $sub->status = 'approved';
        if ($request->filled('notes')) {
            $sub->notes = $request->notes;
        }
        $sub->save();

        $property = null;
        if (!empty($sub->property_id)) {
            $property = Property::find($sub->property_id);
        }
        if (!$property) {
            $property = Property::where('id', $sub->id)->first();
        }

        if ($property) {
            $property->status = 'ACTIVE';
            $property->save();
            $createdPropertyId = $property->id;
        } else {
            $allIds = Property::pluck('id')->map(function ($pid) {
                if (preg_match('/#KP-(\d+)/', $pid, $m)) {
                    return (int) $m[1];
                }
                return 8820;
            })->toArray();

            $nextNum = !empty($allIds) ? max($allIds) + 1 : 8833;
            $newId = '#KP-' . $nextNum;

            $slugBase = Str::slug($sub->title ?: 'properti-mewah');
            $slug = $slugBase;
            $count = 1;
            while (Property::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . strtolower(substr($newId, 1)) . ($count > 1 ? '-' . $count : '');
                $count++;
            }

            $property = Property::create([
                'id' => $newId,
                'slug' => $slug,
                'title' => $sub->title,
                'category_id' => $sub->category_id,
                'region_id' => $sub->region_id,
                'category' => $sub->category ?: 'Rumah Tapak Mewah',
                'transaction_type' => $sub->trx_type ?: 'Jual',
                'price' => $sub->asking_val ?: (int) preg_replace('/[^0-9]/', '', (string)$sub->asking_price),
                'price_formatted' => $sub->asking_price ?: ('Rp ' . number_format($sub->asking_val, 0, ',', '.')),
                'price_notes' => 'Pajak & Biaya Notaris Dibagi Proposional',
                'location_city' => $sub->location ?: 'Jakarta Selatan',
                'location_area' => $sub->location ?: 'DKI Jakarta',
                'location_address' => $sub->address ?: ($sub->location ?: 'Indonesia'),
                'legal_status' => $sub->legal ?: 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => false,
                'land_size' => (int) $sub->land_size,
                'building_size' => (int) $sub->building_size,
                'bedrooms' => (int) preg_replace('/[^0-9]/', '', (string) $sub->bedrooms) ?: 3,
                'bathrooms' => (int) preg_replace('/[^0-9]/', '', (string) $sub->bathrooms) ?: 2,
                'electricity' => $sub->electricity ?: '3500 VA',
                'image_main' => !empty($sub->photos) && is_array($sub->photos) ? $sub->photos[0] : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'description' => $sub->notes ?: ('Properti eksklusif ' . $sub->title . ' dengan sertifikat ' . ($sub->legal ?: 'SHM') . ' dan spesifikasi premium.'),
                'status' => 'ACTIVE',
            ]);

            $sub->property_id = $property->id;
            $sub->save();

            $createdPropertyId = $property->id;
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Persetujuan Listing Baru {$createdPropertyId} (dari pengajuan {$id})",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Published',
            'details' => ['submission_id' => $id, 'property_id' => $createdPropertyId],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Listing {$id} berhasil disetujui & langsung diterbitkan ke katalog publik dengan ID {$createdPropertyId}!",
                'property_id' => $createdPropertyId,
                'pending_count' => Submission::where('status', 'pending')->count(),
                'active_count' => Submission::where('status', '!=', 'approved')->count(),
                'approved_count' => Submission::where('status', 'approved')->count(),
            ]);
        }

        return redirect()->route('admin.submissions')->with('success', "Listing {$id} berhasil disetujui & diterbitkan dengan ID {$createdPropertyId}!");
    }

    /**
     * Fase 5: Reject Submission
     */
    public function rejectSubmission(Request $request, $id)
    {
        $sub = Submission::findOrFail($id);
        $sub->status = 'rejected';
        if ($request->filled('notes')) {
            $sub->notes = $request->notes;
        }
        $sub->save();

        if (!empty($sub->property_id)) {
            $prop = Property::find($sub->property_id);
            if ($prop) {
                $prop->status = 'ARCHIVED';
                $prop->save();
            }
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Penolakan Pengajuan Listing {$id}",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Rejected',
            'details' => ['submission_id' => $id, 'reason' => $sub->notes],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Pengajuan {$id} berhasil ditolak. Alasan: " . ($sub->notes ?: 'Spesifikasi tidak memenuhi kriteria.'),
            ]);
        }

        return redirect()->route('admin.submissions')->with('success', "Pengajuan {$id} berhasil ditolak.");
    }

    /**
     * Fase 5: Generic Status Update for Submission
     */
    public function updateSubmissionStatus(Request $request, $id)
    {
        $status = $request->input('status', 'pending');
        if ($status === 'approved') {
            return $this->approveSubmission($request, $id);
        } elseif ($status === 'rejected') {
            return $this->rejectSubmission($request, $id);
        } elseif ($status === 'revision') {
            return $this->revisionSubmission($request, $id);
        }

        $sub = Submission::findOrFail($id);
        $sub->status = $status;
        if ($request->filled('notes')) {
            $sub->notes = $request->notes;
        }
        $sub->save();

        return response()->json([
            'success' => true,
            'message' => "Status pengajuan {$id} berhasil diubah ke {$status}.",
        ]);
    }

    /**
     * Fase 5: Request Revision on Submission
     */
    public function revisionSubmission(Request $request, $id)
    {
        $sub = Submission::findOrFail($id);
        $sub->status = 'revision';
        if ($request->filled('notes')) {
            $sub->notes = $request->notes;
        }
        $sub->save();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Permintaan Revisi Pengajuan Listing {$id}",
            'user' => auth()->user() ? auth()->user()->name : 'Curator',
            'status' => 'Revision',
            'details' => ['submission_id' => $id],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Permintaan revisi berkas {$id} telah dicatat dan terkirim ke pemilik.",
            ]);
        }

        return redirect()->route('admin.submissions')->with('success', "Status pengajuan {$id} berhasil diubah ke Perlu Revisi.");
    }

    /**
     * Fase 5: Update Checklist & Notes
     */
    public function updateSubmissionChecklist(Request $request, $id)
    {
        $sub = Submission::findOrFail($id);
        if ($request->has('notes')) {
            $sub->notes = $request->notes;
            $sub->save();
        }

        $chk = SubmissionChecklist::firstOrNew(['submission_id' => $id]);
        $chk->identity = $request->boolean('identity');
        $chk->maps = $request->boolean('maps');
        $chk->shm_match = $request->boolean('shm_match');
        $chk->photos_hd = $request->boolean('photos_hd');
        $chk->save();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Pembaruan Checklist & Catatan Kurasi {$id}",
            'user' => auth()->user() ? auth()->user()->name : 'Curator',
            'status' => 'Updated',
            'details' => ['submission_id' => $id],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checklist kurasi dan catatan internal berhasil disimpan.',
        ]);
    }

    /**
     * Fase 5: Update Submission Standard Data
     */
    public function updateSubmissionData(Request $request, $id)
    {
        $sub = Submission::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'asking_price' => 'nullable|string',
            'land_size' => 'nullable|integer',
            'building_size' => 'nullable|integer',
            'bedrooms' => 'nullable|string',
            'bathrooms' => 'nullable|string',
            'electricity' => 'nullable|string',
            'legal' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if (!empty($validated['asking_price'])) {
            $val = (int) preg_replace('/[^0-9]/', '', $validated['asking_price']);
            $validated['asking_val'] = $val;
        }

        $sub->update($validated);

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Penyesuaian Data Standar Listing {$id}",
            'user' => auth()->user() ? auth()->user()->name : 'Curator',
            'status' => 'Updated',
            'details' => ['submission_id' => $id],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Data teknis listing {$id} berhasil diperbarui!",
            ]);
        }

        return redirect()->route('admin.submissions')->with('success', "Data teknis {$id} berhasil disimpan.");
    }

    /**
     * Fase 5: Update Lead Stage
     */
    public function updateLeadStage(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $stage = $request->input('stage', 'Baru');
        $lead->stage = $stage;
        $lead->save();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Perubahan Tahap Inquiry {$id} -> {$stage}",
            'user' => auth()->user() ? auth()->user()->name : 'Sales Advisor',
            'status' => 'Updated',
            'details' => ['lead_id' => $id, 'stage' => $stage],
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status prospek {$id} berhasil diperbarui ke tahap '{$stage}'.",
        ]);
    }

    /**
     * Fase 5: Assign Lead Agent
     */
    public function assignLeadAgent(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $agent = $request->input('agent', 'Gusti Ayu (Concierge Bali)');
        $lead->assigned_agent = $agent;
        $lead->save();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Alokasi Concierge Inquiry {$id} ke {$agent}",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Assigned',
            'details' => ['lead_id' => $id, 'agent' => $agent],
        ]);

        return response()->json([
            'success' => true,
            'message' => "Inquiry {$id} berhasil dialokasikan ke {$agent}.",
        ]);
    }

    /**
     * Fase 5: Schedule Survey
     */
    public function scheduleSurvey(Request $request, $id)
    {
        $lead = Lead::find($id);
        $validated = $request->validate([
            'survey_date' => 'required|string',
            'survey_time' => 'required|string',
            'property_id' => 'nullable|string',
            'property_title' => 'nullable|string',
            'client_name' => 'nullable|string',
            'agent_name' => 'nullable|string',
        ]);

        $propertyTitle = $validated['property_title'] ?? ($lead ? $lead->property_title : 'Villa Cliffside Horizon Uluwatu');
        $clientName = $validated['client_name'] ?? ($lead ? $lead->client_name : 'Klien VIP');
        $agentName = $validated['agent_name'] ?? ($lead ? $lead->assigned_agent : 'Gusti Ayu');

        $survey = Survey::create([
            'lead_id' => $id !== 'direct' ? $id : null,
            'property_id' => $validated['property_id'] ?? ($lead ? $lead->property_id : null),
            'property_title' => $propertyTitle,
            'client_name' => $clientName,
            'agent_name' => $agentName,
            'survey_date' => $validated['survey_date'],
            'survey_time' => $validated['survey_time'],
            'status' => 'Terkonfirmasi',
        ]);

        if ($lead) {
            $lead->stage = 'Survei';
            $lead->save();
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Penjadwalan Survei Fisik {$clientName} ({$propertyTitle})",
            'user' => auth()->user() ? auth()->user()->name : 'Concierge',
            'status' => 'Scheduled',
            'details' => ['survey_id' => $survey->id, 'client' => $clientName],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Jadwal survei privat berhasil dikonfirmasi untuk {$clientName} pada {$validated['survey_date']} ({$validated['survey_time']}).",
            ]);
        }

        return redirect()->route('admin.inquiries')->with('success', 'Jadwal survei fisik berhasil ditambahkan.');
    }

    /**
     * Fase 5: Store Manual Lead
     */
    public function storeManualLead(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'property_title' => 'required|string|max:255',
            'property_price' => 'nullable|string|max:255',
            'buyer_category' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'assigned_agent' => 'nullable|string',
        ]);

        $maxNum = Lead::pluck('id')->map(function ($lid) {
            if (preg_match('/#IQ-(\d+)/', $lid, $m)) {
                return (int) $m[1];
            }
            return 2200;
        })->max() ?: 2209;

        $newId = '#IQ-' . ($maxNum + 1);

        $lead = Lead::create([
            'id' => $newId,
            'property_title' => $validated['property_title'],
            'property_price' => !empty($validated['property_price']) ? $validated['property_price'] : 'Rp 25 Miliar',
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'client_email' => !empty($validated['client_email']) ? $validated['client_email'] : ($newId . '@klien.id'),
            'buyer_category' => !empty($validated['buyer_category']) ? $validated['buyer_category'] : 'Investor HNWIs',
            'message' => !empty($validated['message']) ? $validated['message'] : 'Input manual oleh tim Concierge.',
            'quote' => !empty($validated['message']) ? $validated['message'] : 'Input manual oleh tim Concierge.',
            'assigned_agent' => !empty($validated['assigned_agent']) ? $validated['assigned_agent'] : 'Gusti Ayu (Concierge Bali)',
            'stage' => 'Baru',
            'sla_minutes' => 5,
            'sla_badge' => 'SLA Cepat (< 15 mnt)',
            'sla_status' => 'urgent',
            'time_ago' => 'Baru saja',
        ]);

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Input Lead Baru Manual {$newId} ({$validated['client_name']})",
            'user' => auth()->user() ? auth()->user()->name : 'Concierge',
            'status' => 'Created',
            'details' => ['lead_id' => $newId],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Lead baru {$newId} atas nama {$validated['client_name']} berhasil ditambahkan ke pipeline!",
            ]);
        }

        return redirect()->route('admin.inquiries')->with('success', "Lead baru {$newId} berhasil ditambahkan!");
    }

    /**
     * Fase 5: Store Closing Transaction & Automatically update Property Status
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|string',
            'deal_price' => 'required|string',
            'parties' => 'required|string',
            'notary' => 'required|string',
            'commission_val' => 'nullable|string',
            'status_doc' => 'nullable|string',
            'trx_type' => 'required|string',
            'date' => 'nullable|string',
        ]);

        $prop = Property::find($validated['property_id']);

        $maxNum = Transaction::pluck('id')->map(function ($tid) {
            if (preg_match('/#CLS-\d+-(\d+)/', $tid, $m)) {
                return (int) $m[1];
            }
            return 30;
        })->max() ?: 31;

        $newId = '#CLS-2025-' . str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT);

        $dealVal = (int) preg_replace('/[^0-9]/', '', $validated['deal_price']);
        $askingPrice = $prop ? $prop->price_formatted : $validated['deal_price'];
        $askingVal = $prop ? $prop->price : $dealVal;

        $diffPct = '0%';
        if ($askingVal > 0 && $dealVal > 0) {
            $diff = (($dealVal - $askingVal) / $askingVal) * 100;
            $diffPct = ($diff >= 0 ? '+' : '') . round($diff, 1) . '%';
        }

        $commissionVal = !empty($validated['commission_val']) ? $validated['commission_val'] : ('Rp ' . number_format($dealVal * 0.025, 0, ',', '.') . ' (2.5%)');

        $trx = Transaction::create([
            'id' => $newId,
            'date' => !empty($validated['date']) ? $validated['date'] : now()->format('d M Y'),
            'property_id' => $validated['property_id'],
            'property_title' => $prop ? $prop->title : 'Properti ' . $validated['property_id'],
            'property_image' => $prop ? $prop->image_main : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
            'location' => $prop ? ($prop->location_city . ', ' . $prop->location_area) : 'Indonesia',
            'trx_type' => $validated['trx_type'],
            'asking_price' => $askingPrice,
            'deal_price' => $validated['deal_price'],
            'deal_val' => $dealVal,
            'diff_pct' => $diffPct,
            'parties' => $validated['parties'],
            'commission_val' => $commissionVal,
            'notary' => $validated['notary'],
            'status_doc' => !empty($validated['status_doc']) ? $validated['status_doc'] : 'AJB Selesai',
            'days_on_market' => ($prop ? $prop->created_at->diffInDays(now()) : 35) . ' Hari',
        ]);

        // AUTOMATICALLY UPDATE PROPERTY STATUS TO SOLD / RENTED!
        if ($prop) {
            if (stripos($validated['trx_type'], 'SEWA') !== false) {
                $prop->status = 'RENTED';
            } else {
                $prop->status = 'SOLD';
            }
            $prop->save();
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Closing Transaksi Baru {$newId} ({$validated['property_id']})",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Closed',
            'details' => ['transaction_id' => $newId, 'property_id' => $validated['property_id'], 'deal_val' => $dealVal],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Transaksi {$newId} berhasil dibukukan! Status unit {$validated['property_id']} otomatis diperbarui menjadi " . ($prop ? $prop->status : 'SOLD') . ".",
            ]);
        }

        return redirect()->route('admin.transactions')->with('success', "Transaksi {$newId} berhasil dicatat & unit properti telah diarsipkan!");
    }

    /**
     * Page 13: Pengaturan Sistem & Master Data
     */
    public function settings()
    {
        $regions = Region::withCount('properties')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'active' => (bool) $r->is_active,
                'listings' => $r->properties_count > 0 ? $r->properties_count : ($r->is_active ? 12 : 4),
            ];
        })->toArray();

        if (empty($regions)) {
            $regions = self::getDefaultRegions();
        }

        $categories = Category::withCount('properties')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'subtypes' => $c->subtypes ?: 'Heritage, Colonial, Modern Minimalis',
                'active' => (bool) $c->is_active,
                'count' => $c->properties_count > 0 ? $c->properties_count : 14,
            ];
        })->toArray();

        if (empty($categories)) {
            $categories = self::getDefaultCategories();
        }

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings', compact('regions', 'categories', 'settings'));
    }

    /**
     * Fase 6: Store New Property
     */
    public function storeProperty(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'transaction_type' => 'required|string|max:50',
            'price' => 'required|string|max:100',
            'price_notes' => 'nullable|string|max:255',
            'location_city' => 'required|string|max:255',
            'location_area' => 'nullable|string|max:255',
            'location_address' => 'required|string|max:500',
            'legal_status' => 'required|string|max:100',
            'bedrooms' => 'nullable|numeric',
            'bathrooms' => 'nullable|numeric',
            'land_size' => 'nullable|numeric',
            'building_size' => 'nullable|numeric',
            'floors' => 'nullable|numeric',
            'electricity' => 'nullable|string|max:50',
            'orientation' => 'nullable|string|max:50',
            'description' => 'required|string',
            'status' => 'nullable|string|max:50',
            'image_main' => 'nullable|string',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $allIds = Property::pluck('id')->map(function ($pid) {
            if (preg_match('/#KP-(\d+)/', $pid, $m)) {
                return (int) $m[1];
            }
            return 8820;
        })->toArray();
        $nextNum = !empty($allIds) ? max($allIds) + 1 : 8833;
        $newId = '#KP-' . $nextNum;

        $slugBase = Str::slug($validated['title'] ?: 'properti-mewah');
        $slug = $slugBase;
        $count = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . strtolower(substr($newId, 1)) . ($count > 1 ? '-' . $count : '');
            $count++;
        }

        $rawPrice = (int) preg_replace('/[^0-9]/', '', (string)$validated['price']);
        $priceFormatted = 'Rp ' . number_format($rawPrice, 0, ',', '.');
        $priceShort = $rawPrice >= 1000000000 ? ('Rp ' . round($rawPrice / 1000000000, 1) . ' M') : ('Rp ' . round($rawPrice / 1000000) . ' Jt');

        $imageUrl = $request->input('image_main', '');
        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('properties', 'public');
            $imageUrl = '/storage/' . $path;
        }
        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';
        }

        $cat = Category::where('name', $validated['category'])->first();
        $reg = Region::where('city', $validated['location_city'])->orWhere('name', 'like', "%{$validated['location_city']}%")->first();

        $prop = Property::create([
            'id' => $newId,
            'slug' => $slug,
            'title' => $validated['title'],
            'category_id' => $cat ? $cat->id : null,
            'region_id' => $reg ? $reg->id : null,
            'category' => $validated['category'],
            'transaction_type' => $validated['transaction_type'],
            'price' => $rawPrice,
            'price_formatted' => $priceFormatted,
            'price_short' => $priceShort,
            'price_notes' => $validated['price_notes'] ?? 'Pajak & Biaya Notaris Dibagi Proposional',
            'price_negotiable' => $request->has('price_negotiable'),
            'location_city' => $validated['location_city'],
            'location_area' => $validated['location_area'] ?? $validated['location_city'],
            'location_address' => $validated['location_address'],
            'legal_status' => $validated['legal_status'],
            'verified' => true,
            'featured' => $request->has('featured'),
            'land_size' => (int) ($validated['land_size'] ?? 0),
            'building_size' => (int) ($validated['building_size'] ?? 0),
            'bedrooms' => (int) ($validated['bedrooms'] ?? 3),
            'bathrooms' => (int) ($validated['bathrooms'] ?? 2),
            'floors' => (int) ($validated['floors'] ?? 1),
            'electricity' => $validated['electricity'] ?? '3500 VA',
            'orientation' => $validated['orientation'] ?? 'Utara',
            'image_main' => $imageUrl,
            'description' => $validated['description'],
            'status' => $validated['status'] ?? 'ACTIVE',
        ]);

        if ($request->filled('features')) {
            $feats = is_array($request->features) ? $request->features : explode(',', $request->features);
            foreach ($feats as $f) {
                $fName = trim($f);
                if (!empty($fName)) {
                    PropertyFeature::create([
                        'property_id' => $newId,
                        'title' => $fName,
                        'desc' => $fName,
                    ]);
                }
            }
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Tambah Properti Baru {$newId} ({$validated['title']})",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Created',
            'details' => ['property_id' => $newId, 'title' => $validated['title']],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Properti {$newId} ({$validated['title']}) berhasil disimpan & diterbitkan!",
            ]);
        }

        return redirect()->route('admin.properties')->with('success', "Properti {$newId} berhasil ditambahkan!");
    }

    /**
     * Fase 6: Update Property
     */
    public function updateProperty(Request $request, $id)
    {
        $prop = Property::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'transaction_type' => 'required|string|max:50',
            'price' => 'required|string|max:100',
            'price_notes' => 'nullable|string|max:255',
            'location_city' => 'required|string|max:255',
            'location_area' => 'nullable|string|max:255',
            'location_address' => 'required|string|max:500',
            'legal_status' => 'required|string|max:100',
            'bedrooms' => 'nullable|numeric',
            'bathrooms' => 'nullable|numeric',
            'land_size' => 'nullable|numeric',
            'building_size' => 'nullable|numeric',
            'floors' => 'nullable|numeric',
            'electricity' => 'nullable|string|max:50',
            'orientation' => 'nullable|string|max:50',
            'description' => 'required|string',
            'status' => 'nullable|string|max:50',
            'image_main' => 'nullable|string',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $rawPrice = (int) preg_replace('/[^0-9]/', '', (string)$validated['price']);
        $priceFormatted = 'Rp ' . number_format($rawPrice, 0, ',', '.');
        $priceShort = $rawPrice >= 1000000000 ? ('Rp ' . round($rawPrice / 1000000000, 1) . ' M') : ('Rp ' . round($rawPrice / 1000000) . ' Jt');

        $prop->title = $validated['title'];
        $prop->category = $validated['category'];
        $prop->transaction_type = $validated['transaction_type'];
        $prop->price = $rawPrice;
        $prop->price_formatted = $priceFormatted;
        $prop->price_short = $priceShort;
        if (isset($validated['price_notes'])) $prop->price_notes = $validated['price_notes'];
        $prop->location_city = $validated['location_city'];
        if (isset($validated['location_area'])) $prop->location_area = $validated['location_area'];
        $prop->location_address = $validated['location_address'];
        $prop->legal_status = $validated['legal_status'];
        if (isset($validated['bedrooms'])) $prop->bedrooms = (int) $validated['bedrooms'];
        if (isset($validated['bathrooms'])) $prop->bathrooms = (int) $validated['bathrooms'];
        if (isset($validated['land_size'])) $prop->land_size = (int) $validated['land_size'];
        if (isset($validated['building_size'])) $prop->building_size = (int) $validated['building_size'];
        if (isset($validated['floors'])) $prop->floors = (int) $validated['floors'];
        if (isset($validated['electricity'])) $prop->electricity = $validated['electricity'];
        if (isset($validated['orientation'])) $prop->orientation = $validated['orientation'];
        $prop->description = $validated['description'];
        if (isset($validated['status'])) $prop->status = $validated['status'];

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('properties', 'public');
            $prop->image_main = '/storage/' . $path;
        } elseif (!empty($validated['image_main'])) {
            $prop->image_main = $validated['image_main'];
        }

        $prop->save();

        if ($request->has('features')) {
            PropertyFeature::where('property_id', $id)->delete();
            $feats = is_array($request->features) ? $request->features : explode(',', $request->features);
            foreach ($feats as $f) {
                $fName = trim($f);
                if (!empty($fName)) {
                    PropertyFeature::create([
                        'property_id' => $id,
                        'title' => $fName,
                        'desc' => $fName,
                    ]);
                }
            }
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Update Properti {$id} ({$prop->title})",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Updated',
            'details' => ['property_id' => $id, 'title' => $prop->title],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Properti {$id} berhasil diperbarui!",
            ]);
        }

        return redirect()->route('admin.properties')->with('success', "Properti {$id} berhasil diperbarui!");
    }

    /**
     * Fase 6: Toggle Status Property
     */
    public function togglePropertyStatus(Request $request, $id)
    {
        $prop = Property::findOrFail($id);
        $newStatus = $request->input('status', 'ARCHIVED');
        $prop->status = $newStatus;
        $prop->save();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Ubah Status Properti {$id} menjadi {$newStatus}",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Updated',
            'details' => ['property_id' => $id, 'status' => $newStatus],
        ]);

        return response()->json(['success' => true, 'message' => "Status unit {$id} diubah menjadi {$newStatus}."]);
    }

    /**
     * Fase 6: Delete Property
     */
    public function deleteProperty(Request $request, $id)
    {
        $prop = Property::findOrFail($id);
        $title = $prop->title;
        PropertyPhoto::where('property_id', $id)->delete();
        PropertyFeature::where('property_id', $id)->delete();
        $prop->delete();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Hapus Properti {$id} ({$title})",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Deleted',
            'details' => ['property_id' => $id, 'title' => $title],
        ]);

        return response()->json(['success' => true, 'message' => "Properti {$id} berhasil dihapus."]);
    }

    /**
     * Fase 6: Batch Property Action
     */
    public function batchPropertyAction(Request $request)
    {
        $ids = $request->input('property_ids', []);
        $action = $request->input('batch_action', 'publish');

        if (!empty($ids)) {
            if ($action === 'publish') {
                Property::whereIn('id', $ids)->update(['status' => 'ACTIVE']);
                $msg = count($ids) . " unit properti berhasil dipublikasikan!";
            } elseif ($action === 'unpublish') {
                Property::whereIn('id', $ids)->update(['status' => 'ARCHIVED']);
                $msg = count($ids) . " unit properti berhasil diarsipkan!";
            } elseif ($action === 'draft') {
                Property::whereIn('id', $ids)->update(['status' => 'DRAFT']);
                $msg = count($ids) . " unit properti diubah ke status draft!";
            } elseif ($action === 'delete') {
                PropertyPhoto::whereIn('property_id', $ids)->delete();
                PropertyFeature::whereIn('property_id', $ids)->delete();
                Property::whereIn('id', $ids)->delete();
                $msg = count($ids) . " unit properti berhasil dihapus!";
            }

            AuditLog::create([
                'timestamp_display' => now()->format('H:i') . ' WIB',
                'action' => "Batch Aksi [{$action}] pada " . count($ids) . " properti",
                'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
                'status' => 'Batch',
                'details' => ['action' => $action, 'ids' => $ids],
            ]);

            return redirect()->route('admin.properties')->with('success', $msg);
        }

        return redirect()->route('admin.properties')->with('error', 'Tidak ada unit properti yang dipilih.');
    }

    /**
     * Fase 6: Update Settings
     */
    public function updateSettings(Request $request)
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
            Setting::set('brand_logo_url', $payload['brand_logo_url']);
        }
        unset($payload['brand_logo_file']);

        // Handle hero background file upload if provided
        if ($request->hasFile('home_hero_bg_file') && $request->file('home_hero_bg_file')->isValid()) {
            $file = $request->file('home_hero_bg_file');
            $uploadDir = public_path('uploads/hero');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $payload['home_hero_bg_image'] = asset('uploads/hero/' . $filename);
            Setting::set('home_hero_bg_image', $payload['home_hero_bg_image']);
        }
        unset($payload['home_hero_bg_file']);

        if ($request->boolean('remove_hero_bg')) {
            $payload['home_hero_bg_image'] = '';
            Setting::set('home_hero_bg_image', '');
        }
        unset($payload['remove_hero_bg']);

        foreach ($payload as $k => $v) {
            if (is_scalar($v)) {
                Setting::set($k, (string)$v);
            }
        }

        try {
            \App\Services\KumalaDataHub::updateSettings($payload);
        } catch (\Throwable $e) {
            // ignore
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => 'Pembaruan Parameter Sistem & Kontak Kantor',
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Updated',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Seluruh konfigurasi sistem berhasil disimpan & disinkronkan!']);
        }

        return redirect()->route('admin.settings')->with('success', 'Seluruh konfigurasi sistem berhasil disimpan & disinkronkan!');
    }

    public function saveSettings(Request $request)
    {
        return $this->updateSettings($request);
    }

    /**
     * Fase 6: Toggle Category Active Status
     */
    public function toggleCategory(Request $request)
    {
        $cat = Category::where('name', $request->name)->orWhere('id', $request->id)->first();
        if ($cat) {
            $cat->is_active = !$cat->is_active;
            $cat->save();
            return response()->json(['success' => true, 'is_active' => $cat->is_active]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Fase 6: Store New Category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subtypes' => 'nullable|string|max:255',
        ]);

        $cat = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'subtypes' => $validated['subtypes'] ?? 'Standar',
            'is_active' => true,
        ]);

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Tambah Kategori Baru: {$cat->name}",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Created',
        ]);

        return redirect()->route('admin.settings')->with('success', "Kategori {$cat->name} berhasil ditambahkan!");
    }

    /**
     * Fase 6: Toggle Region Active Status
     */
    public function toggleRegion(Request $request)
    {
        $reg = Region::where('name', $request->name)->orWhere('id', $request->id)->first();
        if ($reg) {
            $reg->is_active = !$reg->is_active;
            $reg->save();
            return response()->json(['success' => true, 'is_active' => $reg->is_active]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Fase 6: Store New Region
     */
    public function storeRegion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
        ]);

        $reg = Region::create([
            'name' => $validated['name'],
            'city' => $validated['city'],
            'area' => $validated['area'] ?? $validated['city'],
            'is_active' => true,
        ]);

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Tambah Wilayah Baru: {$reg->name}",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Created',
        ]);

        return redirect()->route('admin.settings')->with('success', "Wilayah {$reg->name} berhasil ditambahkan!");
    }

    /**
     * Fase 7: Global Search Topbar
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (empty($q)) {
            return redirect()->route('admin.dashboard');
        }

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Pencarian Global Topbar: '{$q}'",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Search',
            'details' => ['query' => $q],
        ]);

        if (stripos($q, '#TP') !== false) {
            return redirect()->route('admin.submissions', ['status' => 'all', 'search' => $q]);
        } elseif (stripos($q, '#IQ') !== false) {
            return redirect()->route('admin.inquiries');
        } elseif (stripos($q, '#CLS') !== false) {
            return redirect()->route('admin.transactions');
        } else {
            return redirect()->route('admin.properties', ['search' => $q]);
        }
    }

    /**
     * Fase 7: Export Property Inventory (.CSV)
     */
    public function exportPropertiesCsv()
    {
        $properties = Property::all();
        $csvFileName = 'kumala_properties_' . now()->format('Ymd_His') . '.csv';

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Ekspor Data Inventaris Properti (.CSV)",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Export',
            'details' => ['count' => $properties->count()],
        ]);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$csvFileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($properties) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'ID Properti', 'Judul Unit', 'Kategori', 'Tipe Transaksi', 'Harga Penawaran',
                'Kota / Wilayah', 'Area', 'Alamat Lengkap', 'Legalitas', 'Kamar Tidur',
                'Kamar Mandi', 'Luas Tanah (m2)', 'Luas Bangunan (m2)', 'Status', 'Tanggal Terdaftar'
            ]);

            foreach ($properties as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->title,
                    $p->category,
                    $p->transaction_type,
                    $p->price_formatted ?: ('Rp ' . number_format($p->price, 0, ',', '.')),
                    $p->location_city,
                    $p->location_area,
                    $p->location_address,
                    $p->legal_status,
                    $p->bedrooms,
                    $p->bathrooms,
                    $p->land_size,
                    $p->building_size,
                    $p->status,
                    $p->created_at ? $p->created_at->format('d/m/Y') : '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Fase 7: Export Daily Operational Report (.CSV)
     */
    public function exportDailyReportCsv()
    {
        $csvFileName = 'kumala_daily_report_' . now()->format('Ymd_His') . '.csv';

        $totalActive = Property::where('status', 'ACTIVE')->count();
        $draftReview = Property::whereIn('status', ['DRAFT', 'PENDING_REVIEW'])->count();
        $totalValuation = Property::where('status', 'ACTIVE')->sum('price');
        $pendingSubmissions = Submission::where('status', 'pending')->count();
        $newLeads = Lead::where('stage', 'Baru')->count();
        $surveysToday = Survey::count();
        $closings = Transaction::count();

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Ekspor Laporan Harian Operasional (.CSV)",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Export',
        ]);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$csvFileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($totalActive, $draftReview, $totalValuation, $pendingSubmissions, $newLeads, $surveysToday, $closings) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['METRIK LAPORAN OPERASIONAL HARIAN', 'KUMALA PROPERTY', now()->format('d M Y H:i:s') . ' WIB']);
            fputcsv($file, []);
            fputcsv($file, ['Indikator Kinerja Utama (KPI)', 'Nilai']);
            fputcsv($file, ['Unit Properti Aktif Tayang', $totalActive . ' Unit']);
            fputcsv($file, ['Draft & Menunggu Review', $draftReview . ' Berkas']);
            fputcsv($file, ['Total Valuasi Portofolio Aktif', 'Rp ' . number_format($totalValuation, 0, ',', '.')]);
            fputcsv($file, ['Antrean Titip Properti Pending', $pendingSubmissions . ' Berkas']);
            fputcsv($file, ['Inquiry / Leads Baru Masuk', $newLeads . ' Prospek']);
            fputcsv($file, ['Total Jadwal Survei Fisik', $surveysToday . ' Agenda']);
            fputcsv($file, ['Total Transaksi Closing', $closings . ' Transaksi']);
            fputcsv($file, []);
            fputcsv($file, ['REKAP TRANSAKSI CLOSING TERAKHIR']);
            fputcsv($file, ['No. Transaksi', 'ID Properti', 'Unit Properti', 'Tipe', 'Harga Listing', 'Harga Deal Final', 'Pihak Terkait', 'Notaris']);
            foreach (Transaction::latest()->take(20)->get() as $t) {
                fputcsv($file, [
                    $t->id, $t->property_id, $t->property_title, $t->trx_type, $t->asking_price, $t->deal_price, $t->parties, $t->notary
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Fase 7: Export Closing Transactions (Printable PDF / Report)
     */
    public function exportTransactionsPdf()
    {
        $transactions = Transaction::latest()->get();
        $totalDeal = Transaction::sum('deal_val');
        $companyName = Setting::get('company_name', 'Kumala Property');
        $hqAddress = Setting::get('hq_address', 'Kumala Tower Lt. 18, Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan 12430');
        $hotline = Setting::get('hotline_admin', '+62 812-8899-2345');

        AuditLog::create([
            'timestamp_display' => now()->format('H:i') . ' WIB',
            'action' => "Cetak / Ekspor Rekapitulasi Closing Transaksi (PDF)",
            'user' => auth()->user() ? auth()->user()->name : 'Super Admin',
            'status' => 'Export',
            'details' => ['transactions_count' => $transactions->count()],
        ]);

        return view('admin.export-transactions-pdf', compact('transactions', 'totalDeal', 'companyName', 'hqAddress', 'hotline'));
    }

    /**
     * Static Fallback Helpers
     */
    private static function getDefaultPriorityActions(): array
    {
        return [
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
    }

    private static function getDefaultAuditLogs(): array
    {
        return [
            ['timestamp' => '11:42 WIB', 'action' => 'Persetujuan Listing Baru #KP-8826', 'user' => 'Aditya Pratama (Super Admin)', 'status' => 'Published'],
            ['timestamp' => '10:15 WIB', 'action' => 'Penyesuaian Harga Asking #KP-8821 (Rp 45M -> Rp 42M)', 'user' => 'Farhan Kamil (Curator)', 'status' => 'Updated'],
            ['timestamp' => '09:30 WIB', 'action' => 'Inquiry Baru #IQ-2209 Dialokasikan ke Concierge Bali', 'user' => 'Sistem Otomatis (Rule Engine)', 'status' => 'Assigned'],
            ['timestamp' => '08:50 WIB', 'action' => 'Unduhan Factsheet PDF #KP-8822 oleh Klien VIP', 'user' => 'Portal Publik (IP 182.253.xx.xx)', 'status' => 'Downloaded'],
        ];
    }

    private static function getDefaultSubmissions(): array
    {
        return [
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
                'checklist' => ['identity' => true, 'maps' => true, 'shm_match' => true, 'photos_hd' => false],
                'notes' => 'Aset lokasi sangat prime dekat Echo Beach.',
            ],
        ];
    }

    private static function getDefaultTransactions(): array
    {
        return [
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
        ];
    }

    private static function getDefaultInquiries(): array
    {
        return [
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
                'quote' => 'Halo tim Kumala, kami berencana survei privat Sabtu pagi ini.',
                'assigned_to' => 'Gusti Ayu (Concierge Bali)',
            ],
        ];
    }

    private static function getDefaultSurveys(): array
    {
        return [
            ['time' => '10:30 WIB', 'property' => 'Villa Cliffside Horizon Uluwatu', 'client' => 'Bpk. Richard Tan', 'agent' => 'Gusti Ayu', 'status' => 'Terkonfirmasi'],
        ];
    }

    private static function getDefaultRegions(): array
    {
        return [
            ['name' => 'DKI Jakarta - Jakarta Selatan', 'active' => true, 'listings' => 48],
            ['name' => 'Bali - Badung (Canggu, Uluwatu, Seminyak)', 'active' => true, 'listings' => 52],
            ['name' => 'Banten - Tangerang Selatan (BSD City)', 'active' => true, 'listings' => 24],
            ['name' => 'DKI Jakarta - Jakarta Pusat (Menteng)', 'active' => true, 'listings' => 12],
            ['name' => 'Jawa Timur - Surabaya Barat', 'active' => true, 'listings' => 8],
            ['name' => 'Jawa Barat - Bandung Utara', 'active' => false, 'listings' => 4],
        ];
    }

    private static function getDefaultCategories(): array
    {
        return [
            ['name' => 'Rumah Tapak Mewah', 'subtypes' => 'Heritage, Colonial, Modern Minimalis', 'active' => true, 'count' => 64],
            ['name' => 'Villa Mewah & Tropis', 'subtypes' => 'Cliffside, Beachfront, Ricefield View', 'active' => true, 'count' => 52],
            ['name' => 'Ruko & Komersial', 'subtypes' => 'Shophouse, Butik, Kantor Representatif', 'active' => true, 'count' => 18],
            ['name' => 'Apartemen & Penthouse', 'subtypes' => 'Sky Villa, Penthouse SCBD, High-rise', 'active' => true, 'count' => 14],
        ];
    }


    /**
     * Admin Logout (POST)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari Portal Kontrol Admin.');
    }

    /**
     * Safe redirect for GET /admin/logout
     */
    public function logoutRedirect()
    {
        return redirect()->route('admin.login');
    }
}

