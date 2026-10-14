<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Http\Requests\TitipPropertiRequest;
use App\Services\KumalaDataHub;
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
        $featuredProperties = array_slice(KumalaDataHub::getAllProperties(true), 0, 6);
        $settings = KumalaDataHub::getSettings();
        return view('pages.home', compact('featuredProperties', 'settings'));
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

        $properties = KumalaDataHub::filterProperties($type, $category, $location, $priceRange);
        $settings = KumalaDataHub::getSettings();
        $categories = KumalaDataHub::getCategories();
        $regions = KumalaDataHub::getRegions();

        return view('pages.explore', compact('properties', 'type', 'category', 'location', 'priceRange', 'settings', 'categories', 'regions'));
    }

    /**
     * Page 3: Detail Properti
     */
    public function detail(string $slug = 'villa-cliffside-horizon-uluwatu')
    {
        $property = KumalaDataHub::findPropertyBySlug($slug);
        if (!$property) {
            abort(404, 'Properti tidak ditemukan');
        }

        $all = KumalaDataHub::getAllProperties(true);
        $relatedProperties = array_values(array_filter($all, function ($item) use ($property) {
            return ($item['id'] ?? '') !== ($property['id'] ?? '');
        }));
        $relatedProperties = array_slice($relatedProperties, 0, 3);
        $settings = KumalaDataHub::getSettings();

        return view('pages.detail', compact('property', 'relatedProperties', 'settings'));
    }

    /**
     * Page 4: Titip Properti (Consignment Submission Wizard)
     */
    public function titipProperti()
    {
        $categories = KumalaDataHub::getCategories();
        $regions = KumalaDataHub::getRegions();
        $settings = KumalaDataHub::getSettings();
        return view('pages.titip-properti', compact('categories', 'regions', 'settings'));
    }

    /**
     * Submit Titip Properti (Konsinyasi dari User)
     * Langsung tersambung ke Admin Gatekeeper Submissions & Admin Dashboard!
     */
    public function submitTitipProperti(TitipPropertiRequest $request)
    {
        $data = $request->all();

        // Handle uploaded photos if present
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            $uploadDir = public_path('uploads/submissions');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $filename = 'prop_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadDir, $filename);
                    $photoUrls[] = asset('uploads/submissions/' . $filename);
                }
            }
        }

        if (!empty($photoUrls)) {
            $data['photos'] = $photoUrls;
            $data['photos_count'] = count($photoUrls);
        }

        $submission = KumalaDataHub::createSubmission($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan konsinyasi properti Anda berhasil dikirim ke antrean kurator Kumala Property dan otomatis terbit di katalog publik!',
            'submission_id' => $submission['id'],
            'title' => $submission['title'],
            'property_id' => $submission['property_id'] ?? null,
            'property_slug' => $submission['property_slug'] ?? null,
            'property_url' => isset($submission['property_slug']) ? route('property.detail', $submission['property_slug']) : route('explore'),
        ]);
    }

    /**
     * Page 5: Properti Favorit Saya
     */
    public function favorites(Request $request)
    {
        $allProperties = KumalaDataHub::getAllProperties();
        $settings = KumalaDataHub::getSettings();
        return view('pages.favorites', compact('allProperties', 'settings'));
    }

    /**
     * Page 6: Tentang Kami & Kontak
     */
    public function about()
    {
        $settings = KumalaDataHub::getSettings();
        return view('pages.about', compact('settings'));
    }

    /**
     * Submit Inquiry Form (Minat Beli / Sewa dari User)
     * Langsung tersambung ke Admin Lead Pipeline CRM & Dashboard!
     */
    public function submitInquiry(InquiryRequest $request)
    {
        $inquiry = KumalaDataHub::createInquiry($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Permohonan minat beli & jadwal survei Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera via WhatsApp resmi Kumala Property (SLA < 15 menit).',
            'lead_id' => $inquiry['id'],
            'client_name' => $inquiry['client_name'],
            'property_title' => $inquiry['property_title'],
        ]);
    }
}
