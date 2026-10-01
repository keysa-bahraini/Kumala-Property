<?php

namespace App\Services;

use App\Models\Property;

class PropertyDataService
{
    /**
     * Transform Eloquent Property model to exact array structure
     */
    public static function transform(Property $p): array
    {
        $images = [];
        if ($p->relationLoaded('photos') && $p->photos->isNotEmpty()) {
            $images = $p->photos->pluck('photo_url')->toArray();
        } elseif ($p->photos()->exists()) {
            $images = $p->photos()->pluck('photo_url')->toArray();
        }
        if (empty($images) && $p->image_main) {
            $images = [$p->image_main];
        }

        $features = [];
        if ($p->relationLoaded('features') && $p->features->isNotEmpty()) {
            $features = $p->features->map(fn($f) => [
                'title' => $f->title,
                'desc' => $f->desc,
                'icon' => $f->icon ?: 'star',
            ])->toArray();
        } elseif ($p->features()->exists()) {
            $features = $p->features()->get()->map(fn($f) => [
                'title' => $f->title,
                'desc' => $f->desc,
                'icon' => $f->icon ?: 'star',
            ])->toArray();
        }

        return [
            'id' => $p->id,
            'slug' => $p->slug,
            'title' => $p->title,
            'category' => $p->category,
            'transaction_type' => $p->transaction_type,
            'price' => (int) $p->price,
            'price_formatted' => $p->price_formatted ?: 'Rp ' . number_format($p->price, 0, ',', '.'),
            'price_short' => $p->price_short ?: ('Rp ' . ($p->price >= 1000000000 ? round($p->price / 1000000000, 1) . ' Miliar' : round($p->price / 1000000) . ' Jt')),
            'price_label' => $p->price_label ?: ($p->transaction_type === 'Sewa' ? 'Biaya Sewa' : 'Harga Penawaran'),
            'price_notes' => $p->price_notes,
            'location_city' => $p->location_city,
            'location_area' => $p->location_area,
            'location_address' => $p->location_address,
            'legal_status' => $p->legal_status,
            'verified' => (bool) $p->verified,
            'featured' => (bool) $p->featured,
            'highlight_badge' => $p->highlight_badge,
            'land_size' => (int) $p->land_size,
            'building_size' => (int) $p->building_size,
            'floors' => (int) $p->floors,
            'bedrooms' => (int) $p->bedrooms,
            'bathrooms' => (int) $p->bathrooms,
            'orientation' => $p->orientation,
            'electricity' => $p->electricity,
            'water_source' => $p->water_source,
            'garage' => $p->garage,
            'year_built' => $p->year_built,
            'furnishing' => $p->furnishing,
            'specs_tags' => is_array($p->specs_tags) ? $p->specs_tags : (json_decode($p->specs_tags ?? '[]', true) ?: []),
            'image_main' => $p->image_main,
            'images' => $images,
            'description' => $p->description,
            'features' => $features,
            'distance_points' => is_array($p->distance_points) ? $p->distance_points : (json_decode($p->distance_points ?? '[]', true) ?: []),
            'status' => $p->status,
        ];
    }

    /**
     * Get all active properties from database
     */
    public static function getAll(): array
    {
        try {
            $properties = Property::with(['photos', 'features'])
                ->where('status', 'ACTIVE')
                ->orderBy('id', 'asc')
                ->get();

            if ($properties->isEmpty()) {
                return self::getStaticProperties();
            }

            return $properties->map(fn($item) => self::transform($item))->toArray();
        } catch (\Throwable $e) {
            return self::getStaticProperties();
        }
    }

