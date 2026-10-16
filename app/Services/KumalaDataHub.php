<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Lead;
use App\Models\PriorityAction;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyPhoto;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KumalaDataHub
{
    protected static ?string $storageFile = null;

    protected static function getStoragePath(): string
    {
        if (self::$storageFile === null) {
            self::$storageFile = storage_path('app/kumala_store.json');
        }
        return self::$storageFile;
    }

    /**
     * Optional file sync / fallback
     */
    public static function loadFallbackData(): array
    {
        $path = self::getStoragePath();
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $data = json_decode($content, true);
            if (is_array($data) && isset($data['properties'])) {
                return $data;
            }
        }
        return [];
    }

    // =========================================================================
    // 1. PROPERTIES MANAGEMENT (Eloquent Database Layer)
    // =========================================================================

    public static function getAllProperties(bool $activeOnly = false): array
    {
        try {
            $query = Property::orderBy('created_at', 'desc');
            if ($activeOnly) {
                $query->where('status', 'ACTIVE');
            }
            $properties = $query->get();

            if ($properties->isNotEmpty()) {
                return $properties->map(fn($p) => self::transformProperty($p))->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getAllProperties fallback to file: ' . $e->getMessage());
        }

        // Fallback
        $fb = self::loadFallbackData()['properties'] ?? [];
        if ($activeOnly) {
            return array_values(array_filter($fb, fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE'));
        }
        return $fb;
    }

    public static function findPropertyBySlug(string $slug): ?array
    {
        try {
            $prop = Property::where('slug', $slug)->first();
            if ($prop) {
                return self::transformProperty($prop);
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::findPropertyBySlug fallback: ' . $e->getMessage());
        }

        // Fallback
        $all = self::getAllProperties();
        foreach ($all as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }
        return $all[0] ?? null;
    }

    public static function findPropertyById(string $id): ?array
    {
        try {
            $prop = Property::where('id', $id)->first();
            if ($prop) {
                return self::transformProperty($prop);
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::findPropertyById fallback: ' . $e->getMessage());
        }

        $all = self::getAllProperties();
        foreach ($all as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null;
    }

    public static function filterProperties(?string $type = null, ?string $category = null, ?string $location = null, ?string $priceRange = null): array
    {
        try {
            $query = Property::where('status', 'ACTIVE');

            if ($type && $type !== 'Semua') {
                $query->where('transaction_type', $type);
            }

            if ($category && $category !== 'Semua Tipe Properti' && $category !== 'Semua') {
                $query->where(function ($q) use ($category) {
                    $q->where('category', 'LIKE', "%{$category}%")
                      ->orWhere('title', 'LIKE', "%{$category}%");
                });
            }

            if ($location && $location !== 'Semua Wilayah' && $location !== 'Semua') {
                $query->where(function ($q) use ($location) {
                    $q->where('location_city', 'LIKE', "%{$location}%")
                      ->orWhere('location_area', 'LIKE', "%{$location}%")
                      ->orWhere('location_address', 'LIKE', "%{$location}%");
                });
            }

            if ($priceRange && $priceRange !== 'Semua Rentang Harga') {
                if ($priceRange === '< 5 Miliar' || $priceRange === 'under_5b') {
                    $query->where('price', '<', 5000000000);
                } elseif ($priceRange === '5 - 15 Miliar' || $priceRange === '5b_15b') {
                    $query->whereBetween('price', [5000000000, 15000000000]);
                } elseif ($priceRange === '15 - 30 Miliar' || $priceRange === '15b_30b') {
                    $query->whereBetween('price', [15000000000, 30000000000]);
                } elseif ($priceRange === '> 30 Miliar' || $priceRange === 'above_30b') {
                    $query->where('price', '>', 30000000000);
                }
            }

            $results = $query->orderBy('created_at', 'desc')->get();
            return $results->map(fn($p) => self::transformProperty($p))->toArray();
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::filterProperties fallback: ' . $e->getMessage());
        }

        // Fallback filter
        $items = self::getAllProperties(true);
        return array_values(array_filter($items, function ($item) use ($type, $category, $location, $priceRange) {
            if ($type && $type !== 'Semua') {
                if (strtolower($item['transaction_type'] ?? '') !== strtolower($type)) return false;
            }
            if ($category && $category !== 'Semua Tipe Properti' && $category !== 'Semua') {
                if (stripos($item['category'] ?? '', $category) === false && stripos($category, $item['category'] ?? '') === false) return false;
            }
            if ($location && $location !== 'Semua Wilayah' && $location !== 'Semua') {
                $city = $item['location_city'] ?? '';
                $area = $item['location_area'] ?? '';
                if (stripos($city, $location) === false && stripos($area, $location) === false) return false;
            }
            if ($priceRange && $priceRange !== 'Semua Rentang Harga') {
                $p = $item['price'] ?? 0;
                if (($priceRange === '< 5 Miliar' || $priceRange === 'under_5b') && $p >= 5000000000) return false;
                if (($priceRange === '5 - 15 Miliar' || $priceRange === '5b_15b') && ($p < 5000000000 || $p > 15000000000)) return false;
                if (($priceRange === '15 - 30 Miliar' || $priceRange === '15b_30b') && ($p < 15000000000 || $p > 30000000000)) return false;
                if (($priceRange === '> 30 Miliar' || $priceRange === 'above_30b') && $p <= 30000000000) return false;
            }
            return true;
        }));
    }

    public static function addProperty(array $newProperty): array
    {
        $id = $newProperty['id'] ?? null;
        if (empty($id)) {
            $count = Property::count();
            $id = '#KP-' . (8820 + $count + 1);
        }

        $title = $newProperty['title'] ?? 'Properti Baru Kumala';
        $slug = $newProperty['slug'] ?? (Str::slug($title) . '-' . rand(100, 999));
        $price = (float)($newProperty['price'] ?? 1000000000);

        $specsTags = $newProperty['specs_tags'] ?? [
            ($newProperty['bedrooms'] ?? 3) . ' Kamar Tidur',
            'LT ' . ($newProperty['land_size'] ?? 200) . 'm²',
            'LB ' . ($newProperty['building_size'] ?? 180) . 'm²',
        ];

        $images = !empty($newProperty['images']) && is_array($newProperty['images']) ? $newProperty['images'] : [
            $newProperty['image_main'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
        ];

        $features = !empty($newProperty['features']) && is_array($newProperty['features']) ? $newProperty['features'] : [
            ['title' => 'Keamanan 24 Jam', 'desc' => 'One gate security system.', 'icon' => 'shield'],
            ['title' => 'Akses Strategis', 'desc' => 'Dekat pusat bisnis dan area komersial.', 'icon' => 'map-pin'],
        ];

        $distancePoints = $newProperty['distance_points'] ?? [
            ['time' => '10 Menit', 'place' => 'Pusat Kota / CBD'],
            ['time' => '25 Menit', 'place' => 'Akses Tol / Bandara'],
        ];

        $propData = [
            'id' => $id,
            'slug' => $slug,
            'title' => $title,
            'category' => $newProperty['category'] ?? 'Rumah Mewah',
            'transaction_type' => $newProperty['transaction_type'] ?? 'Jual',
            'price' => $price,
            'price_formatted' => 'Rp ' . number_format($price, 0, ',', '.'),
            'price_short' => self::formatShortPrice($price),
            'price_label' => ($newProperty['transaction_type'] ?? 'Jual') === 'Jual' ? 'Harga Penawaran Eksklusif' : 'Biaya Sewa',
            'price_notes' => $newProperty['price_notes'] ?? 'Legalitas Lengkap • Siap Transaksi',
            'price_negotiable' => true,
            'location_city' => $newProperty['location_city'] ?? 'Jakarta Selatan',
            'location_area' => $newProperty['location_area'] ?? 'Kebayoran Baru',
            'location_address' => $newProperty['location_address'] ?? 'Jakarta Selatan, DKI Jakarta',
            'legal_status' => $newProperty['legal_status'] ?? 'SHM (Hak Milik)',
            'verified' => true,
            'featured' => (bool)($newProperty['featured'] ?? true),
            'highlight_badge' => $newProperty['highlight_badge'] ?? 'Listing Baru',
            'land_size' => (int)($newProperty['land_size'] ?? 200),
            'building_size' => (int)($newProperty['building_size'] ?? 180),
            'floors' => (int)($newProperty['floors'] ?? 2),
            'bedrooms' => (int)($newProperty['bedrooms'] ?? 3),
            'bathrooms' => (int)($newProperty['bathrooms'] ?? 3),
            'orientation' => $newProperty['orientation'] ?? 'Utara',
            'electricity' => $newProperty['electricity'] ?? '4400 VA',
            'water_source' => $newProperty['water_source'] ?? 'PDAM',
            'garage' => $newProperty['garage'] ?? '2 Mobil',
            'year_built' => (string)($newProperty['year_built'] ?? date('Y')),
            'furnishing' => $newProperty['furnishing'] ?? 'Semi Furnished',
            'specs_tags' => $specsTags,
            'image_main' => $newProperty['image_main'] ?? $images[0],
            'images' => $images,
            'features' => $features,
            'description' => $newProperty['description'] ?? 'Hunian eksklusif terkurasi oleh Kumala Property dengan standar spesifikasi premium.',
            'distance_points' => $distancePoints,
            'status' => $newProperty['status'] ?? 'ACTIVE',
        ];

        try {
            $createdProp = Property::create($propData);

            // Populate child photos
            foreach ($images as $idx => $photoUrl) {
                PropertyPhoto::create([
                    'property_id' => $id,
                    'photo_url' => $photoUrl,
                    'caption' => $title . ' - Foto ' . ($idx + 1),
                    'is_main' => ($idx === 0),
                    'sort_order' => $idx,
                ]);
            }

            // Populate child features
            foreach ($features as $feat) {
                PropertyFeature::create([
                    'property_id' => $id,
                    'title' => $feat['title'] ?? 'Fasilitas Unggulan',
                    'desc' => $feat['desc'] ?? '',
                    'icon' => $feat['icon'] ?? 'star',
                ]);
            }

            self::logAction("Penerbitan Listing Baru {$id}: {$title}", 'Admin Operator', 'Published');
            return self::transformProperty($createdProp);
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::addProperty DB Error: ' . $e->getMessage());
            return $propData;
        }
    }

    public static function updatePropertyStatus(string $id, string $status): bool
    {
        try {
            $prop = Property::where('id', $id)->first();
            if ($prop) {
                $prop->status = $status;
                if ($status === 'SOLD') {
                    $prop->highlight_badge = 'TERJUAL';
                } elseif ($status === 'RENTED') {
                    $prop->highlight_badge = 'TERSEWA';
                }
                $prop->save();

                self::logAction("Perubahan status properti {$id} ke {$status}", 'Admin Operator', 'Updated');
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::updatePropertyStatus Error: ' . $e->getMessage());
        }
        return false;
    }

    public static function deleteProperty(string $id): bool
    {
        try {
            $prop = Property::where('id', $id)->first();
            if ($prop) {
                PropertyPhoto::where('property_id', $id)->delete();
                PropertyFeature::where('property_id', $id)->delete();
                $prop->delete();

                self::logAction("Penghapusan listing {$id}", 'Admin Operator', 'Deleted');
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::deleteProperty Error: ' . $e->getMessage());
        }
        return false;
    }

    // =========================================================================
    // 2. CONSIGNMENT SUBMISSIONS (Titip Properti)
    // =========================================================================

    public static function getSubmissions(?string $status = null): array
    {
        try {
            $query = Submission::orderBy('created_at', 'desc');
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            $submissions = $query->get();
            return $submissions->map(fn($s) => self::transformSubmission($s))->toArray();
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getSubmissions fallback: ' . $e->getMessage());
        }

        $all = self::loadFallbackData()['submissions'] ?? [];
        if ($status && $status !== 'all') {
            return array_values(array_filter($all, fn($s) => ($s['status'] ?? '') === $status));
        }
        return $all;
    }

    public static function findSubmissionById(string $id): ?array
    {
        try {
            $sub = Submission::where('id', $id)->first();
            if ($sub) {
                return self::transformSubmission($sub);
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::findSubmissionById fallback: ' . $e->getMessage());
        }

        $all = self::getSubmissions();
        foreach ($all as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null;
    }

    public static function createSubmission(array $input): array
    {
        $count = Submission::count();
        $id = '#TP-' . ($count + 205);

        // 1. Price
        $rawPrice = $input['asking_price'] ?? ($input['price'] ?? '0');
        $priceNum = (float)str_replace(['Rp', '.', ' ', ','], '', (string)$rawPrice);
        if ($priceNum <= 0) $priceNum = 3500000000;

        // 2. Phone
        $rawPhone = $input['owner_phone'] ?? ($input['phone'] ?? '');
        $phoneDigits = preg_replace('/[^0-9]/', '', (string)$rawPhone);
        if (str_starts_with($phoneDigits, '62')) {
            $formattedPhone = '+62 ' . substr($phoneDigits, 2);
        } elseif (str_starts_with($phoneDigits, '0')) {
            $formattedPhone = '+62 ' . substr($phoneDigits, 1);
        } elseif (!empty($phoneDigits)) {
            $formattedPhone = '+62 ' . $phoneDigits;
        } else {
            $formattedPhone = '+62 812-8899-2345';
        }

        // 3. Category & Transaction
        $category = $input['property_category'] ?? ($input['category'] ?? 'Rumah Mewah');
        $trxType = $input['transaction_type'] ?? ($input['trx_type'] ?? 'Jual');
        if ($trxType === 'Keduanya') $trxType = 'Jual';

        // 4. Locations
        $cityRegion = $input['city_region'] ?? 'Jakarta Selatan';
        $specificArea = $input['specific_area'] ?? 'Kawasan Prime';
        $locationStr = $cityRegion . ' - ' . $specificArea;
        $address = $input['full_address'] ?? ($input['address'] ?? ($input['location_address'] ?? 'Alamat lengkap terverifikasi.'));

        // 5. Specs
        $landSize = (int)($input['land_size'] ?? 250);
        $buildingSize = (int)($input['building_size'] ?? 200);
        $bedStr = (string)($input['bedrooms'] ?? '3 KT');
        $bedInt = (int)filter_var($bedStr, FILTER_SANITIZE_NUMBER_INT) ?: 3;
        $bathStr = (string)($input['bathrooms'] ?? '3 KM');
        $bathInt = (int)filter_var($bathStr, FILTER_SANITIZE_NUMBER_INT) ?: 3;

        // 6. Facilities & Legal
        $facilities = (array)($input['facilities'] ?? ['pool', 'furnished', 'carport', 'shm', 'pbg']);
        $legal = in_array('shm', $facilities) ? 'SHM (Hak Milik)' : ($input['legal_status'] ?? 'SHM (Hak Milik)');
        if (stripos($category, 'Apartemen') !== false || stripos($category, 'Penthouse') !== false) {
            $legal = 'Strata Title';
        }

        // 7. Photos
        $defaultPhotos = [
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=800&q=80',
        ];
        $photos = !empty($input['photos']) && is_array($input['photos']) ? array_values($input['photos']) : $defaultPhotos;

        // 8. Description & Title
        $title = !empty($input['property_title']) ? trim($input['property_title']) : (!empty($input['title']) ? trim($input['title']) : "{$category} Eksklusif di {$specificArea}");
        $description = !empty($input['description']) ? trim($input['description']) : "Properti konsinyasi resmi dititipkan oleh pemilik langsung melalui portal Kumala Property.";

        $specsTags = [
            "{$bedInt} Kamar Tidur",
            "LT {$landSize}m²",
            "LB {$buildingSize}m²",
        ];
        if (in_array('pool', $facilities)) {
            $specsTags[2] = 'Private Pool';
        }

        // 9. Automatically create Property Catalog Unit in MySQL
        $newProp = self::addProperty([
            'title' => $title,
            'category' => $category,
            'transaction_type' => $trxType,
            'price' => $priceNum,
            'location_city' => $cityRegion,
            'location_area' => $specificArea,
            'location_address' => $address,
            'legal_status' => $legal,
            'land_size' => $landSize,
            'building_size' => $buildingSize,
            'bedrooms' => $bedInt,
            'bathrooms' => $bathInt,
            'specs_tags' => $specsTags,
            'image_main' => $photos[0],
            'images' => $photos,
            'description' => $description,
            'highlight_badge' => 'Listing Baru',
            'status' => 'REVIEW',
        ]);

        // 10. Create Submission Record in MySQL
        $subModel = Submission::create([
            'id' => $id,
            'property_id' => $newProp['id'],
            'property_slug' => $newProp['slug'],
            'title' => $title,
            'owner_name' => $input['owner_name'] ?? 'Bpk/Ibu Pemilik',
            'owner_phone' => $formattedPhone,
            'owner_email' => $input['owner_email'] ?? 'pemilik@kumalaproperty.id',
            'owner_status' => ($input['ownership_status'] ?? '') === 'agent_official' ? 'Kuasa Jual Resmi' : 'Pemilik Langsung',
            'trx_type' => $trxType,
            'category' => $category,
            'city_region' => $cityRegion,
            'specific_area' => $specificArea,
            'location' => $locationStr,
            'address' => $address,
            'asking_price' => 'Rp ' . number_format($priceNum, 0, ',', '.'),
            'asking_val' => (int)$priceNum,
            'valuation_range' => self::formatValuationRange($priceNum),
            'land_size' => $landSize,
            'building_size' => $buildingSize,
            'bedrooms' => "{$bedInt} KT",
            'bathrooms' => "{$bathInt} KM",
            'electricity' => ($input['electricity'] ?? '4400') . ' VA',
            'legal' => $legal,
            'facilities' => $facilities,
            'photos' => $photos,
            'photos_count' => count($photos),
            'priority' => 'P1 - Hot Asset',
            'status' => 'pending',
            'checklist' => ['identity' => true, 'maps' => true, 'shm_match' => true, 'photos_hd' => true],
            'notes' => $description,
            'time_ago' => 'Baru saja',
            'submitted_at' => now(),
        ]);

        // Priority Action in MySQL
        PriorityAction::create([
            'action_id' => $id,
            'type' => 'listing',
            'title' => 'Pengajuan Titip Baru: ' . $title,
            'owner' => $subModel->owner_name,
            'phone' => $subModel->owner_phone,
            'time_ago' => 'Baru saja',
            'sla_status' => 'warning',
            'sla_badge' => 'SLA Respon < 4 Jam',
            'detail' => 'Harga pengajuan ' . $subModel->asking_price . '. Menunggu verifikasi kurator untuk tayang di katalog publik (' . $newProp['id'] . ').',
            'btn_action_1' => 'Verifikasi SHM BPN',
            'btn_action_2' => 'Tinjau & Kurasi Unit',
        ]);

        self::logAction("Pengajuan Titip Properti Baru {$id} ({$newProp['id']}) dari {$subModel->owner_name}", 'Portal Publik (User)', 'Pending Review');

        return self::transformSubmission($subModel);
    }

    public static function approveSubmission(string $id, string $curatorNotes = ''): ?array
    {
        try {
            $sub = Submission::where('id', $id)->first();
            if ($sub) {
                $sub->status = 'approved';
                if ($curatorNotes) {
                    $sub->notes = $curatorNotes;
                }
                $sub->save();

                self::logAction("Persetujuan kurasi unit {$id}", 'Admin Curator', 'Approved');

                if ($sub->property_id) {
                    $prop = Property::where('id', $sub->property_id)->first();
                    if ($prop) {
                        $prop->status = 'ACTIVE';
                        $prop->save();
                        return self::transformProperty($prop);
                    }
                } else {
                    $prop = Property::where('id', $sub->id)->first();
                    if ($prop) {
                        $prop->status = 'ACTIVE';
                        $prop->save();
                        $sub->property_id = $prop->id;
                        $sub->save();
                        return self::transformProperty($prop);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::approveSubmission Error: ' . $e->getMessage());
        }
        return null;
    }

    public static function updateSubmissionStatus(string $id, string $status, string $notes = ''): bool
    {
        try {
            $sub = Submission::where('id', $id)->first();
            if ($sub) {
                $sub->status = $status;
                if ($notes) {
                    $sub->notes = $notes;
                }
                $sub->save();

                self::logAction("Status pengajuan {$id} diperbarui menjadi {$status}", 'Admin Curator', 'Status Changed');
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::updateSubmissionStatus Error: ' . $e->getMessage());
        }
        return false;
    }

    // =========================================================================
    // 3. INQUIRIES & LEAD PIPELINE CRM
    // =========================================================================

    public static function getInquiries(?string $stage = null): array
    {
        try {
            $query = Lead::orderBy('created_at', 'desc');
            if ($stage && $stage !== 'all') {
                $query->where('stage', $stage);
            }
            $leads = $query->get();
            return $leads->map(fn($l) => self::transformLead($l))->toArray();
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getInquiries fallback: ' . $e->getMessage());
        }

        $all = self::loadFallbackData()['inquiries'] ?? [];
        if ($stage && $stage !== 'all') {
            return array_values(array_filter($all, fn($i) => strtolower($i['stage'] ?? '') === strtolower($stage)));
        }
        return $all;
    }

    public static function createInquiry(array $input): array
    {
        $count = Lead::count();
        $id = '#IQ-' . ($count + 2210);

        $propertyId = $input['property_id'] ?? '#KP-8821';
        $propertyTitle = $input['property_title'] ?? 'Properti Terpilih Kumala';
        $propertyPrice = $input['property_price'] ?? 'Rp 15 Miliar';

        if (!empty($input['property_slug'])) {
            $prop = self::findPropertyBySlug($input['property_slug']);
            if ($prop) {
                $propertyId = $prop['id'];
                $propertyTitle = $prop['title'];
                $propertyPrice = $prop['price_short'];
            }
        }

        $scheme = $input['payment_scheme'] ?? '';
        $surveyDate = $input['survey_date'] ?? '';
        $userNotes = $input['notes'] ?? ($input['message'] ?? '');

        $quoteParts = [];
        if ($scheme) $quoteParts[] = "Rencana: {$scheme}";
        if ($surveyDate) $quoteParts[] = "Tgl Survei: {$surveyDate}";
        if ($userNotes) $quoteParts[] = $userNotes;
        $finalQuote = !empty($quoteParts) ? implode(' | ', $quoteParts) : 'Menanyakan jadwal survei privat dan legalitas lengkap unit.';

        $lead = Lead::create([
            'id' => $id,
            'property_id' => $propertyId,
            'property_title' => $propertyTitle,
            'property_price' => $propertyPrice,
            'client_name' => $input['name'] ?? ($input['client_name'] ?? 'Calon Pembeli VIP'),
            'client_phone' => $input['phone'] ?? '+62 812-3456-7890',
            'client_email' => $input['email'] ?? 'klien@investor.id',
            'buyer_category' => $input['buyer_type'] ?? 'Calon Pembeli Eksklusif (HNWIs)',
            'buyer_type' => $input['buyer_type'] ?? 'Calon Pembeli Eksklusif (HNWIs)',
            'quote' => $finalQuote,
            'message' => $finalQuote,
            'stage' => 'Baru',
            'assigned_agent' => 'Private Client Concierge',
            'assigned_to' => 'Private Client Concierge',
            'sla_badge' => 'SLA Hijau (< 15 mnt)',
            'sla_status' => 'urgent',
            'time_ago' => 'Baru saja',
        ]);

        PriorityAction::create([
            'action_id' => $id,
            'type' => 'inquiry',
            'title' => 'Inquiry Baru: ' . $propertyTitle,
            'owner' => $lead->client_name,
            'phone' => $lead->client_phone,
            'time_ago' => 'Baru saja',
            'sla_status' => 'urgent',
            'sla_badge' => 'SLA Respon < 15 mnt',
            'detail' => $finalQuote,
            'btn_action_1' => 'Hubungi via WhatsApp',
            'btn_action_2' => 'Jadwalkan Survei',
        ]);

        self::logAction("Inquiry Baru {$id} untuk properti {$propertyId} dari {$lead->client_name}", 'Portal Publik (User)', 'Lead Created');

        return self::transformLead($lead);
    }

    public static function updateInquiryStage(string $id, string $newStage): bool
    {
        try {
            $lead = Lead::where('id', $id)->first();
            if ($lead) {
                $lead->stage = $newStage;
                $lead->save();

                self::logAction("Tahapan Lead {$id} diubah ke {$newStage}", 'Admin Concierge', 'Stage Advanced');
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::updateInquiryStage Error: ' . $e->getMessage());
        }
        return false;
    }

    public static function getSurveysToday(): array
    {
        try {
            $surveys = Survey::orderBy('created_at', 'desc')->get();
            if ($surveys->isNotEmpty()) {
                return $surveys->map(fn($s) => [
                    'time' => $s->survey_time,
                    'property' => $s->property_title,
                    'client' => $s->client_name,
                    'agent' => $s->agent_name,
                    'status' => $s->status,
                ])->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getSurveysToday fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['surveys_today'] ?? [];
    }

    public static function addSurvey(array $survey): array
    {
        try {
            $item = Survey::create([
                'property_title' => $survey['property'] ?? 'Unit Terpilih',
                'client_name' => $survey['client'] ?? 'Klien VIP',
                'agent_name' => $survey['agent'] ?? 'Tim Concierge Kumala',
                'survey_time' => $survey['time'] ?? '14:00 WIB',
                'survey_date' => now()->toDateString(),
                'status' => 'Terkonfirmasi',
            ]);

            return [
                'time' => $item->survey_time,
                'property' => $item->property_title,
                'client' => $item->client_name,
                'agent' => $item->agent_name,
                'status' => $item->status,
            ];
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::addSurvey Error: ' . $e->getMessage());
            return $survey;
        }
    }

    // =========================================================================
    // 4. TRANSACTIONS & CLOSINGS
    // =========================================================================

    public static function getTransactions(): array
    {
        try {
            $transactions = Transaction::orderBy('created_at', 'desc')->get();
            if ($transactions->isNotEmpty()) {
                return $transactions->map(fn($t) => self::transformTransaction($t))->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getTransactions fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['transactions'] ?? [];
    }

    public static function createTransaction(array $input): array
    {
        $count = Transaction::count();
        $id = '#CLS-2025-0' . ($count + 32);

        $dealVal = (int)preg_replace('/[^0-9]/', '', (string)($input['deal_price'] ?? '9500000000')) ?: 9500000000;

        $t = Transaction::create([
            'id' => $id,
            'date' => date('d M Y'),
            'property_id' => $input['property_id'] ?? '#KP-8821',
            'property_title' => $input['property_title'] ?? 'Properti Closing',
            'property_image' => $input['property_image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
            'location' => $input['location'] ?? 'Jakarta Selatan',
            'trx_type' => $input['trx_type'] ?? 'TERJUAL - JUAL BELI',
            'asking_price' => $input['asking_price'] ?? 'Rp 10.000.000.000',
            'deal_price' => $input['deal_price'] ?? 'Rp 9.500.000.000',
            'deal_val' => $dealVal,
            'diff_pct' => $input['diff_pct'] ?? '-5.0%',
            'parties' => $input['parties'] ?? 'Penjual & Pembeli Terverifikasi PPAT',
            'commission_val' => $input['commission_val'] ?? 'Rp 237.500.000 (2.5%)',
            'notary' => $input['notary'] ?? 'Notaris Rekanan Kumala PPAT',
            'status_doc' => 'AJB Selesai',
            'days_on_market' => ($input['days_on_market'] ?? 30) . ' Hari',
        ]);

        if (!empty($input['property_id'])) {
            $isSewa = stripos($t->trx_type, 'SEWA') !== false;
            self::updatePropertyStatus($input['property_id'], $isSewa ? 'RENTED' : 'SOLD');
        }

        self::logAction("Transaksi Closing {$id} Dicatatkan untuk unit {$t->property_id}", 'Admin Closing Dept', 'Deal Closed');

        return self::transformTransaction($t);
    }

    // =========================================================================
    // 5. SYSTEM SETTINGS & MASTER CONFIG
    // =========================================================================

    public static function getSettings(): array
    {
        $defaults = [
            'brand_name' => 'KUMALA PROPERTY',
            'brand_subtitle' => 'JUAL & SEWA PROPERTI',
            'brand_logo_url' => '',
            'site_tagline' => 'Luxury Real Estate Atelier & Integrated Property Management Platform',
            'footer_bio' => 'Kumala Property adalah platform real estate mewah terdepan di Indonesia. Portofolio hunian prestisius, villa tropis, penthouse, dan ruang komersial prima dengan kurasi arsitektural ketat dan legalitas 100% terverifikasi notaris.',
            'footer_copyright' => '© 2026 Kumala Property. Seluruh hak cipta dilindungi undang-undang.',
            'hotline_wa' => '+62 811-9988-7711',
            'hotline_wa_numeric' => '6281199887711',
            'hotline_admin' => '+62 811-9988-7711',
            'email_cs' => 'cs@kumalaproperty.id',
            'email_official' => 'concierge@kumalaproperty.id',
            'email_business' => 'vip@kumalaproperty.co.id',
            'address' => 'Kumala Heritage Suite, Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190',
            'address_hq' => 'Kumala Heritage Suite, Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190',
            'address_bali' => 'Jl. Pantai Suluban No. 8, Pecatu, Uluwatu, Badung, Bali 80361',
            'office_hours' => 'Setiap Hari: 08:00 - 20:00 WIB',
            'hours_weekday' => '08.30 - 18.00 WIB',
            'hours_saturday' => '09.00 - 15.00 WIB',
            'hours_sunday' => 'Dengan Janji Temu',
            'social_instagram' => '@kumala.realty',
            'social_youtube' => 'Kumala Property Official',
            'social_facebook' => 'Kumala Realty Indonesia',
            'home_hero_title' => 'Temukan Hunian Eksklusif & Aset Bernilai Abadi di Indonesia',
            'home_hero_desc' => 'Menghubungkan pemilik properti bernilai tinggi dengan investor & pembeli eksklusif. Setiap unit diaudit dengan verifikasi dokumen legalitas notarial dan kurasi arsitektural menyeluruh.',
            'home_hero_bg_image' => '',
            'home_hero_bg_overlay' => '0.45',
            'home_hero_bg_position' => 'center',
            'home_hero_text_theme' => 'auto',
            'home_trust_badge_1' => '100% SHM/HGB Terverifikasi Notaris',
            'home_trust_badge_2' => '< 15 Menit Respon Private Concierge',
            'home_trust_badge_3' => 'Zero Hidden Mark-up Fee',
            'home_trust_badge_4' => 'Discretionary Off-Market Listing',
            'home_curated_title' => 'Koleksi Pilihan Kurator',
            'home_curated_subtitle' => 'Portofolio hunian residensial & komersial dengan standar arsitektural tertinggi',
            'home_consignment_badge' => 'LAYANAN KONSINYASI & PEMASARAN RESMI',
            'home_consignment_title' => 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas',
            'home_consignment_desc' => 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas tuntas tanpa biaya di muka.',
            'home_consignment_cta' => 'Titip Properti Sekarang',
            'home_consignment_wa_cta' => 'Tanya Advisor via WA',
            'home_consult_title' => 'Tidak Menemukan Kriteria yang Dicari?',
            'home_consult_desc' => 'Konsultasikan preferensi properti impian Anda secara privat bersama Property Advisor kami.',
            'home_consult_cta' => 'Konsultasi via WhatsApp',
            'explore_badge' => 'KOLEKSI PILIHAN KUMALA',
            'explore_title' => 'Eksplorasi Properti',
            'explore_desc' => 'Temukan hunian eksklusif, villa tropis, apartemen modern, dan lahan strategis di kawasan prima.',
            'explore_offmarket_title' => 'Mencari Properti Off-Market / Private Collection?',
            'explore_offmarket_desc' => 'Sebagian besar aset paling bergengsi tidak dipublikasikan secara umum demi menjaga privasi pemilik.',
            'detail_concierge_title' => 'Tertarik dengan Properti Ini?',
            'detail_concierge_desc' => 'Hubungi Private Client Concierge kami untuk jadwal survei privat atau dokumen legalitas lengkap.',
            'detail_concierge_sla' => 'SLA Respon < 15 Menit • 7 Hari Seminggu',
            'titip_badge' => 'LAYANAN KONSINYASI & PEMASARAN RESMI',
            'titip_title' => 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas',
            'titip_desc' => 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas transaksi tuntas tanpa biaya di muka.',
            'titip_benefit_1' => 'Valuasi & Konsultasi Gratis',
            'titip_benefit_2' => 'Jejaring Investor Terverifikasi',
            'titip_benefit_3' => 'Transparan & Legalitas Aman BPN',
            'favorites_badge' => 'KOLEKSI PRIBADI TERSIMPAN',
            'favorites_title' => 'Properti Favorit Saya',
            'favorites_desc' => 'Daftar unit properti pilihan yang Anda simpan di perangkat ini.',
            'favorites_cta' => 'Konsultasi Semua Favorit via WA',
            'about_badge' => 'ATELIER REAL ESTATE INDEPENDEN',
            'about_hero_title' => 'Kurasi Presisi untuk Koleksi Aset Bernilai Abadi',
            'about_hero_desc' => 'Kumala Property lahir dari keyakinan mendasar bahwa transaksi properti prestisius bukan sekadar nominal penawaran, melainkan tentang integritas arsitektural dan legalitas tanpa celah.',
            'about_pillar_title' => 'Tiga Pilar Layanan Kumala',
            'about_pillar_subtitle' => 'Landasan etika kerja yang membedakan kami dalam mendampingi klien.',
            'about_p1_title' => '1. Transparansi Tanpa Kompromi',
            'about_p1_desc' => 'Tidak ada harga titipan atau manipulasi margin. Penjual dan pembeli mendapatkan keterbukaan valuasi objektif.',
            'about_p2_title' => '2. Kurasi Estetika & Keandalan Struktur',
            'about_p2_desc' => 'Setiap unit melalui inspeksi fisik mendalam terhadap material bangunan, pencahayaan alami, dan zonasi lingkungan.',
            'about_p3_title' => '3. Pendampingan Privat & Obyektif',
            'about_p3_desc' => 'Private Client Concierge kami mendampingi dari awal hingga penandatanganan AJB.',
            'standard_commission_pct' => '2.5',
            'standard_commission_rent_pct' => '5.0',
            'auto_watermark' => true,
            'whatsapp_notify' => true,
            'email_notify' => true,
        ];

        try {
            $dbSettings = Setting::all()->pluck('value', 'key')->toArray();
            return array_merge($defaults, $dbSettings);
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getSettings fallback: ' . $e->getMessage());
        }

        return $defaults;
    }

    public static function updateSettings(array $newSettings): array
    {
        try {
            if (!empty($newSettings['hotline_wa'])) {
                $newSettings['hotline_wa_numeric'] = preg_replace('/[^0-9]/', '', $newSettings['hotline_wa']);
            }

            foreach ($newSettings as $k => $v) {
                Setting::updateOrCreate(
                    ['key' => $k],
                    ['value' => is_array($v) ? json_encode($v) : (string)$v, 'group' => 'general']
                );
            }

            self::logAction('Pembaruan Konfigurasi Sistem & Konten Portal Publik', 'Super Admin', 'Config Saved');
            return self::getSettings();
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::updateSettings Error: ' . $e->getMessage());
            return $newSettings;
        }
    }

    public static function saveSettings(array $newSettings): array
    {
        return self::updateSettings($newSettings);
    }

    public static function getCategories(): array
    {
        try {
            $categories = Category::where('is_active', true)->get();
            if ($categories->isNotEmpty()) {
                return $categories->map(fn($c) => [
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'icon' => $c->icon ?: 'home',
                    'count' => $c->count ?: 0,
                    'subtypes' => $c->subtypes,
                    'active' => (bool)$c->is_active,
                ])->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getCategories fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['categories'] ?? [];
    }

    public static function addCategory(array $data): array
    {
        $name = trim($data['name'] ?? 'Kategori Baru');
        $slug = Str::slug($name);
        $subtypes = trim($data['subtypes'] ?? '');

        try {
            $cat = Category::create([
                'name' => $name,
                'slug' => $slug,
                'icon' => $data['icon'] ?? 'home',
                'count' => 0,
                'subtypes' => $subtypes,
                'is_active' => true,
            ]);

            self::logAction("Kategori properti baru '{$name}' ditambahkan oleh admin.", 'Admin Operator', 'Created');

            return [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'subtypes' => $cat->subtypes,
                'active' => true,
                'count' => 0,
            ];
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::addCategory Error: ' . $e->getMessage());
            return ['name' => $name, 'subtypes' => $subtypes, 'active' => true, 'count' => 0];
        }
    }

    public static function getRegions(): array
    {
        try {
            $regions = Region::where('is_active', true)->get();
            if ($regions->isNotEmpty()) {
                return $regions->map(fn($r) => [
                    'name' => $r->name,
                    'slug' => $r->slug ?: Str::slug($r->name),
                    'city' => $r->city,
                    'area' => $r->area,
                    'image' => $r->image,
                    'active' => (bool)$r->is_active,
                ])->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getRegions fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['regions'] ?? [];
    }

    // =========================================================================
    // 6. DASHBOARD METRICS & AUDIT LOGS
    // =========================================================================

    public static function getDashboardMetrics(): array
    {
        try {
            $activeUnits = Property::where('status', 'ACTIVE')->count();
            $jualUnits = Property::where('status', 'ACTIVE')->where('transaction_type', 'Jual')->count();
            $sewaUnits = Property::where('status', 'ACTIVE')->where('transaction_type', 'Sewa')->count();

            $pendingSubmissions = Submission::where('status', 'pending')->count();
            $newInquiries = Lead::where('stage', 'Baru')->count();
            $closingUnits = Transaction::count();
            $closingSum = Transaction::sum('deal_val');

            $closingFormatted = $closingSum > 0 ? self::formatShortPrice($closingSum) : 'Rp 18.4 Miliar';

            return [
                'active_units' => $activeUnits,
                'active_jual' => $jualUnits,
                'active_sewa' => $sewaUnits,
                'active_growth' => '+' . min($activeUnits, 8) . ' unit aktif',
                'pending_review' => $pendingSubmissions,
                'new_inquiries' => $newInquiries,
                'avg_sla_minutes' => 15,
                'closing_month_units' => $closingUnits,
                'closing_month_val' => $closingFormatted,
                'closing_target_pct' => 88,
            ];
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getDashboardMetrics fallback: ' . $e->getMessage());
        }

        return [
            'active_units' => 12,
            'active_jual' => 8,
            'active_sewa' => 4,
            'active_growth' => '+8 unit aktif',
            'pending_review' => 4,
            'new_inquiries' => 6,
            'avg_sla_minutes' => 18,
            'closing_month_units' => 4,
            'closing_month_val' => 'Rp 18.4 Miliar',
            'closing_target_pct' => 85,
        ];
    }

    public static function getPriorityActions(): array
    {
        $actions = [];

        try {
            // 1. Pending Submissions
            $pendingSubs = Submission::where('status', 'pending')->orderBy('created_at', 'desc')->take(5)->get();
            foreach ($pendingSubs as $sub) {
                $actions[] = [
                    'id' => $sub->id,
                    'category' => 'listing',
                    'type' => 'listing',
                    'title' => $sub->title,
                    'owner' => 'Pemilik: ' . ($sub->owner_name ?? 'Klien Titip'),
                    'phone' => $sub->owner_phone ?? '',
                    'time_ago' => $sub->time_ago ?? 'Baru saja',
                    'sla_status' => 'warning',
                    'sla_badge' => 'LISTING TITIP BARU',
                    'tag_badge' => 'LISTING TITIP BARU',
                    'tag_class' => 'badge-cyan',
                    'detail' => 'Harga: ' . ($sub->asking_price ?? 'Rp -') . ' | Lokasi: ' . ($sub->location ?? ''),
                    'image' => !empty($sub->photos) ? $sub->photos[0] : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80',
                    'btn_action_1' => 'Verifikasi SHM',
                    'btn_url_1' => route('admin.submissions'),
                    'btn_action_2' => 'Tinjau & Kurasi',
                    'btn_url_2' => route('admin.submissions'),
                ];
            }

            // 2. New Inquiries
            $newInqs = Lead::where('stage', 'Baru')->orderBy('created_at', 'desc')->take(5)->get();
            foreach ($newInqs as $inq) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $inq->client_phone ?? '');
                if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);

                $actions[] = [
                    'id' => $inq->id,
                    'category' => 'inquiry',
                    'type' => 'inquiry',
                    'title' => 'Inquiry: ' . ($inq->property_title ?? 'Listing Minat'),
                    'owner' => 'Klien: ' . ($inq->client_name ?? 'Klien VIP'),
                    'phone' => $inq->client_phone ?? '',
                    'time_ago' => $inq->time_ago ?? 'Baru saja',
                    'sla_status' => 'urgent',
                    'sla_badge' => 'INQUIRY BELI SEGERA',
                    'tag_badge' => 'INQUIRY BELI SEGERA',
                    'tag_class' => 'badge-peach',
                    'detail' => $inq->quote ?? 'Permintaan info unit & jadwal survei.',
                    'image' => 'https://images.unsplash.com/photo-1541971875076-8f970d573be6?auto=format&fit=crop&w=300&q=80',
                    'btn_action_1' => 'Buka Leads',
                    'btn_url_1' => route('admin.inquiries'),
                    'btn_action_2' => 'WhatsApp Klien',
                    'btn_url_2' => 'https://wa.me/' . $cleanPhone,
                ];
            }

            // 3. Stored Priority Actions from table
            $dbActions = PriorityAction::where('is_resolved', false)->orderBy('created_at', 'desc')->take(10)->get();
            foreach ($dbActions as $sa) {
                $exists = false;
                foreach ($actions as $act) {
                    if (($act['id'] ?? '') === $sa->action_id) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $cat = $sa->type ?? 'listing';
                    $actions[] = [
                        'id' => $sa->action_id,
                        'category' => $cat,
                        'type' => $cat,
                        'title' => $sa->title,
                        'owner' => $sa->owner,
                        'phone' => $sa->phone,
                        'time_ago' => $sa->time_ago ?? 'Baru saja',
                        'sla_status' => $sa->sla_status,
                        'sla_badge' => $sa->sla_badge,
                        'tag_badge' => $sa->sla_badge,
                        'tag_class' => ($cat === 'inquiry') ? 'badge-peach' : (($cat === 'closing') ? 'badge-teal' : 'badge-cyan'),
                        'detail' => $sa->detail,
                        'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=300&q=80',
                        'btn_action_1' => $sa->btn_action_1 ?: 'Tinjau Unit',
                        'btn_url_1' => ($cat === 'inquiry') ? route('admin.inquiries') : route('admin.submissions'),
                        'btn_action_2' => $sa->btn_action_2 ?: 'Hubungi Klien',
                        'btn_url_2' => route('admin.dashboard'),
                    ];
                }
            }

            if (!empty($actions)) {
                return $actions;
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getPriorityActions fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['priority_actions'] ?? [];
    }

    public static function getAuditLogs(): array
    {
        try {
            $logs = AuditLog::orderBy('created_at', 'desc')->take(10)->get();
            if ($logs->isNotEmpty()) {
                return $logs->map(fn($l) => [
                    'timestamp' => $l->timestamp_display ?? ($l->created_at ? $l->created_at->format('H:i') . ' WIB' : 'Hari ini'),
                    'action' => $l->action,
                    'user' => $l->user,
                    'status' => $l->status,
                ])->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('KumalaDataHub::getAuditLogs fallback: ' . $e->getMessage());
        }

        return self::loadFallbackData()['audit_logs'] ?? [];
    }

    public static function logAction(string $action, string $user, string $status): void
    {
        try {
            AuditLog::create([
                'timestamp_display' => date('H:i') . ' WIB',
                'action' => $action,
                'user' => $user,
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            Log::error('KumalaDataHub::logAction Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // TRANSFORMERS (Converting Eloquent Models to View-Ready Arrays)
    // =========================================================================

    public static function transformProperty(Property $p): array
    {
        $images = is_array($p->images) && !empty($p->images) ? array_values(array_filter($p->images)) : [];
        if (empty($images)) {
            $photosRel = $p->relationLoaded('photos') ? $p->getRelation('photos') : null;
            if ($photosRel instanceof \Illuminate\Support\Collection && $photosRel->isNotEmpty()) {
                $images = $photosRel->pluck('photo_url')->filter()->values()->toArray();
            } elseif (!$p->relationLoaded('photos') && $p->photos()->exists()) {
                $images = $p->photos()->pluck('photo_url')->filter()->values()->toArray();
            } elseif ($p->image_main) {
                $images = [$p->image_main];
            }
        }

        $features = [];
        $featuresRel = $p->relationLoaded('features') ? $p->getRelation('features') : null;
        if ($featuresRel instanceof \Illuminate\Support\Collection && $featuresRel->isNotEmpty()) {
            $features = $featuresRel->map(fn($f) => [
                'title' => is_object($f) ? ($f->title ?? '') : ($f['title'] ?? ''),
                'desc' => is_object($f) ? ($f->desc ?? '') : ($f['desc'] ?? ''),
                'icon' => (is_object($f) ? ($f->icon ?? '') : ($f['icon'] ?? '')) ?: 'shield',
            ])->toArray();
        } elseif (is_array($p->features) && !empty($p->features)) {
            $features = array_values(array_map(fn($f) => [
                'title' => is_object($f) ? ($f->title ?? '') : ($f['title'] ?? ''),
                'desc' => is_object($f) ? ($f->desc ?? '') : ($f['desc'] ?? ''),
                'icon' => (is_object($f) ? ($f->icon ?? '') : ($f['icon'] ?? '')) ?: 'shield',
            ], $p->features));
        } elseif (!$p->relationLoaded('features') && $p->features()->exists()) {
            $features = $p->features()->get()->map(fn($f) => [
                'title' => $f->title,
                'desc' => $f->desc,
                'icon' => $f->icon ?: 'shield',
            ])->toArray();
        }

        $specsTags = is_array($p->specs_tags) ? $p->specs_tags : (json_decode($p->specs_tags ?? '[]', true) ?: []);
        $distancePoints = is_array($p->distance_points) ? $p->distance_points : (json_decode($p->distance_points ?? '[]', true) ?: []);

        return [
            'id' => $p->id,
            'slug' => $p->slug,
            'title' => $p->title,
            'category' => $p->category,
            'transaction_type' => $p->transaction_type,
            'price' => (float)$p->price,
            'price_formatted' => $p->price_formatted ?: ('Rp ' . number_format($p->price, 0, ',', '.')),
            'price_short' => $p->price_short ?: self::formatShortPrice((float)$p->price),
            'price_label' => $p->price_label ?: ($p->transaction_type === 'Sewa' ? 'Biaya Sewa' : 'Harga Penawaran Eksklusif'),
            'price_notes' => $p->price_notes ?: 'Legalitas Lengkap • Siap Transaksi',
            'location_city' => $p->location_city,
            'location_area' => $p->location_area,
            'location_address' => $p->location_address,
            'legal_status' => $p->legal_status ?: 'SHM (Hak Milik)',
            'verified' => (bool)$p->verified,
            'featured' => (bool)$p->featured,
            'highlight_badge' => $p->highlight_badge,
            'land_size' => (int)$p->land_size,
            'building_size' => (int)$p->building_size,
            'floors' => (int)$p->floors,
            'bedrooms' => (int)$p->bedrooms,
            'bathrooms' => (int)$p->bathrooms,
            'orientation' => $p->orientation ?: 'Utara',
            'electricity' => $p->electricity ?: '4400 VA',
            'water_source' => $p->water_source ?: 'PDAM',
            'garage' => $p->garage ?: '2 Mobil',
            'year_built' => (string)($p->year_built ?: date('Y')),
            'furnishing' => $p->furnishing ?: 'Semi Furnished',
            'specs_tags' => $specsTags,
            'image_main' => $p->image_main ?: ($images[0] ?? ''),
            'images' => $images,
            'description' => $p->description,
            'features' => $features,
            'distance_points' => $distancePoints,
            'status' => $p->status ?: 'ACTIVE',
            'created_at' => $p->created_at ? $p->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
        ];
    }

    public static function transformSubmission(Submission $s): array
    {
        $photos = is_array($s->photos) ? $s->photos : (json_decode($s->photos ?? '[]', true) ?: []);
        $checklist = is_array($s->checklist) ? $s->checklist : (json_decode($s->checklist ?? '[]', true) ?: [
            'identity' => true, 'maps' => true, 'shm_match' => true, 'photos_hd' => true
        ]);
        $facilities = is_array($s->facilities) ? $s->facilities : (json_decode($s->facilities ?? '[]', true) ?: []);

        return [
            'id' => $s->id,
            'property_id' => $s->property_id,
            'property_slug' => $s->property_slug,
            'title' => $s->title,
            'owner' => $s->owner_name,
            'owner_name' => $s->owner_name,
            'phone' => $s->owner_phone,
            'owner_phone' => $s->owner_phone,
            'email' => $s->owner_email,
            'owner_email' => $s->owner_email,
            'price' => $s->asking_price ?: ('Rp ' . number_format($s->asking_val, 0, ',', '.')),
            'asking_val' => (int)$s->asking_val,
            'valuation_range' => $s->valuation_range ?: self::formatValuationRange((float)$s->asking_val),
            'trx_type' => $s->trx_type,
            'category' => $s->category,
            'city_region' => $s->city_region,
            'specific_area' => $s->specific_area,
            'location' => $s->location,
            'address' => $s->address,
            'land_size' => (int)$s->land_size,
            'building_size' => (int)$s->building_size,
            'bedrooms' => $s->bedrooms,
            'bathrooms' => $s->bathrooms,
            'electricity' => $s->electricity,
            'legal' => $s->legal,
            'ownership' => $s->owner_status,
            'facilities' => $facilities,
            'time_ago' => $s->time_ago ?: 'Baru saja',
            'status' => $s->status,
            'priority' => $s->priority,
            'photos_count' => (int)($s->photos_count ?: count($photos)),
            'photos' => $photos,
            'checklist' => $checklist,
            'notes' => $s->notes,
            'created_at' => $s->created_at ? $s->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
        ];
    }

    public static function transformLead(Lead $l): array
    {
        return [
            'id' => $l->id,
            'client_name' => $l->client_name,
            'phone' => $l->client_phone,
            'email' => $l->client_email,
            'buyer_type' => $l->buyer_category ?: ($l->buyer_type ?: 'HNWIs'),
            'time_ago' => $l->time_ago ?: 'Baru saja',
            'sla_badge' => $l->sla_badge ?: 'SLA Hijau (< 15 mnt)',
            'sla_status' => $l->sla_status ?: 'urgent',
            'stage' => $l->stage,
            'property_id' => $l->property_id,
            'property_title' => $l->property_title,
            'property_price' => $l->property_price,
            'quote' => $l->quote ?: $l->message,
            'assigned_to' => $l->assigned_agent ?: ($l->assigned_to ?: 'Private Client Concierge'),
            'created_at' => $l->created_at ? $l->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
        ];
    }

    public static function transformTransaction(Transaction $t): array
    {
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
            'deal_val' => (int)$t->deal_val,
            'diff_pct' => $t->diff_pct,
            'parties' => $t->parties,
            'commission_val' => $t->commission_val,
            'notary' => $t->notary,
            'status_doc' => $t->status_doc,
            'days_on_market' => $t->days_on_market,
        ];
    }

    public static function formatShortPrice(float $p): string
    {
        if ($p >= 1000000000) {
            $val = $p / 1000000000;
            return 'Rp ' . (round($val, 2) == round($val) ? number_format($val, 0) : number_format($val, 1)) . ' Miliar';
        } elseif ($p >= 1000000) {
            $val = $p / 1000000;
            return 'Rp ' . number_format($val, 0) . ' Juta';
        }
        return 'Rp ' . number_format($p, 0, ',', '.');
    }

    public static function formatValuationRange(float $p): string
    {
        $low = $p * 0.95;
        $high = $p * 1.08;
        return self::formatShortPrice($low) . ' - ' . self::formatShortPrice($high);
    }
}
