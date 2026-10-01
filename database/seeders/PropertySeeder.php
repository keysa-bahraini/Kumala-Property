<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyPhoto;
use App\Models\Region;
use App\Services\PropertyDataService;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $regions = Region::all();

        // 1. Seed 9 Public Properties from PropertyDataService
        $staticProperties = PropertyDataService::getAll();

        foreach ($staticProperties as $p) {
            $catId = $this->matchCategoryId($p['category'], $categories);
            $regId = $this->matchRegionId($p['location_city'], $regions);

            $prop = Property::updateOrCreate(
                ['id' => $p['id']],
                [
                    'slug' => $p['slug'],
                    'title' => $p['title'],
                    'category_id' => $catId,
                    'region_id' => $regId,
                    'category' => $p['category'],
                    'transaction_type' => $p['transaction_type'],
                    'price' => $p['price'],
                    'price_formatted' => $p['price_formatted'],
                    'price_short' => $p['price_short'],
                    'price_label' => $p['price_label'],
                    'price_notes' => $p['price_notes'],
                    'price_negotiable' => true,
                    'location_city' => $p['location_city'],
                    'location_area' => $p['location_area'],
                    'location_address' => $p['location_address'],
                    'legal_status' => $p['legal_status'],
                    'verified' => $p['verified'],
                    'featured' => $p['featured'],
                    'highlight_badge' => $p['highlight_badge'] ?? null,
                    'land_size' => $p['land_size'] ?? 0,
                    'building_size' => $p['building_size'] ?? 0,
                    'floors' => $p['floors'] ?? 1,
                    'bedrooms' => $p['bedrooms'] ?? 0,
                    'bathrooms' => $p['bathrooms'] ?? 0,
                    'orientation' => $p['orientation'] ?? null,
                    'electricity' => $p['electricity'] ?? null,
                    'water_source' => $p['water_source'] ?? null,
                    'garage' => $p['garage'] ?? null,
                    'year_built' => $p['year_built'] ?? null,
                    'furnishing' => $p['furnishing'] ?? null,
                    'specs_tags' => $p['specs_tags'] ?? [],
                    'image_main' => $p['image_main'],
                    'video_tour_url' => $p['video_tour_url'] ?? null,
                    'pdf_factsheet_url' => $p['pdf_factsheet_url'] ?? null,
                    'description' => $p['description'],
                    'distance_points' => $p['distance_points'] ?? [],
                    'status' => 'ACTIVE',
                ]
            );

            // Photos
            PropertyPhoto::where('property_id', $prop->id)->delete();
            if (!empty($p['images'])) {
                foreach ($p['images'] as $idx => $imgUrl) {
                    PropertyPhoto::create([
                        'property_id' => $prop->id,
                        'photo_url' => $imgUrl,
                        'caption' => $p['title'] . ' Photo ' . ($idx + 1),
                        'is_main' => ($idx === 0),
                        'sort_order' => $idx,
                    ]);
                }
            }

            // Features
            PropertyFeature::where('property_id', $prop->id)->delete();
            if (!empty($p['features'])) {
                foreach ($p['features'] as $feat) {
                    PropertyFeature::create([
                        'property_id' => $prop->id,
                        'title' => $feat['title'],
                        'desc' => $feat['desc'],
                        'icon' => $feat['icon'] ?? 'star',
                    ]);
                }
            }
        }

        // 2. Seed 3 Additional Admin Properties (Option A Conflict Resolution)
        $adminProperties = [
            [
                'id' => '#KP-8830',
                'slug' => 'menteng-heritage-colonial-mansion',
                'title' => 'Menteng Heritage Colonial Mansion',
                'category' => 'Rumah Mewah',
                'transaction_type' => 'Jual',
                'price' => 85000000000,
                'price_formatted' => 'Rp 85.000.000.000',
                'price_short' => 'Rp 85 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'AJB Selesai • SHM Clear',
                'location_city' => 'Jakarta Pusat',
                'location_area' => 'Menteng, Jakarta Pusat',
                'location_address' => 'Jl. Teuku Umar No. 24, Menteng, Jakarta Pusat',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => false,
                'land_size' => 1200,
                'building_size' => 950,
                'floors' => 2,
                'bedrooms' => 6,
                'bathrooms' => 6,
                'image_main' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Mansion klasik kolonial di kawasan prestisius Menteng dengan pekarangan asri, pilar megah, dan status legalitas bersertifikat BPN bersih.',
                'status' => 'SOLD',
            ],
            [
                'id' => '#KP-8831',
                'slug' => 'tropical-modern-sanctuary-canggu',
                'title' => 'Tropical Modern Sanctuary Canggu',
                'category' => 'Villa',
                'transaction_type' => 'Sewa',
                'price' => 720000000,
                'price_formatted' => 'Rp 720.000.000 / tahun',
                'price_short' => 'Rp 720 Jt / tahun',
                'price_label' => 'Harga Sewa',
                'price_notes' => 'Perjanjian Sewa Sah (1 Tahun)',
                'location_city' => 'Bali',
                'location_area' => 'Canggu, Badung, Bali',
                'location_address' => 'Jl. Pantai Berawa No. 55, Canggu, Kuta Utara, Badung',
                'legal_status' => 'Hak Sewa (Leasehold)',
                'verified' => true,
                'featured' => false,
                'land_size' => 400,
                'building_size' => 320,
                'floors' => 2,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'image_main' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Vila tropis modern berkonsep open living di Berawa Canggu dengan kolam renang privat dan akses selangkah ke pantai.',
                'status' => 'RENTED',
            ],
            [
                'id' => '#KP-8832',
                'slug' => 'lavish-executive-penthouse-scbd',
                'title' => 'Lavish Executive Penthouse SCBD',
                'category' => 'Apartemen / Penthouse',
                'transaction_type' => 'Sewa',
                'price' => 850000000,
                'price_formatted' => 'Rp 850.000.000 / tahun',
                'price_short' => 'Rp 850 Jt / tahun',
                'price_label' => 'Harga Sewa',
                'price_notes' => 'Perjanjian Sewa Sah (2 Tahun Deal Rp 1.7 M)',
                'location_city' => 'Jakarta Selatan',
                'location_area' => 'SCBD Senayan, Jakarta Selatan',
                'location_address' => 'Pacific Place Residence Tower 1, SCBD, Jakarta Selatan',
                'legal_status' => 'Strata Title',
                'verified' => true,
                'featured' => false,
                'land_size' => 0,
                'building_size' => 350,
                'floors' => 1,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'image_main' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Penthouse eksekutif di jantung kawasan finansial SCBD dengan akses langsung ke mall dan lift pribadi.',
                'status' => 'RENTED',
            ],
        ];

        foreach ($adminProperties as $ap) {
            $catId = $this->matchCategoryId($ap['category'], $categories);
            $regId = $this->matchRegionId($ap['location_city'], $regions);

            $prop = Property::updateOrCreate(
                ['id' => $ap['id']],
                array_merge($ap, [
                    'category_id' => $catId,
                    'region_id' => $regId,
                    'price_negotiable' => false,
                    'specs_tags' => [$ap['bedrooms'] . ' KT', $ap['bathrooms'] . ' KM'],
                    'distance_points' => [],
                ])
            );

            PropertyPhoto::updateOrCreate(
                ['property_id' => $prop->id, 'sort_order' => 0],
                [
                    'photo_url' => $ap['image_main'],
                    'caption' => $ap['title'] . ' Main Fasad',
                    'is_main' => true,
                ]
            );
        }
    }

    private function matchCategoryId(string $catName, $categories): ?int
    {
        if (stripos($catName, 'Villa') !== false) {
            return $categories['Villa Mewah & Tropis']->id ?? null;
        }
        if (stripos($catName, 'Apartemen') !== false || stripos($catName, 'Penthouse') !== false) {
            return $categories['Apartemen & Penthouse']->id ?? null;
        }
        if (stripos($catName, 'Ruko') !== false || stripos($catName, 'Komersial') !== false) {
            return $categories['Ruko & Komersial']->id ?? null;
        }
        return $categories['Rumah Tapak Mewah']->id ?? null;
    }

    private function matchRegionId(string $cityName, $regions): ?int
    {
        foreach ($regions as $reg) {
            if (stripos($reg->city, $cityName) !== false || stripos($cityName, $reg->city) !== false) {
                return $reg->id;
            }
        }
        return $regions->first()->id ?? null;
    }
}
