<?php

namespace App\Services;

class KumalaDataHub
{
    protected static ?string $storageFile = null;

    /**
     * Get path to json storage file
     */
    protected static function getStoragePath(): string
    {
        if (self::$storageFile === null) {
            self::$storageFile = storage_path('app/kumala_store.json');
        }
        return self::$storageFile;
    }

    /**
     * Load all data from store (or initialize with default dataset)
     */
    public static function loadData(): array
    {
        $path = self::getStoragePath();
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $data = json_decode($content, true);
            if (is_array($data) && isset($data['properties'], $data['submissions'], $data['inquiries'], $data['settings'])) {
                return $data;
            }
        }

        // Initialize default seed dataset
        $data = self::getDefaultSeedData();
        self::saveData($data);
        return $data;
    }

    /**
     * Save data atomically
     */
    public static function saveData(array $data): bool
    {
        $path = self::getStoragePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) !== false;
    }

    // =========================================================================
    // 1. PROPERTIES MANAGEMENT
    // =========================================================================

    public static function getAllProperties(bool $activeOnly = false): array
    {
        $data = self::loadData();
        $properties = $data['properties'] ?? [];

        if ($activeOnly) {
            return array_values(array_filter($properties, function ($p) {
                return ($p['status'] ?? 'ACTIVE') === 'ACTIVE';
            }));
        }

        return $properties;
    }

    public static function findPropertyBySlug(string $slug): ?array
    {
        $all = self::getAllProperties();
        foreach ($all as $prop) {
            if (($prop['slug'] ?? '') === $slug) {
                return $prop;
            }
        }
        // Fallback to first property if not found
        return $all[0] ?? null;
    }

    public static function findPropertyById(string $id): ?array
    {
        $all = self::getAllProperties();
        foreach ($all as $prop) {
            if (($prop['id'] ?? '') === $id) {
                return $prop;
            }
        }
        return null;
    }

    public static function filterProperties(?string $type = null, ?string $category = null, ?string $location = null, ?string $priceRange = null): array
    {
        $items = self::getAllProperties(true);

        return array_values(array_filter($items, function ($item) use ($type, $category, $location, $priceRange) {
            if ($type && $type !== 'Semua') {
                if (strtolower($item['transaction_type'] ?? '') !== strtolower($type)) {
                    return false;
                }
            }
            if ($category && $category !== 'Semua Tipe Properti' && $category !== 'Semua') {
                if (stripos($item['category'] ?? '', $category) === false && stripos($category, $item['category'] ?? '') === false) {
                    return false;
                }
            }
            if ($location && $location !== 'Semua Wilayah' && $location !== 'Semua') {
                $city = $item['location_city'] ?? '';
                $area = $item['location_area'] ?? '';
                if (stripos($city, $location) === false && stripos($area, $location) === false) {
                    return false;
                }
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
        $data = self::loadData();
        
        $idNumber = 8820 + count($data['properties']) + 1;
        $id = '#KP-' . $idNumber;
        
        $title = $newProperty['title'] ?? 'Properti Baru Kumala';
        $slug = \Illuminate\Support\Str::slug($title) . '-' . rand(100, 999);

        $property = array_merge([
            'id' => $id,
            'slug' => $slug,
            'title' => $title,
            'category' => $newProperty['category'] ?? 'Rumah Mewah',
            'transaction_type' => $newProperty['transaction_type'] ?? 'Jual',
            'price' => (float)($newProperty['price'] ?? 1000000000),
            'price_formatted' => 'Rp ' . number_format((float)($newProperty['price'] ?? 1000000000), 0, ',', '.'),
            'price_short' => self::formatShortPrice((float)($newProperty['price'] ?? 1000000000)),
            'price_label' => ($newProperty['transaction_type'] ?? 'Jual') === 'Jual' ? 'Harga Penawaran Eksklusif' : 'Biaya Sewa',
            'price_notes' => 'Legalitas Lengkap • Siap Transaksi',
            'location_city' => $newProperty['location_city'] ?? 'Jakarta Selatan',
            'location_area' => $newProperty['location_area'] ?? 'Kebayoran Baru',
            'location_address' => $newProperty['location_address'] ?? 'Jakarta Selatan, DKI Jakarta',
            'legal_status' => $newProperty['legal_status'] ?? 'SHM (Hak Milik)',
            'verified' => true,
            'featured' => true,
            'highlight_badge' => 'Listing Baru',
            'land_size' => (int)($newProperty['land_size'] ?? 200),
            'building_size' => (int)($newProperty['building_size'] ?? 180),
            'floors' => (int)($newProperty['floors'] ?? 2),
            'bedrooms' => (int)($newProperty['bedrooms'] ?? 3),
            'bathrooms' => (int)($newProperty['bathrooms'] ?? 3),
            'orientation' => $newProperty['orientation'] ?? 'Utara',
            'electricity' => $newProperty['electricity'] ?? '4400 VA',
            'water_source' => 'PDAM',
            'garage' => '2 Mobil',
            'year_built' => date('Y'),
            'furnishing' => $newProperty['furnishing'] ?? 'Semi Furnished',
            'specs_tags' => [
                ($newProperty['bedrooms'] ?? 3) . ' Kamar Tidur',
                'LT ' . ($newProperty['land_size'] ?? 200) . 'm²',
                'LB ' . ($newProperty['building_size'] ?? 180) . 'm²'
            ],
            'image_main' => $newProperty['image_main'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'images' => $newProperty['images'] ?? [
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
            ],
            'description' => $newProperty['description'] ?? 'Hunian eksklusif terkurasi oleh Kumala Property dengan standar spesifikasi premium dan legalitas aman.',
            'features' => [
                ['title' => 'Keamanan 24 Jam', 'desc' => 'One gate security system.', 'icon' => 'shield'],
                ['title' => 'Akses Strategis', 'desc' => 'Dekat pusat bisnis dan area komersial.', 'icon' => 'map-pin']
            ],
            'distance_points' => [
                ['time' => '10 Menit', 'place' => 'Pusat Kota / CBD'],
                ['time' => '25 Menit', 'place' => 'Akses Tol / Bandara'],
            ],
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s'),
        ], $newProperty);

        // Prepend to properties so it appears on top
        array_unshift($data['properties'], $property);

        // Add audit trail
        self::appendAuditLog($data, 'Penerbitan Listing Baru ' . $id, 'Admin Operator', 'Published');

        self::saveData($data);
        return $property;
    }

    public static function updatePropertyStatus(string $id, string $status): bool
    {
        $data = self::loadData();
        foreach ($data['properties'] as &$prop) {
            if (($prop['id'] ?? '') === $id) {
                $prop['status'] = $status;
                if ($status === 'SOLD') {
                    $prop['highlight_badge'] = 'TERJUAL';
                } elseif ($status === 'RENTED') {
                    $prop['highlight_badge'] = 'TERSEWA';
                }
                self::appendAuditLog($data, "Perubahan status properti {$id} ke {$status}", 'Admin Operator', 'Updated');
                self::saveData($data);
                return true;
            }
        }
        return false;
    }

    public static function deleteProperty(string $id): bool
    {
        $data = self::loadData();
        $initialCount = count($data['properties']);
        $data['properties'] = array_values(array_filter($data['properties'], function ($p) use ($id) {
            return ($p['id'] ?? '') !== $id;
        }));

        if (count($data['properties']) < $initialCount) {
            self::appendAuditLog($data, "Penghapusan listing {$id}", 'Admin Operator', 'Deleted');
            self::saveData($data);
            return true;
        }
        return false;
    }

    // =========================================================================
    // 2. CONSIGNMENT SUBMISSIONS (TITIP PROPERTI)
    // =========================================================================

    public static function getSubmissions(?string $status = null): array
    {
        $data = self::loadData();
        $submissions = $data['submissions'] ?? [];

        if ($status && $status !== 'all') {
            return array_values(array_filter($submissions, function ($s) use ($status) {
                return ($s['status'] ?? '') === $status;
            }));
        }

        return $submissions;
    }

    public static function findSubmissionById(string $id): ?array
    {
        $submissions = self::getSubmissions();
        foreach ($submissions as $sub) {
            if (($sub['id'] ?? '') === $id) {
                return $sub;
            }
        }
        return null;
    }

    public static function createSubmission(array $input): array
    {
        $data = self::loadData();
        $id = '#TP-' . (count($data['submissions']) + 205);

        // 1. Parse Price
        $rawPrice = $input['asking_price'] ?? ($input['price'] ?? '0');
        $priceNum = (float)str_replace(['Rp', '.', ' ', ','], '', (string)$rawPrice);
        if ($priceNum <= 0) $priceNum = 3500000000;

        // 2. Parse Phone
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

        // 3. Parse Category & Trx
        $category = $input['property_category'] ?? ($input['category'] ?? 'Rumah Mewah');
        $trxType = $input['transaction_type'] ?? ($input['trx_type'] ?? 'Jual');
        if ($trxType === 'Keduanya') {
            $trxType = 'Jual';
        }

        // 4. Parse Locations
        $cityRegion = $input['city_region'] ?? 'Jakarta Selatan';
        $specificArea = $input['specific_area'] ?? 'Kawasan Prime';
        $locationStr = $cityRegion . ' - ' . $specificArea;
        $address = $input['full_address'] ?? ($input['address'] ?? ($input['location_address'] ?? 'Alamat lengkap terverifikasi oleh Kurator Kumala.'));

        // 5. Parse Specs
        $landSize = (int)($input['land_size'] ?? 250);
        $buildingSize = (int)($input['building_size'] ?? 200);
        $bedStr = (string)($input['bedrooms'] ?? '3 KT');
        $bedInt = (int)filter_var($bedStr, FILTER_SANITIZE_NUMBER_INT) ?: 3;
        $bathStr = (string)($input['bathrooms'] ?? '3 KM');
        $bathInt = (int)filter_var($bathStr, FILTER_SANITIZE_NUMBER_INT) ?: 3;

        // 6. Parse Facilities & Legal
        $facilities = (array)($input['facilities'] ?? ['pool', 'furnished', 'carport', 'shm', 'pbg']);
        $legal = in_array('shm', $facilities) ? 'SHM (Hak Milik)' : ($input['legal_status'] ?? 'SHM (Hak Milik)');
        if (stripos($category, 'Apartemen') !== false || stripos($category, 'Penthouse') !== false) {
            $legal = 'Strata Title';
        }

        // 7. Parse Photos
        $defaultPhotos = [
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=800&q=80',
        ];
        if (stripos($category, 'Apartemen') !== false || stripos($category, 'Penthouse') !== false) {
            $defaultPhotos = [
                'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
            ];
        }
        $photos = !empty($input['photos']) && is_array($input['photos']) ? array_values($input['photos']) : $defaultPhotos;

        // 8. Description & Title
        $title = !empty($input['property_title']) ? trim($input['property_title']) : (!empty($input['title']) ? trim($input['title']) : "{$category} Eksklusif di {$specificArea}");
        $description = !empty($input['description']) ? trim($input['description']) : "Properti konsinyasi resmi dititipkan oleh pemilik langsung melalui portal Kumala Property. Berkas legalitas terkurasi dan siap transaksi.";

        // 9. Generate Specs Tags for Public Card
        $specsTags = [
            "{$bedInt} Kamar Tidur",
            "LT {$landSize}m²",
            "LB {$buildingSize}m²"
        ];
        if (in_array('pool', $facilities)) {
            $specsTags[2] = 'Private Pool';
        } elseif (in_array('ocean_view', $facilities)) {
            $specsTags[2] = 'Full Ocean View';
        }

        // 10. Automatically Create & Publish Property to Public Catalog (Userpage)
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
            'status' => 'ACTIVE',
        ]);

        // 11. Create Submission Record for Admin Gatekeeper
        $submission = [
            'id' => $id,
            'title' => $title,
            'owner' => $input['owner_name'] ?? 'Bpk/Ibu Pemilik',
            'phone' => $formattedPhone,
            'email' => $input['owner_email'] ?? 'pemilik@kumalaproperty.id',
            'price' => 'Rp ' . number_format($priceNum, 0, ',', '.'),
            'asking_val' => $priceNum,
            'valuation_range' => self::formatValuationRange($priceNum),
            'trx_type' => $trxType,
            'category' => $category,
            'city_region' => $cityRegion,
            'specific_area' => $specificArea,
            'location' => $locationStr,
            'address' => $address,
            'land_size' => $landSize,
            'building_size' => $buildingSize,
            'bedrooms' => "{$bedInt} KT",
            'bathrooms' => "{$bathInt} KM",
            'electricity' => ($input['electricity'] ?? '4400') . ' VA',
            'legal' => $legal,
            'ownership' => ($input['ownership_status'] ?? '') === 'agent_official' ? 'Kuasa Jual Resmi' : 'Pemilik Langsung',
            'facilities' => $facilities,
            'time_ago' => 'Baru saja',
            'status' => 'pending',
            'priority' => 'P1 - Hot Asset',
            'photos_count' => count($photos),
            'photos' => $photos,
            'checklist' => [
                'identity' => true,
                'maps' => true,
                'shm_match' => true,
                'photos_hd' => true,
            ],
            'notes' => $description,
            'property_id' => $newProp['id'],
            'property_slug' => $newProp['slug'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Reload data to ensure newly added property from addProperty() is preserved
        $data = self::loadData();

        // Prepend to submissions list
        array_unshift($data['submissions'], $submission);

        // Add priority action in dashboard
        self::appendPriorityAction($data, [
            'id' => $id,
            'type' => 'listing',
            'title' => 'Pengajuan Titip Baru: ' . $submission['title'],
            'owner' => $submission['owner'],
            'phone' => $submission['phone'],
            'time_ago' => 'Baru saja',
            'sla_status' => 'warning',
            'sla_badge' => 'SLA Respon < 4 Jam',
            'detail' => 'Harga pengajuan ' . $submission['price'] . '. Unit telah tayang perdana di katalog publik (' . $newProp['id'] . ').',
            'btn_action_1' => 'Verifikasi SHM BPN',
            'btn_action_2' => 'Tinjau & Kurasi Unit',
        ]);

        self::appendAuditLog($data, "Pengajuan Titip Properti Baru {$id} ({$newProp['id']}) dari {$submission['owner']}", 'Portal Publik (User)', 'Published');

        self::saveData($data);
        return $submission;
    }

    public static function approveSubmission(string $id, string $curatorNotes = ''): ?array
    {
        $data = self::loadData();
        $targetSub = null;

        foreach ($data['submissions'] as &$sub) {
            if (($sub['id'] ?? '') === $id) {
                $sub['status'] = 'approved';
                if ($curatorNotes) {
                    $sub['notes'] = $curatorNotes;
                }
                $targetSub = $sub;
                break;
            }
        }

        if ($targetSub) {
            self::saveData($data);

            // If property is already linked, return it; otherwise create it
            if (!empty($targetSub['property_id'])) {
                $existingProp = self::findPropertyById($targetSub['property_id']);
                if ($existingProp) {
                    return $existingProp;
                }
            }

            // Fallback create if not exists
            $newProp = self::addProperty([
                'title' => $targetSub['title'],
                'category' => $targetSub['category'],
                'transaction_type' => $targetSub['trx_type'],
                'price' => (float)($targetSub['asking_val'] ?? 3000000000),
                'location_city' => $targetSub['city_region'] ?? (explode(' - ', $targetSub['location'])[0] ?? 'Jakarta Selatan'),
                'location_area' => $targetSub['specific_area'] ?? (explode(' - ', $targetSub['location'])[1] ?? 'Kawasan Prime'),
                'location_address' => $targetSub['address'],
                'legal_status' => $targetSub['legal'],
                'land_size' => $targetSub['land_size'],
                'building_size' => $targetSub['building_size'],
                'bedrooms' => (int)filter_var($targetSub['bedrooms'], FILTER_SANITIZE_NUMBER_INT) ?: 3,
                'bathrooms' => (int)filter_var($targetSub['bathrooms'], FILTER_SANITIZE_NUMBER_INT) ?: 3,
                'image_main' => $targetSub['photos'][0] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'images' => $targetSub['photos'] ?? [],
                'description' => $targetSub['notes'],
            ]);

            return $newProp;
        }

        return null;
    }

    public static function updateSubmissionStatus(string $id, string $status, string $notes = ''): bool
    {
        $data = self::loadData();
        foreach ($data['submissions'] as &$sub) {
            if (($sub['id'] ?? '') === $id) {
                $sub['status'] = $status;
                if ($notes) {
                    $sub['notes'] = $notes;
                }
                self::appendAuditLog($data, "Status pengajuan {$id} diperbarui menjadi {$status}", 'Admin Curator', 'Status Changed');
                self::saveData($data);
                return true;
            }
        }
        return false;
    }

    // =========================================================================
    // 3. INQUIRIES & LEAD PIPELINE CRM
    // =========================================================================

    public static function getInquiries(?string $stage = null): array
    {
        $data = self::loadData();
        $inquiries = $data['inquiries'] ?? [];

        if ($stage && $stage !== 'all') {
            return array_values(array_filter($inquiries, function ($inq) use ($stage) {
                return strtolower($inq['stage'] ?? '') === strtolower($stage);
            }));
        }

        return $inquiries;
    }

    public static function createInquiry(array $input): array
    {
        $data = self::loadData();
        $id = '#IQ-' . (count($data['inquiries']) + 2210);

        $propertyId = $input['property_id'] ?? '#KP-8821';
        $propertyTitle = $input['property_title'] ?? 'Properti Terpilih Kumala';
        $propertyPrice = $input['property_price'] ?? 'Rp 15 Miliar';

        // Match property title if slug or ID provided
        if (!empty($input['property_slug'])) {
            $prop = self::findPropertyBySlug($input['property_slug']);
            if ($prop) {
                $propertyId = $prop['id'];
                $propertyTitle = $prop['title'];
                $propertyPrice = $prop['price_short'];
            }
        } elseif (!empty($input['property_id'])) {
            $prop = self::findPropertyById($input['property_id']);
            if ($prop) {
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
        $finalQuote = !empty($quoteParts) ? implode(' | ', $quoteParts) : 'Menanyakan ketersediaan jadwal survei privat dan legalitas lengkap unit.';

        $inquiry = [
            'id' => $id,
            'client_name' => $input['name'] ?? ($input['client_name'] ?? 'Calon Pembeli VIP'),
            'phone' => $input['phone'] ?? '+62 812-3456-7890',
            'email' => $input['email'] ?? 'klien@investor.id',
            'buyer_type' => $input['buyer_type'] ?? 'Calon Pembeli Eksklusif (HNWIs)',
            'time_ago' => 'Baru saja',
            'sla_badge' => 'SLA Hijau (< 15 mnt)',
            'sla_status' => 'urgent',
            'stage' => 'Baru',
            'property_id' => $propertyId,
            'property_title' => $propertyTitle,
            'property_price' => $propertyPrice,
            'quote' => $finalQuote,
            'assigned_to' => 'Private Client Concierge',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        array_unshift($data['inquiries'], $inquiry);

        // Add priority action in dashboard
        self::appendPriorityAction($data, [
            'id' => $id,
            'type' => 'inquiry',
            'title' => 'Inquiry Baru: ' . $inquiry['property_title'],
            'owner' => $inquiry['client_name'],
            'phone' => $inquiry['phone'],
            'time_ago' => 'Baru saja',
            'sla_status' => 'urgent',
            'sla_badge' => 'SLA Respon < 15 mnt',
            'detail' => $inquiry['quote'],
            'btn_action_1' => 'Hubungi via WhatsApp',
            'btn_action_2' => 'Jadwalkan Survei',
        ]);

        self::appendAuditLog($data, "Inquiry Baru {$id} untuk properti {$propertyId} dari {$inquiry['client_name']}", 'Portal Publik (User)', 'Lead Created');

        self::saveData($data);
        return $inquiry;
    }

    public static function updateInquiryStage(string $id, string $newStage): bool
    {
        $data = self::loadData();
        foreach ($data['inquiries'] as &$inq) {
            if (($inq['id'] ?? '') === $id) {
                $inq['stage'] = $newStage;
                self::appendAuditLog($data, "Tahapan Lead {$id} diubah ke {$newStage}", 'Admin Concierge', 'Stage Advanced');
                self::saveData($data);
                return true;
            }
        }
        return false;
    }

    public static function getSurveysToday(): array
    {
        $data = self::loadData();
        return $data['surveys_today'] ?? [];
    }

    public static function addSurvey(array $survey): array
    {
        $data = self::loadData();
        $item = [
            'time' => $survey['time'] ?? '14:00 WIB',
            'property' => $survey['property'] ?? 'Unit Terpilih',
            'client' => $survey['client'] ?? 'Klien VIP',
            'agent' => $survey['agent'] ?? 'Tim Concierge Kumala',
            'status' => 'Terkonfirmasi',
        ];
        $data['surveys_today'][] = $item;
        self::saveData($data);
        return $item;
    }

    // =========================================================================
    // 4. TRANSACTIONS & CLOSINGS
    // =========================================================================

    public static function getTransactions(): array
    {
        $data = self::loadData();
        return $data['transactions'] ?? [];
    }

    public static function createTransaction(array $input): array
    {
        $data = self::loadData();
        $id = '#CLS-2025-0' . (count($data['transactions']) + 32);

        $transaction = [
            'id' => $id,
            'date' => date('d M Y'),
            'property_id' => $input['property_id'] ?? '#KP-8821',
            'property_title' => $input['property_title'] ?? 'Properti Closing',
            'property_image' => $input['property_image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
            'location' => $input['location'] ?? 'Jakarta Selatan',
            'trx_type' => $input['trx_type'] ?? 'TERJUAL - JUAL BELI',
            'asking_price' => $input['asking_price'] ?? 'Rp 10.000.000.000',
            'deal_price' => $input['deal_price'] ?? 'Rp 9.500.000.000',
            'diff_pct' => $input['diff_pct'] ?? '-5.0%',
            'parties' => $input['parties'] ?? 'Penjual & Pembeli Terverifikasi PPAT',
            'commission_val' => $input['commission_val'] ?? 'Rp 237.500.000 (2.5%)',
            'notary' => $input['notary'] ?? 'Notaris Rekanan Kumala PPAT',
            'status_doc' => 'AJB Selesai',
            'days_on_market' => ($input['days_on_market'] ?? 30) . ' Hari',
        ];

        array_unshift($data['transactions'], $transaction);

        // Mark property as SOLD/RENTED in local data
        if (!empty($input['property_id'])) {
            $isSewa = stripos($transaction['trx_type'], 'SEWA') !== false;
            $newStatus = $isSewa ? 'RENTED' : 'SOLD';
            foreach ($data['properties'] as &$prop) {
                if (($prop['id'] ?? '') === $input['property_id']) {
                    $prop['status'] = $newStatus;
                    $prop['highlight_badge'] = $isSewa ? 'TERSEWA' : 'TERJUAL';
                    break;
                }
            }
        }

        self::appendAuditLog($data, "Transaksi Closing {$id} Dicatatkan untuk unit {$transaction['property_id']}", 'Admin Closing Dept', 'Deal Closed');

        self::saveData($data);
        return $transaction;
    }

    // =========================================================================
    // 5. SYSTEM SETTINGS & MASTER CONFIG
    // =========================================================================

    public static function getSettings(): array
    {
        $data = self::loadData();
        return $data['settings'] ?? [
            'hotline_wa' => '+62 812-8899-2345',
            'hotline_wa_numeric' => '6281288992345',
            'email_official' => 'concierge@kumalaproperty.id',
            'email_business' => 'inquiries@kumalaproperty.id',
            'address_hq' => 'Jl. Kemang Raya No. 42, Mampang Prapatan, Jakarta Selatan 12730',
            'address_bali' => 'Jl. Pantai Suluban No. 8, Pecatu, Uluwatu, Badung, Bali 80361',
            'hours_weekday' => '08.30 - 18.00 WIB',
            'hours_saturday' => '09.00 - 15.00 WIB',
            'hours_sunday' => 'Dengan Janji Temu',
            'auto_watermark' => true,
            'whatsapp_notify' => true,
            'email_notify' => true,
        ];
    }

    public static function updateSettings(array $newSettings): array
    {
        $data = self::loadData();
        $data['settings'] = array_merge($data['settings'] ?? [], $newSettings);
        
        // Sanitize numeric WA if provided
        if (!empty($newSettings['hotline_wa'])) {
            $data['settings']['hotline_wa_numeric'] = preg_replace('/[^0-9]/', '', $newSettings['hotline_wa']);
        }

        self::appendAuditLog($data, 'Pembaruan Konfigurasi Sistem & Kontak Resmi', 'Super Admin', 'Config Saved');
        self::saveData($data);
        return $data['settings'];
    }

    public static function getRegions(): array
    {
        $data = self::loadData();
        return $data['regions'] ?? [];
    }

    public static function getCategories(): array
    {
        $data = self::loadData();
        return $data['categories'] ?? [];
    }

    // =========================================================================
    // 6. DASHBOARD METRICS & AUDIT LOGS
    // =========================================================================

    public static function getDashboardMetrics(): array
    {
        $data = self::loadData();
        $properties = $data['properties'] ?? [];
        $submissions = $data['submissions'] ?? [];
        $inquiries = $data['inquiries'] ?? [];
        $transactions = $data['transactions'] ?? [];

        $activeProps = array_filter($properties, fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE');
        $jualCount = count(array_filter($activeProps, fn($p) => strtolower($p['transaction_type'] ?? '') === 'jual'));
        $sewaCount = count(array_filter($activeProps, fn($p) => strtolower($p['transaction_type'] ?? '') === 'sewa'));

        $pendingSubs = count(array_filter($submissions, fn($s) => ($s['status'] ?? 'pending') === 'pending'));
        $newInqs = count(array_filter($inquiries, fn($i) => strtolower($i['stage'] ?? 'baru') === 'baru'));

        return [
            'active_units' => count($activeProps),
            'active_jual' => $jualCount,
            'active_sewa' => $sewaCount,
            'active_growth' => '+' . min(count($activeProps), 8) . ' unit aktif',
            'pending_review' => $pendingSubs,
            'new_inquiries' => $newInqs,
            'avg_sla_minutes' => 18,
            'closing_month_units' => count($transactions),
            'closing_month_val' => 'Rp 18.4 Miliar',
            'closing_target_pct' => 85,
        ];
    }

    public static function getPriorityActions(): array
    {
        $data = self::loadData();
        $actions = [];

        // 1. Pending Submissions (Titip Properti baru dari user)
        $submissions = $data['submissions'] ?? [];
        foreach ($submissions as $sub) {
            if (($sub['status'] ?? 'pending') === 'pending') {
                $actions[] = [
                    'id' => $sub['id'],
                    'category' => 'listing',
                    'type' => 'listing',
                    'title' => $sub['title'],
                    'owner' => 'Pemilik: ' . ($sub['owner'] ?? 'Klien Titip'),
                    'phone' => $sub['phone'] ?? '',
                    'time_ago' => $sub['time_ago'] ?? 'Baru saja',
                    'sla_status' => 'warning',
                    'sla_badge' => 'LISTING TITIP BARU',
                    'tag_badge' => 'LISTING TITIP BARU',
                    'tag_class' => 'badge-cyan',
                    'detail' => 'Harga: ' . ($sub['price'] ?? 'Rp -') . ' | Lokasi: ' . ($sub['location'] ?? ''),
                    'image' => $sub['photos'][0] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80',
                    'btn_action_1' => 'Verifikasi SHM',
                    'btn_url_1' => route('admin.submissions'),
                    'btn_action_2' => 'Tinjau & Kurasi',
                    'btn_url_2' => route('admin.submissions'),
                ];
            }
        }

        // 2. New Inquiries (Lead baru masuk dari web detail)
        $inquiries = $data['inquiries'] ?? [];
        foreach ($inquiries as $inq) {
            if (strtolower($inq['stage'] ?? 'baru') === 'baru') {
                $cleanPhone = preg_replace('/[^0-9]/', '', $inq['phone'] ?? '');
                if (substr($cleanPhone, 0, 1) === '0') $cleanPhone = '62' . substr($cleanPhone, 1);
                $actions[] = [
                    'id' => $inq['id'],
                    'category' => 'inquiry',
                    'type' => 'inquiry',
                    'title' => 'Inquiry: ' . ($inq['property_title'] ?? 'Listing Minat'),
                    'owner' => 'Klien: ' . ($inq['client_name'] ?? 'Klien VIP'),
                    'phone' => $inq['phone'] ?? '',
                    'time_ago' => $inq['time_ago'] ?? 'Baru saja',
                    'sla_status' => 'urgent',
                    'sla_badge' => 'INQUIRY BELI SEGERA',
                    'tag_badge' => 'INQUIRY BELI SEGERA',
                    'tag_class' => 'badge-peach',
                    'detail' => ($inq['quote'] ?? 'Permintaan info unit & jadwal survei.'),
                    'image' => 'https://images.unsplash.com/photo-1541971875076-8f970d573be6?auto=format&fit=crop&w=300&q=80',
                    'btn_action_1' => 'Buka Leads',
                    'btn_url_1' => route('admin.inquiries'),
                    'btn_action_2' => 'WhatsApp Klien',
                    'btn_url_2' => 'https://wa.me/' . $cleanPhone,
                ];
            }
        }

        // 3. Transactions & Seed Priority Actions
        $storedActions = $data['priority_actions'] ?? [];
        foreach ($storedActions as $sa) {
            $alreadyExists = false;
            foreach ($actions as $act) {
                if (($act['id'] ?? '') === ($sa['id'] ?? '')) {
                    $alreadyExists = true;
                    break;
                }
            }
            if (!$alreadyExists) {
                $sa['category'] = $sa['type'] ?? 'listing';
                $sa['tag_badge'] = $sa['sla_badge'] ?? 'PRIORITAS';
                $sa['tag_class'] = ($sa['category'] === 'inquiry') ? 'badge-peach' : (($sa['category'] === 'closing') ? 'badge-teal' : 'badge-cyan');
                $sa['image'] = $sa['image'] ?? 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=300&q=80';
                $actions[] = $sa;
            }
        }

        return $actions;
    }

    public static function getAuditLogs(): array
    {
        $data = self::loadData();
        return array_slice($data['audit_logs'] ?? [], 0, 10);
    }

    // =========================================================================
    // INTERNAL HELPERS
    // =========================================================================

    protected static function appendAuditLog(array &$data, string $action, string $user, string $status): void
    {
        if (!isset($data['audit_logs'])) {
            $data['audit_logs'] = [];
        }
        array_unshift($data['audit_logs'], [
            'timestamp' => date('H:i') . ' WIB',
            'action' => $action,
            'user' => $user,
            'status' => $status,
        ]);
    }

    protected static function appendPriorityAction(array &$data, array $action): void
    {
        if (!isset($data['priority_actions'])) {
            $data['priority_actions'] = [];
        }
        array_unshift($data['priority_actions'], $action);
    }

    protected static function formatShortPrice(float $p): string
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

    protected static function formatValuationRange(float $p): string
    {
        $low = $p * 0.95;
        $high = $p * 1.08;
        return self::formatShortPrice($low) . ' - ' . self::formatShortPrice($high);
    }

    /**
     * Default seed dataset matching PRD and UI mockups
     */
    protected static function getDefaultSeedData(): array
    {
        return [
            'properties' => PropertyDataService::getAll(),
            'submissions' => [
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
                    'photos' => [
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                        'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                    ],
                    'checklist' => [
                        'identity' => true,
                        'maps' => true,
                        'shm_match' => true,
                        'photos_hd' => false,
                    ],
                    'notes' => 'Aset lokasi sangat prime dekat Echo Beach. Foto interior dapur perlu peningkatan pencahayaan standar Kumala sebelum diterbitkan.',
                ],
                [
                    'id' => '#TP-205',
                    'title' => 'Colonial Executive Residence Kemang',
                    'owner' => 'Ibu Farida Siregar',
                    'phone' => '+62 811-3344-5566',
                    'email' => 'farida.s@siregarholding.com',
                    'price' => 'Rp 38.000.000.000',
                    'asking_val' => 38000000000,
                    'valuation_range' => 'Rp 36 M - 39 Miliar',
                    'trx_type' => 'Jual',
                    'category' => 'Rumah Mewah',
                    'location' => 'DKI Jakarta - Kemang, Jaksel',
                    'address' => 'Jl. Kemang Timur Raya No. 14, Bangka, Jakarta Selatan',
                    'land_size' => 750,
                    'building_size' => 550,
                    'bedrooms' => '5 KT',
                    'bathrooms' => '5 KM',
                    'electricity' => '13200 VA',
                    'legal' => 'SHM Clear',
                    'ownership' => 'Pemilik Langsung',
                    'time_ago' => '2 jam lalu',
                    'status' => 'pending',
                    'priority' => 'P2 - High Priority',
                    'photos_count' => 12,
                    'photos' => [
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                    ],
                    'checklist' => [
                        'identity' => true,
                        'maps' => true,
                        'shm_match' => true,
                        'photos_hd' => true,
                    ],
                    'notes' => 'Legalitas sertifikat SHM sudah diverifikasi notaris rekanan. Menunggu persetujuan final Super Admin.',
                ],
                [
                    'id' => '#TP-206',
                    'title' => 'Beachside Cliff Pavillion Uluwatu',
                    'owner' => 'Bpk. Kenneth Lee (Kuasa Jual)',
                    'phone' => '+62 817-9000-1122',
                    'email' => 'k.lee@pacificwealth.sg',
                    'price' => 'Rp 650.000.000 / Thn',
                    'asking_val' => 650000000,
                    'valuation_range' => 'Rp 600 Jt - 700 Jt / Thn',
                    'trx_type' => 'Sewa',
                    'category' => 'Villa Mewah',
                    'location' => 'Bali - Uluwatu, Badung',
                    'address' => 'Jl. Pantai Suluban No. 12, Pecatu, Uluwatu',
                    'land_size' => 600,
                    'building_size' => 450,
                    'bedrooms' => '4 KT',
                    'bathrooms' => '4 KM',
                    'electricity' => '7700 VA',
                    'legal' => 'HGB Murni',
                    'ownership' => 'Kuasa Jual Resmi',
                    'time_ago' => '3 jam lalu',
                    'status' => 'pending',
                    'priority' => 'P2 - High Priority',
                    'photos_count' => 8,
                    'photos' => [
                        'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=400&q=80',
                    ],
                    'checklist' => [
                        'identity' => true,
                        'maps' => true,
                        'shm_match' => false,
                        'photos_hd' => true,
                    ],
                    'notes' => 'Surat kuasa notarial dari pemilik Singapura sedang diproses legalisasi Kemenkumham.',
                ],
            ],
            'inquiries' => [
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
                    'quote' => 'Halo tim Kumala, kami berencana survei privat Sabtu pagi ini. Mohon info apakah tersedia helipad transfer atau penjemputan bandara Ngurah Rai?',
                    'assigned_to' => 'Gusti Ayu (Concierge Bali)',
                ],
                [
                    'id' => '#IQ-2208',
                    'client_name' => 'Ibu Stephanie Halim',
                    'phone' => '+62 812-7788-9900',
                    'email' => 'stephanie.h@halimholdings.id',
                    'buyer_type' => 'Pembeli Rumah Tinggal',
                    'time_ago' => '45 menit lalu',
                    'sla_badge' => 'SLA Aman (45 mnt)',
                    'sla_status' => 'normal',
                    'stage' => 'Follow-Up',
                    'property_id' => '#KP-8822',
                    'property_title' => 'Menteng Heritage Colonial Mansion',
                    'property_price' => 'Rp 85 Miliar',
                    'quote' => 'Apakah berkas sertifikat SHM sudah diaudit BPN tanpa riwayat sengketa waris? Kami ingin menjadwalkan pertemuan di kantor Menteng.',
                    'assigned_to' => 'Farhan Kamil (Curator Jaksel)',
                ],
                [
                    'id' => '#IQ-2207',
                    'client_name' => 'Mr. Thomas Vance',
                    'phone' => '+62 819-2233-4455',
                    'email' => 'tvance@singaporeventures.sg',
                    'buyer_type' => 'Sewa Korporat Ekspatriat',
                    'time_ago' => '2 jam lalu',
                    'sla_badge' => 'Survei Terjadwal',
                    'sla_status' => 'normal',
                    'stage' => 'Survei',
                    'property_id' => '#KP-8824',
                    'property_title' => 'Lavish Executive Penthouse SCBD',
                    'property_price' => 'Rp 850 Jt / Thn',
                    'quote' => 'Survei dikonfirmasi untuk besok pukul 15.00 WIB bersama tim legal perusahaan kami.',
                    'assigned_to' => 'Aditya Pratama (Super Admin)',
                ],
            ],
            'surveys_today' => [
                [
                    'time' => '10:30 WIB',
                    'property' => 'Villa Cliffside Horizon Uluwatu',
                    'client' => 'Bpk. Richard Tan',
                    'agent' => 'Gusti Ayu',
                    'status' => 'Terkonfirmasi',
                ],
                [
                    'time' => '14:00 WIB',
                    'property' => 'Menteng Heritage Mansion',
                    'client' => 'Ibu Stephanie Halim',
                    'agent' => 'Farhan Kamil',
                    'status' => 'Menunggu Klien',
                ],
                [
                    'time' => '16:30 WIB',
                    'property' => 'Lavish Penthouse SCBD',
                    'client' => 'Mr. Thomas Vance',
                    'agent' => 'Aditya Pratama',
                    'status' => 'Terkonfirmasi',
                ],
            ],
            'transactions' => [
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
                [
                    'id' => '#CLS-2025-030',
                    'date' => '02 Mar 2025',
                    'property_id' => '#KP-8821',
                    'property_title' => 'Villa Cliffside Horizon Uluwatu',
                    'property_image' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=400&q=80',
                    'location' => 'Uluwatu, Badung, Bali',
                    'trx_type' => 'TERJUAL - JUAL BELI',
                    'asking_price' => 'Rp 45.000.000.000',
                    'deal_price' => 'Rp 42.000.000.000',
                    'diff_pct' => '-6.6%',
                    'parties' => 'Penjual: PT Bali Vista / Pembeli: Keluarga Gunawan',
                    'commission_val' => 'Rp 1.050.000.000 (2.5%)',
                    'notary' => 'I Made Suardana, S.H. (Denpasar)',
                    'status_doc' => 'AJB Selesai',
                    'days_on_market' => '45 Hari',
                ],
            ],
            'settings' => [
                'hotline_wa' => '+62 812-8899-2345',
                'hotline_wa_numeric' => '6281288992345',
                'email_official' => 'concierge@kumalaproperty.id',
                'email_business' => 'inquiries@kumalaproperty.id',
                'address_hq' => 'Jl. Kemang Raya No. 42, Mampang Prapatan, Jakarta Selatan 12730',
                'address_bali' => 'Jl. Pantai Suluban No. 8, Pecatu, Uluwatu, Badung, Bali 80361',
                'hours_weekday' => '08.30 - 18.00 WIB',
                'hours_saturday' => '09.00 - 15.00 WIB',
                'hours_sunday' => 'Dengan Janji Temu',
                'auto_watermark' => true,
                'whatsapp_notify' => true,
                'email_notify' => true,
            ],
            'regions' => [
                ['name' => 'DKI Jakarta - Jakarta Selatan', 'active' => true, 'listings' => 48],
                ['name' => 'Bali - Badung (Canggu, Uluwatu, Seminyak)', 'active' => true, 'listings' => 52],
                ['name' => 'Banten - Tangerang Selatan (BSD City)', 'active' => true, 'listings' => 24],
                ['name' => 'DKI Jakarta - Jakarta Pusat (Menteng)', 'active' => true, 'listings' => 12],
                ['name' => 'Jawa Timur - Surabaya Barat', 'active' => true, 'listings' => 8],
                ['name' => 'Jawa Barat - Bandung Utara', 'active' => false, 'listings' => 4],
            ],
            'categories' => [
                ['name' => 'Rumah Tapak Mewah', 'subtypes' => 'Heritage, Colonial, Modern Minimalis', 'active' => true, 'count' => 64],
                ['name' => 'Villa Mewah & Tropis', 'subtypes' => 'Cliffside, Beachfront, Ricefield View', 'active' => true, 'count' => 52],
                ['name' => 'Ruko & Komersial', 'subtypes' => 'Shophouse, Butik, Kantor Representatif', 'active' => true, 'count' => 18],
                ['name' => 'Apartemen & Penthouse', 'subtypes' => 'Sky Villa, Penthouse SCBD, High-rise', 'active' => true, 'count' => 14],
            ],
            'priority_actions' => [
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
            ],
            'audit_logs' => [
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
            ]
        ];
    }
}
