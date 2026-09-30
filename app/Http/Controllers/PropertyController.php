<?php

namespace App\Http\Controllers;

use App\Services\PropertyDataService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Page 1: Landing Page (Beranda)
     */
    public function home()
    {
        $featuredProperties = array_slice(PropertyDataService::getAll(), 0, 6);
        return view('pages.home', compact('featuredProperties'));
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

        return view('pages.explore', compact('properties', 'type', 'category', 'location', 'priceRange'));
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
        return view('pages.titip-properti');
    }

    /**
     * Submit Titip Properti
     */
    public function submitTitipProperti(Request $request)
    {
        // Mock submission response for demo
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan konsinyasi properti Anda berhasil dikirim! Tim kurator Kumala Property akan menghubungi Anda dalam waktu < 4 jam.',
            'submission_id' => '#TP-' . rand(205, 999),
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
     * Submit Inquiry Form
     */
    public function submitInquiry(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan minat & permohonan survei Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera (SLA < 15 menit).',
            'lead_id' => '#IQ-' . rand(2210, 9999),
        ]);
    }
}