    /**
     * Static initial dataset fallback
     */
    public static function getStaticProperties(): array
    {
        return [
            [
                'id' => '#KP-8821',
                'slug' => 'villa-cliffside-horizon-uluwatu',
                'title' => 'Villa Cliffside Horizon Uluwatu',
                'category' => 'Villa',
                'transaction_type' => 'Jual',
                'price' => 42000000000,
                'price_formatted' => 'Rp 42.000.000.000',
                'price_short' => 'Rp 42 Miliar',
                'price_label' => 'Harga Penawaran Eksklusif',
                'price_notes' => 'Nego Langsung Pemilik • Legalitas On Hand Clean',
                'location_city' => 'Bali',
                'location_area' => 'Uluwatu, Badung',
                'location_address' => 'Jl. Pantai Suluban, Uluwatu, Pecatu, Kuta Selatan, Badung, Bali 80361',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => true,
                'highlight_badge' => 'Ocean Sunset',
                'land_size' => 850,
                'building_size' => 620,
                'floors' => 2,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'orientation' => 'Barat Daya (Direct Sunset)',
                'electricity' => '16.500 VA + Backup Genset',
                'water_source' => 'PDAM & Deep Well Filter',
                'garage' => '3 Mobil + 4 Motor',
                'year_built' => '2023 (Pristine Baru)',
                'furnishing' => 'Furnished Bespoke Full Interior',
                'specs_tags' => ['5 Kamar Tidur', 'LT 850m²', 'Full Ocean View'],
                'image_main' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Terletak di atas tebing prestisius pesisir Uluwatu, Villa Cliffside Horizon merupakan adikarya arsitektur tropis modern yang memadukan kehangatan material batu paras alam, marmer Italia, dan elemen kayu jati grade A bersertifikasi legalitas. Properti ini dirancang secara eksklusif untuk menangkap vista panorama Samudra Hindia 180 derajat tanpa terhalang seumur hidup.\n\nSetiap sudut hunian dikuratori untuk menghadirkan sirkulasi udara alami yang sejuk serta limpahan pencahayaan senja. Area ruang keluarga berkonsep open-plan menyatu mulus dengan teras kolam renang infinity sepanjang 18 meter yang seolah melayang di tubir tebing. Lantai atas diperuntukkan khusus bagi kamar tidur utama yang memiliki balkon pribadi dengan pemandangan langsung ke ombak legendaris Pantai Suluban.\n\nSebagai portofolio investasi premium, vila ini berlokasi strategis di episentrum destinasi rekreasi kelas dunia Uluwatu. Didukung izin operasional pariwisata lengkap serta potensi yield sewa tahunan yang sangat tinggi (proyeksi gross ROI 11–14% per tahun), properti ini ideal sebagai sanctuary privat maupun investasi residensial bernilai tinggi di Bali.',
                'features' => [
                    [
                        'title' => 'Infinity Pool 18 Meter',
                        'desc' => 'Sistem sirkulasi garam ramah kulit dengan pemandangan jurang tebing laut.',
                        'icon' => 'waves'
                    ],
                    [
                        'title' => 'Sunken Lounge & Sun Deck',
                        'desc' => 'Bale gazebo kayu ulin dan area santai senja dengan instalasi fire pit.',
                        'icon' => 'sun'
                    ],
                    [
                        'title' => 'Dapur Gourmet Eropa & Wet Kitchen',
                        'desc' => 'Island marble terintegrasi Miele appliances dan dapur basah staf terpisah.',
                        'icon' => 'utensils'
                    ],
                    [
                        'title' => 'Staff Quarter & Security Post',
                        'desc' => '2 kamar tidur asisten rumah tangga dengan akses pintu servis independen.',
                        'icon' => 'shield'
                    ],
                    [
                        'title' => 'Smart Home & Keamanan 24 Jam',
                        'desc' => 'Automasi pencahayaan, digital smart lock, sensor gerak, dan 8 titik CCTV.',
                        'icon' => 'lock'
                    ],
                    [
                        'title' => 'Dedicated Fiber Optic Internet',
                        'desc' => 'Koneksi dedicated ultra-stabil ideal untuk remote executive & hiburan 4K.',
                        'icon' => 'wifi'
                    ],
                ],
                'distance_points' => [
                    ['time' => '5 Menit', 'place' => 'Pantai Suluban & Single Fin'],
                    ['time' => '15 Menit', 'place' => 'Savaya Bali & Ulu Cliffhouse'],
                    ['time' => '35 Menit', 'place' => 'Bandara Int\'l Ngurah Rai'],
                ]
            ],
            [
                'id' => '#KP-8822',
                'slug' => 'modern-tropical-residence-kemang',
                'title' => 'Modern Tropical Residence Kemang',
                'category' => 'Rumah Mewah',
                'transaction_type' => 'Sewa',
                'price' => 450000000,
                'price_formatted' => 'Rp 450.000.000 / tahun',
                'price_short' => 'Rp 450 Jt / tahun',
                'price_label' => 'Biaya Sewa',
                'price_notes' => 'Minimal Kontrak 2 Tahun • Deposit 10%',
                'location_city' => 'Jakarta Selatan',
                'location_area' => 'Kemang, Mampang Prapatan',
                'location_address' => 'Jl. Kemang Timur Raya No. 18, Jakarta Selatan 12730',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => true,
                'highlight_badge' => 'Private Pool',
                'land_size' => 480,
                'building_size' => 420,
                'floors' => 2,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'orientation' => 'Utara',
                'electricity' => '11.000 VA',
                'water_source' => 'Air Tanah Filter & Jet Pump',
                'garage' => '2 Mobil Dalam + 2 Carport',
                'year_built' => '2022',
                'furnishing' => 'Fully Furnished Scandinavian Chic',
                'specs_tags' => ['4 Kamar Tidur', 'LB 480m²', 'Private Pool'],
                'image_main' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Oase privat di jantung Jakarta Selatan dengan arsitektur tropis kontemporer, kolam renang alami, dan pencahayaan skylight yang lapang.',
                'features' => [],
                'distance_points' => [
                    ['time' => '5 Menit', 'place' => 'Lippo Mall Kemang'],
                    ['time' => '15 Menit', 'place' => 'SCBD Sudirman'],
                ]
            ],
            [
                'id' => '#KP-8823',
                'slug' => 'skyline-executive-penthouse',
                'title' => 'Skyline Executive Penthouse',
                'category' => 'Apartemen / Penthouse',
                'transaction_type' => 'Jual',
                'price' => 18500000000,
                'price_formatted' => 'Rp 18.500.000.000',
                'price_short' => 'Rp 18,5 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'Strata Title on Hand • Semi Furnished Miele',
                'location_city' => 'Jakarta Selatan',
                'location_area' => 'Pondok Indah',
                'location_address' => 'Jl. Metro Pondok Indah, Kebayoran Lama, Jakarta Selatan',
                'legal_status' => 'Strata Title',
                'verified' => true,
                'featured' => true,
                'highlight_badge' => 'Private Lift',
                'land_size' => 0,
                'building_size' => 320,
                'floors' => 1,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'orientation' => 'Timur (City Skyline)',
                'electricity' => '13.200 VA',
                'water_source' => 'PDAM Building Central',
                'garage' => '2 Reserved Parking Lot',
                'year_built' => '2021',
                'furnishing' => 'Semi Furnished Premium',
                'specs_tags' => ['3 Bed Ensuite', 'LB 320m²', 'Private Lift'],
                'image_main' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Penthouse mahatinggi dengan private foyer, lift khusus, spesifikasi marmer impor, dan pemandangan panorama skyline Jakarta 270 derajat.',
                'features' => [],
                'distance_points' => [
                    ['time' => '3 Menit', 'place' => 'Pondok Indah Mall 1 & 2'],
                    ['time' => '8 Menit', 'place' => 'Gerbang Tol TB Simatupang'],
                ]
            ],
            [
                'id' => '#KP-8824',
                'slug' => 'the-sanctuary-garden-bsd',
                'title' => 'The Sanctuary Garden BSD',
                'category' => 'Rumah Mewah',
                'transaction_type' => 'Jual',
                'price' => 8900000000,
                'price_formatted' => 'Rp 8.900.000.000',
                'price_short' => 'Rp 8,9 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'Siap Huni • SHM Murni',
                'location_city' => 'Tangerang Selatan',
                'location_area' => 'BSD City',
                'location_address' => 'Cluster Greenwich Park, BSD City, Tangerang Selatan',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'Ready Unit',
                'land_size' => 300,
                'building_size' => 280,
                'floors' => 2,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'orientation' => 'Selatan',
                'electricity' => '5.500 VA',
                'water_source' => 'PDAM BSD City',
                'garage' => '2 Carport Kanopi Kaca',
                'year_built' => '2023',
                'furnishing' => 'Unfurnished Pristine',
                'specs_tags' => ['4 Kamar Tidur', 'LT 300m²', 'Clubhouse'],
                'image_main' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Hunian cluster mewah bernuansa resor di kawasan mandiri BSD City dengan akses eksklusif ke lagoon pool dan botanical clubhouse.',
                'features' => [],
                'distance_points' => [
                    ['time' => '5 Menit', 'place' => 'AEON Mall BSD & ICE'],
                    ['time' => '7 Menit', 'place' => 'Tol Serpong - Balaraja'],
                ]
            ],
            [
                'id' => '#KP-8825',
                'slug' => 'villa-serenity-greenery-canggu',
                'title' => 'Villa Serenity Greenery Canggu',
                'category' => 'Villa',
                'transaction_type' => 'Sewa',
                'price' => 550000000,
                'price_formatted' => 'Rp 550.000.000 / tahun',
                'price_short' => 'Rp 550 Jt / tahun',
                'price_label' => 'Biaya Sewa',
                'price_notes' => 'Leasehold Tersedia / Sewa Tahunan',
                'location_city' => 'Bali',
                'location_area' => 'Canggu, Badung',
                'location_address' => 'Jl. Kayu Tulang, Canggu, Kuta Utara, Badung, Bali',
                'legal_status' => 'Hak Sewa (Leasehold)',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'Tahunan',
                'land_size' => 350,
                'building_size' => 310,
                'floors' => 2,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'orientation' => 'Barat',
                'electricity' => '7.700 VA',
                'water_source' => 'Sumur Bor Filter',
                'garage' => '1 Mobil + 2 Motor',
                'year_built' => '2024',
                'furnishing' => 'Fully Furnished Boho Luxe',
                'specs_tags' => ['3 Master Suite', 'LB 310m²', 'Rice Field View'],
                'image_main' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Vila santai tropis menghadap sawah abadi Canggu dengan interior boho modern dan dek kolam renang luas untuk relaksasi maksimal.',
                'features' => [],
                'distance_points' => [
                    ['time' => '7 Menit', 'place' => 'Pantai Batu Bolong'],
                    ['time' => '10 Menit', 'place' => 'Echo Beach'],
                ]
            ],
            [
                'id' => '#KP-8826',
                'slug' => 'grand-versailles-mansion-citraland',
                'title' => 'Grand Versailles Mansion CitraLand',
                'category' => 'Rumah Mewah',
                'transaction_type' => 'Jual',
                'price' => 26000000000,
                'price_formatted' => 'Rp 26.000.000.000',
                'price_short' => 'Rp 26 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'SHM On Hand • Siap Huni Klasik Mewah',
                'location_city' => 'Surabaya',
                'location_area' => 'CitraLand, Surabaya Barat',
                'location_address' => 'Bukit Golf CitraLand, Sambikerep, Surabaya Barat',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'SHM On Hand',
                'land_size' => 640,
                'building_size' => 580,
                'floors' => 2,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'orientation' => 'Timur',
                'electricity' => '13.200 VA',
                'water_source' => 'PDAM CitraLand',
                'garage' => 'Garasi 4 Mobil',
                'year_built' => '2021',
                'furnishing' => 'Semi Furnished European Style',
                'specs_tags' => ['5 Kamar Tidur', 'LT 640m²', 'Garasi 4 Mobil'],
                'image_main' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Mansion megah bergaya Eropa klasik modern dengan pilar tinggi, marmer slab utuh, dan halaman belakang luas dengan pemandangan lapangan golf.',
                'features' => [],
                'distance_points' => [
                    ['time' => '5 Menit', 'place' => 'Ciputra World Surabaya'],
                    ['time' => '10 Menit', 'place' => 'Pakuwon Mall'],
                ]
            ],
            [
                'id' => '#KP-8827',
                'slug' => 'the-bingin-cove-sanctuary',
                'title' => 'The Bingin Cove Sanctuary',
                'category' => 'Villa',
                'transaction_type' => 'Jual',
                'price' => 26500000000,
                'price_formatted' => 'Rp 26.500.000.000',
                'price_short' => 'Rp 26,5 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'Freehold SHM • Full Furnished',
                'location_city' => 'Bali',
                'location_area' => 'Bingin Beach, Uluwatu',
                'location_address' => 'Jl. Pantai Bingin, Pecatu, Kuta Selatan, Badung, Bali',
                'legal_status' => 'SHM (Hak Milik)',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'Ocean View',
                'land_size' => 450,
                'building_size' => 380,
                'floors' => 2,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'orientation' => 'Barat',
                'electricity' => '10.600 VA',
                'water_source' => 'PDAM & Filter',
                'garage' => '2 Mobil',
                'year_built' => '2023',
                'furnishing' => 'Fully Furnished Luxury',
                'specs_tags' => ['4 KT', '4 KM', '450 m²'],
                'image_main' => 'https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Vila eksklusif selangkah dari pantai pasir putih Bingin dengan rooftop lounge berlatar Samudra Hindia.',
                'features' => [],
                'distance_points' => []
            ],
            [
                'id' => '#KP-8828',
                'slug' => 'cliff-haven-nusa-dua-estate',
                'title' => 'Cliff Haven Nusa Dua Estate',
                'category' => 'Villa',
                'transaction_type' => 'Sewa',
                'price' => 1450000000,
                'price_formatted' => 'Rp 1.450.000.000 / tahun',
                'price_short' => 'Rp 1.450 Jt / tahun',
                'price_label' => 'Harga Sewa / Tahun',
                'price_notes' => 'Minimum 1 Tahun • Staff Terlatih',
                'location_city' => 'Bali',
                'location_area' => 'Sawangan, Nusa Dua',
                'location_address' => 'Kawasan Sawangan, Nusa Dua Selatan, Badung, Bali',
                'legal_status' => 'Sewa Tahunan (Verified)',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'Verified',
                'land_size' => 920,
                'building_size' => 750,
                'floors' => 2,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'orientation' => 'Selatan',
                'electricity' => '23.000 VA',
                'water_source' => 'PDAM Nusa Dua',
                'garage' => '4 Mobil',
                'year_built' => '2022',
                'furnishing' => 'Fully Furnished Presidential Style',
                'specs_tags' => ['6 KT', '7 KM', '920 m²'],
                'image_main' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Estate super megah di kawasan hotel bintang lima Nusa Dua dengan private tennis court dan helipad access.',
                'features' => [],
                'distance_points' => []
            ],
            [
                'id' => '#KP-8829',
                'slug' => 'casa-del-sol-mediterranean-villa',
                'title' => 'Casa Del Sol Mediterranean Villa',
                'category' => 'Villa',
                'transaction_type' => 'Jual',
                'price' => 34000000000,
                'price_formatted' => 'Rp 34.000.000.000',
                'price_short' => 'Rp 34 Miliar',
                'price_label' => 'Harga Penawaran',
                'price_notes' => 'HGB / SHM Murni',
                'location_city' => 'Bali',
                'location_area' => 'Padang Padang, Pecatu',
                'location_address' => 'Jl. Labuansait, Pecatu, Badung, Bali',
                'legal_status' => 'HGB / SHM',
                'verified' => true,
                'featured' => false,
                'highlight_badge' => 'HGB / SHM',
                'land_size' => 510,
                'building_size' => 450,
                'floors' => 2,
                'bedrooms' => 4,
                'bathrooms' => 5,
                'orientation' => 'Barat',
                'electricity' => '13.200 VA',
                'water_source' => 'PDAM & Filter',
                'garage' => '2 Mobil',
                'year_built' => '2023',
                'furnishing' => 'Full Furnished Mediterranean',
                'specs_tags' => ['4 KT', '5 KM', '510 m²'],
                'image_main' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Adopsi arsitektur Mediterania tropis dengan lengkungan estetik, dinding bertekstur kapur, dan taman kaktus eksotis.',
                'features' => [],
                'distance_points' => []
            ]
        ];
    }

