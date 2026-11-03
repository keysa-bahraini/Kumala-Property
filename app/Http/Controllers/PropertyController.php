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
        $sort = $request->query('sort', 'terbaru');
        if (!in_array($sort, ['terbaru', 'harga_rendah', 'harga_tinggi', 'luas_bangunan'])) {
            $sort = 'terbaru';
        }

        $properties = KumalaDataHub::filterProperties($type, $category, $location, $priceRange);
        $settings = KumalaDataHub::getSettings();
        $categories = KumalaDataHub::getCategories();
        $regions = KumalaDataHub::getRegions();

        $searchKey = $request->query('q') ?? ($request->query('search') ?? $request->query('keyword'));
        if (!empty($searchKey)) {
            $properties = array_values(array_filter($properties, function ($item) use ($searchKey) {
                return stripos($item['title'] ?? '', $searchKey) !== false
                    || stripos($item['location_city'] ?? '', $searchKey) !== false
                    || stripos($item['location_area'] ?? '', $searchKey) !== false
                    || stripos($item['category'] ?? '', $searchKey) !== false
                    || stripos($item['id'] ?? '', $searchKey) !== false;
            }));
        }

        // Active filters list (exclude default fallback values, keep sort across filter removal)
        $activeFilters = [];
        if (!empty($type) && !in_array($type, ['Semua', 'all', ''])) {
            $queryWithout = $request->query();
            unset($queryWithout['type'], $queryWithout['page']);
            $activeFilters['type'] = [
                'key' => 'type',
                'label' => 'Tipe: ' . $type,
                'url' => route('explore', $queryWithout),
            ];
        }
        if (!empty($category) && !in_array($category, ['Semua Tipe Properti', 'Semua', 'all', ''])) {
            $queryWithout = $request->query();
            unset($queryWithout['category'], $queryWithout['page']);
            $activeFilters['category'] = [
                'key' => 'category',
                'label' => $category,
                'url' => route('explore', $queryWithout),
            ];
        }
        if (!empty($location) && !in_array($location, ['Semua Wilayah', 'Semua', 'all', ''])) {
            $queryWithout = $request->query();
            unset($queryWithout['location'], $queryWithout['page']);
            $activeFilters['location'] = [
                'key' => 'location',
                'label' => $location,
                'url' => route('explore', $queryWithout),
            ];
        }
        if (!empty($priceRange) && !in_array($priceRange, ['Semua Rentang Harga', 'Semua', 'all', ''])) {
            $queryWithout = $request->query();
            unset($queryWithout['price_range'], $queryWithout['page']);
            $activeFilters['price_range'] = [
                'key' => 'price_range',
                'label' => $priceRange,
                'url' => route('explore', $queryWithout),
            ];
        }
        if (!empty($searchKey)) {
            $queryWithout = $request->query();
            unset($queryWithout['q'], $queryWithout['search'], $queryWithout['keyword'], $queryWithout['page']);
            $activeFilters['q'] = [
                'key' => 'q',
                'label' => 'Kata kunci: "' . $searchKey . '"',
                'url' => route('explore', $queryWithout),
            ];
        }

        // Sort items according to requested sort criteria
        $collection = collect($properties);
        switch ($sort) {
            case 'harga_rendah':
                $sorted = $collection->sortBy('price', SORT_REGULAR, false)->values();
                break;
            case 'harga_tinggi':
                $sorted = $collection->sortBy('price', SORT_REGULAR, true)->values();
                break;
            case 'luas_bangunan':
                $sorted = $collection->sortBy('building_size', SORT_REGULAR, true)->values();
                break;
            case 'terbaru':
            default:
                $sorted = $collection->sortByDesc(function ($item) {
                    return $item['created_at'] ?? $item['id'] ?? '';
                })->values();
                break;
        }

        // LengthAwarePaginator (perPage: 6) with fallback support
        $perPage = 6;
        $total = $sorted->count();
        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = (int)$request->query('page', 1);

        if ($page < 1) {
            $page = 1;
        }
        if ($total > 0 && $page > $lastPage) {
            return redirect()->to($request->fullUrlWithQuery(['page' => $lastPage]));
        }

        $currentItems = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedProperties = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $properties = $paginatedProperties;

        return view('pages.explore', compact('properties', 'type', 'category', 'location', 'priceRange', 'sort', 'settings', 'categories', 'regions', 'activeFilters'));
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

        // Only active/published properties are visible to public. DRAFT and REVIEW are hidden from guests.
        if (in_array(($property['status'] ?? 'ACTIVE'), ['DRAFT', 'REVIEW'])) {
            if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->isAdmin()) {
                abort(404, 'Properti ini sedang dalam peninjauan kurator.');
            }
        }

        $city = trim($property['location_city'] ?? '');
        $all = KumalaDataHub::getAllProperties(true);

        // Filter properti se-wilayah (tidak termasuk unit yang sedang dibuka)
        $sameCityProperties = [];
        if (!empty($city)) {
            $sameCityProperties = array_values(array_filter($all, function ($item) use ($property, $city) {
                if (($item['id'] ?? '') === ($property['id'] ?? '')) {
                    return false;
                }
                $itemCity = trim($item['location_city'] ?? '');
                return strcasecmp($itemCity, $city) === 0 || stripos($itemCity, $city) !== false || stripos($city, $itemCity) !== false;
            }));
        }

        if (count($sameCityProperties) > 0) {
            $relatedProperties = array_slice($sameCityProperties, 0, 3);
            $relatedTitle = 'Rekomendasi Properti Serupa di ' . $city;
            $relatedLink = route('explore', ['location' => $city]);
            $relatedLinkLabel = 'Jelajahi Semua Listing ' . $city;
        } else {
            // Opsi B: bila wilayah tidak punya properti lain, tampilkan properti aktif lain
            $otherProperties = array_values(array_filter($all, function ($item) use ($property) {
                return ($item['id'] ?? '') !== ($property['id'] ?? '');
            }));
            $relatedProperties = array_slice($otherProperties, 0, 3);
            $relatedTitle = 'Rekomendasi Properti Lainnya';
            $relatedLink = route('explore');
            $relatedLinkLabel = 'Jelajahi Semua Listing';
        }

        $settings = KumalaDataHub::getSettings();

        return view('pages.detail', compact('property', 'relatedProperties', 'relatedTitle', 'relatedLink', 'relatedLinkLabel', 'settings'));
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
            'message' => 'Pengajuan konsinyasi properti Anda berhasil dikirim ke antrean kurator Kumala Property untuk ditinjau & diverifikasi sebelum diterbitkan di katalog publik!',
            'submission_id' => $submission['id'],
            'title' => $submission['title'],
            'property_id' => $submission['property_id'] ?? null,
            'property_slug' => $submission['property_slug'] ?? null,
            'property_url' => isset($submission['property_slug']) ? route('property.detail', $submission['property_slug']) : route('explore'),
        ]);
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
     * Page: Privasi & Syarat Ketentuan
     */
    public function privacyTerms()
    {
        $settings = KumalaDataHub::getSettings();
        return view('pages.privacy-terms', compact('settings'));
    }

    /**
     * Submit Inquiry Form (Minat Beli / Sewa dari User / Konsultasi Umum)
     * Langsung tersambung ke Admin Lead Pipeline CRM & Dashboard!
     */
    public function submitInquiry(InquiryRequest $request)
    {
        $data = $request->all();

        $isConsultation = $request->filled('subject') && !isset($data['notes']);
        if ($isConsultation) {
            $subject = $request->input('subject');
            $message = $request->input('message', '');
            $data['notes'] = "[Topik: {$subject}] {$message}";

            if (stripos($subject, 'Sewa') !== false) {
                $data['inquiry_type'] = 'sewa';
            } elseif (stripos($subject, 'Titip') !== false) {
                $data['inquiry_type'] = 'titip';
            } elseif (stripos($subject, 'Kemitraan') !== false) {
                $data['inquiry_type'] = 'kemitraan';
            } else {
                $data['inquiry_type'] = 'beli';
            }

            if (!isset($data['property_id'])) {
                $data['property_id'] = null;
            }
            if (!isset($data['property_title'])) {
                $data['property_title'] = 'Konsultasi Umum (Tentang Kami)';
            }
            if (!isset($data['property_price'])) {
                $data['property_price'] = '-';
            }
        }

        $inquiry = KumalaDataHub::createInquiry($data);

        $responseMsg = $isConsultation
            ? 'Pesan konsultasi Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera (SLA < 15 menit).'
            : 'Permohonan minat beli & jadwal survei Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera via WhatsApp resmi Kumala Property (SLA < 15 menit).';

        return response()->json([
            'success' => true,
            'message' => $responseMsg,
            'lead_id' => $inquiry['id'],
            'client_name' => $inquiry['client_name'],
            'property_title' => $inquiry['property_title'],
        ]);
    }
}
