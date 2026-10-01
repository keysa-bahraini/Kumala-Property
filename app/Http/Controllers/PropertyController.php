<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Http\Requests\TitipPropertiRequest;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\SubmissionChecklist;
use App\Models\Survey;
use App\Services\PropertyDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Page 1: Landing Page (Beranda)
     */
    public function home()
    {
        $featuredProperties = array_slice(PropertyDataService::getAll(), 0, 6);
        $activeCategories = Category::where('is_active', true)->get();
        $activeRegions = Region::where('is_active', true)->get();
        return view('pages.home', compact('featuredProperties', 'activeCategories', 'activeRegions'));
    }

    /**
     * Page 2: Explore Properti (Katalog & Filter Multi-Parameter)
     */
    public function explore(Request $request)
    {
        $type = $request->query('type', 'Semua');
        $category = $request->query('category', 'Semua Tipe Properti');
        $location = $request->query('location', 'Semua Wilayah');
        $priceRange = $request->query('price_range', 'Semua Rentang Harga');

        $properties = PropertyDataService::filter($type, $category, $location, $priceRange);
        $activeCategories = Category::where('is_active', true)->get();
        $activeRegions = Region::where('is_active', true)->get();

        return view('pages.explore', compact('properties', 'type', 'category', 'location', 'priceRange', 'activeCategories', 'activeRegions'));
    }

    /**
     * Page 3: Detail Properti
     */
    public function detail(string $slug = 'villa-cliffside-horizon-uluwatu')
    {
        $property = PropertyDataService::findBySlug($slug);
        if (!$property) {
            abort(404, 'Properti tidak ditemukan');
        }

        $all = PropertyDataService::getAll();
        $relatedProperties = array_values(array_filter($all, function ($item) use ($property) {
            return $item['id'] !== $property['id'];
        }));
        $relatedProperties = array_slice($relatedProperties, 0, 3);

        return view('pages.detail', compact('property', 'relatedProperties'));
    }

    /**
     * Page 4: Titip Properti (Consignment Submission Wizard)
     */
    public function titipProperti()
    {
        $activeCategories = Category::where('is_active', true)->get();
        $activeRegions = Region::where('is_active', true)->get();
        return view('pages.titip-properti', compact('activeCategories', 'activeRegions'));
    }

    /**
     * Submit Titip Properti to database
     */
    public function submitTitipProperti(TitipPropertiRequest $request)
    {
        // Generate sequential #TP-xxx ID
        $maxId = Submission::pluck('id')->map(function ($id) {
            if (preg_match('/#TP-(\d+)/', $id, $m)) {
                return (int) $m[1];
            }
            return 0;
        })->max();

        $nextNum = max(206, $maxId ?: 206) + 1;
        $submissionId = '#TP-' . $nextNum;

        // Parse price
        $rawPrice = preg_replace('/[^0-9]/', '', $request->input('asking_price'));
        $askingVal = (int) $rawPrice;
        $priceFormatted = 'Rp ' . number_format($askingVal, 0, ',', '.');
        $valMin = $askingVal * 0.95;
        $valMax = $askingVal * 1.05;
        $valuationRange = 'Rp ' . ($valMin >= 1000000000 ? round($valMin / 1000000000, 1) . ' M' : round($valMin / 1000000) . ' Jt') . ' - ' . ($valMax >= 1000000000 ? round($valMax / 1000000000, 1) . ' Miliar' : round($valMax / 1000000) . ' Jt');

        // Handle uploaded photos
        $photosCount = 0;
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $photo->store('submissions', 'public');
                    $photosCount++;
                }
            }
        }

        $phone = $request->input('owner_phone');
        if (!Str::startsWith($phone, '+62')) {
            $phone = '+62 ' . ltrim($phone, '0');
        }

        $submission = Submission::create([
            'id' => $submissionId,
            'title' => $request->input('property_title'),
            'owner_name' => $request->input('owner_name'),
            'owner_phone' => $phone,
            'owner_email' => $request->input('owner_email'),
            'owner_status' => $request->input('ownership_status') === 'agent_official' ? 'Kuasa Jual Resmi' : 'Pemilik Langsung',
            'trx_type' => $request->input('transaction_type', 'Jual'),
            'category' => $request->input('property_category', 'Villa Mewah'),
            'location' => $request->input('city_region') . ($request->input('specific_area') ? ' - ' . $request->input('specific_area') : ''),
            'address' => $request->input('full_address'),
            'asking_price' => $priceFormatted,
            'asking_val' => $askingVal,
            'valuation_range' => $valuationRange,
            'land_size' => (int) $request->input('land_size', 0),
            'building_size' => (int) $request->input('building_size', 0),
            'bedrooms' => $request->input('bedrooms', '4 KT'),
            'bathrooms' => $request->input('bathrooms', '4 KM'),
            'electricity' => '3500 VA',
            'legal' => in_array('shm', (array)$request->input('facilities', [])) ? 'SHM (Hak Milik)' : 'HGB / Dokumen Lengkap',
            'priority' => 'P1 - Hot Asset',
            'photos_count' => max(4, $photosCount),
            'status' => 'pending',
            'notes' => $request->input('description'),
            'submitted_at' => now(),
        ]);

        SubmissionChecklist::create([
            'submission_id' => $submission->id,
            'identity' => true,
            'maps' => true,
            'shm_match' => in_array('shm', (array)$request->input('facilities', [])),
            'photos_hd' => $photosCount >= 4,
        ]);

        if (Setting::get('notify_new_submission', '1') == '1') {
            Log::info("Notifikasi Email Otomatis: Pengajuan Titip Properti Baru {$submission->id} dikirim ke " . Setting::get('email_business', 'concierge@kumalaproperty.id'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan konsinyasi properti Anda berhasil dikirim! Tim kurator Kumala Property akan menghubungi Anda dalam waktu < 4 jam.',
            'submission_id' => $submission->id,
        ]);
    }

    /**
     * Page 5: Properti Favorit Saya
     */
     public function favorites(Request $request)
     {
         $allProperties = PropertyDataService::getAll();
         return view('pages.favorites', compact('allProperties'));
     }

     /**
      * Page 6: Tentang Kami & Kontak
      */
     public function about()
     {
         return view('pages.about');
     }

    /**
     * Submit Inquiry Form to database
     */
    public function submitInquiry(InquiryRequest $request)
    {
        // Generate sequential #IQ-xxx ID
        $maxId = Lead::pluck('id')->map(function ($id) {
            if (preg_match('/#IQ-(\d+)/', $id, $m)) {
                return (int) $m[1];
            }
            return 0;
        })->max();

        $nextNum = max(2209, $maxId ?: 2209) + 1;
        $leadId = '#IQ-' . $nextNum;

        $property = Property::find($request->input('property_id'));

        $lead = Lead::create([
            'id' => $leadId,
            'property_id' => $property ? $property->id : $request->input('property_id'),
            'property_title' => $property ? $property->title : 'Properti Eksklusif',
            'property_price' => $property ? $property->price_formatted : 'Sesuai Penawaran',
            'client_name' => $request->input('name'),
            'client_phone' => $request->input('phone'),
            'client_email' => $request->input('email'),
            'buyer_category' => 'Pembeli Rumah Tinggal',
            'message' => $request->input('notes') ?: 'Permohonan informasi dan jadwal survei.',
            'quote' => $request->input('notes') ?: 'Permohonan informasi dan jadwal survei.',
            'inquiry_type' => $request->input('inquiry_type', 'beli'),
            'payment_scheme' => $request->input('payment_scheme', 'Cash Keras'),
            'survey_date_preference' => $request->input('survey_date') ? Carbon::parse($request->input('survey_date'))->translatedFormat('l, d F Y') : 'Menyesuaikan Jadwal Agen',
            'assigned_agent' => 'Gusti Ayu (Concierge Bali)',
            'stage' => 'Baru',
            'sla_minutes' => 12,
            'sla_badge' => 'SLA Baru (< 15 mnt)',
            'sla_status' => 'urgent',
            'time_ago' => 'Baru saja',
        ]);

        if ($request->filled('survey_date')) {
            Survey::create([
                'lead_id' => $lead->id,
                'property_id' => $property ? $property->id : null,
                'property_title' => $property ? $property->title : 'Properti Eksklusif',
                'client_name' => $request->input('name'),
                'agent_name' => 'Gusti Ayu (Concierge Bali)',
                'survey_time' => '10:30 WIB',
                'survey_date' => $request->input('survey_date'),
                'status' => 'Menunggu Klien',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan minat & permohonan survei Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera (SLA < 15 menit).',
            'lead_id' => $lead->id,
        ]);
    }
}