    /**
     * Find property by slug from database (status ACTIVE)
     */
    public static function findBySlug(string $slug): ?array
    {
        try {
            $p = Property::with(['photos', 'features'])
                ->where('status', 'ACTIVE')
                ->where('slug', $slug)
                ->first();

            if ($p) {
                return self::transform($p);
            }

            // Fallback to first active if not found (matching legacy behavior)
            $first = Property::with(['photos', 'features'])
                ->where('status', 'ACTIVE')
                ->first();

            if ($first) {
                return self::transform($first);
            }

            return self::getStaticProperties()[0] ?? null;
        } catch (\Throwable $e) {
            $all = self::getStaticProperties();
            foreach ($all as $property) {
                if ($property['slug'] === $slug) {
                    return $property;
                }
            }
            return $all[0] ?? null;
        }
    }

    /**
     * Filter properties directly via database query
     */
    public static function filter(?string $type = null, ?string $category = null, ?string $location = null, ?string $priceRange = null): array
    {
        try {
            $query = Property::with(['photos', 'features'])->where('status', 'ACTIVE');

            if ($type && $type !== 'Semua') {
                $query->whereRaw('LOWER(transaction_type) = ?', [strtolower($type)]);
            }

            if ($category && $category !== 'Semua Tipe Properti' && $category !== 'Semua') {
                $query->where('category', 'like', "%{$category}%");
            }

            if ($location && $location !== 'Semua Wilayah' && $location !== 'Semua') {
                $query->where(function ($q) use ($location) {
                    $q->where('location_city', 'like', "%{$location}%")
                      ->orWhere('location_area', 'like', "%{$location}%");
                });
            }

            if ($priceRange && $priceRange !== 'Semua Rentang Harga') {
                if ($priceRange === '< 5 Miliar') {
                    $query->where('price', '<', 5000000000);
                } elseif ($priceRange === '5 - 15 Miliar') {
                    $query->whereBetween('price', [5000000000, 15000000000]);
                } elseif ($priceRange === '15 - 30 Miliar') {
                    $query->whereBetween('price', [15000000000, 30000000000]);
                } elseif ($priceRange === '> 30 Miliar') {
                    $query->where('price', '>', 30000000000);
                }
            }

            $results = $query->orderBy('id', 'asc')->get();

            if ($results->isEmpty() && Property::count() === 0) {
                return self::filterStatic($type, $category, $location, $priceRange);
            }

            return $results->map(fn($item) => self::transform($item))->toArray();
        } catch (\Throwable $e) {
            return self::filterStatic($type, $category, $location, $priceRange);
        }
    }

    /**
     * Fallback in-memory filter on static properties array
     */
    public static function filterStatic(?string $type = null, ?string $category = null, ?string $location = null, ?string $priceRange = null): array
    {
        $items = self::getStaticProperties();

        return array_values(array_filter($items, function ($item) use ($type, $category, $location, $priceRange) {
            if ($type && $type !== 'Semua') {
                if (strtolower($item['transaction_type']) !== strtolower($type)) {
                    return false;
                }
            }
            if ($category && $category !== 'Semua Tipe Properti' && $category !== 'Semua') {
                if (stripos($item['category'], $category) === false && stripos($category, $item['category']) === false) {
                    return false;
                }
            }
            if ($location && $location !== 'Semua Wilayah' && $location !== 'Semua') {
                if (stripos($item['location_city'], $location) === false && stripos($item['location_area'], $location) === false) {
                    return false;
                }
            }
            if ($priceRange && $priceRange !== 'Semua Rentang Harga') {
                $p = $item['price'];
                if ($priceRange === '< 5 Miliar' && $p >= 5000000000) return false;
                if ($priceRange === '5 - 15 Miliar' && ($p < 5000000000 || $p > 15000000000)) return false;
                if ($priceRange === '15 - 30 Miliar' && ($p < 15000000000 || $p > 30000000000)) return false;
                if ($priceRange === '> 30 Miliar' && $p <= 30000000000) return false;
            }
            return true;
        }));
    }
}

