<?php

namespace Database\Seeders;

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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KumalaMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Users (Personnel & Admins)
        $users = [
            [
                'name' => 'Super Admin Kumala',
                'email' => 'admin@kumalaproperty.co.id',
                'password' => Hash::make('PropertySecure2024!'),
                'role' => 'super_admin',
                'avatar' => 'SA',
            ],
            [
                'name' => 'Aditya Pratama',
                'email' => 'admin@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'super_admin',
                'avatar' => 'AP',
            ],
            [
                'name' => 'Admin Utama',
                'email' => 'admin@kumalaproperty.id',
                'password' => Hash::make('admin'),
                'role' => 'super_admin',
                'avatar' => 'AU',
            ],
            [
                'name' => 'Farhan Kamil',
                'email' => 'farhan@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'curator',
                'avatar' => 'FK',
            ],
            [
                'name' => 'Gusti Ayu',
                'email' => 'gusti@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'sales_advisor',
                'avatar' => 'GA',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // 2. Load JSON data file if available
        $jsonPath = storage_path('app/kumala_store.json');
        $data = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];

        // 3. Seed Categories
        $categories = $data['categories'] ?? [
            ['name' => 'Rumah Mewah', 'slug' => 'rumah-mewah', 'icon' => 'home', 'count' => 8, 'subtypes' => 'Modern Minimalist, American Classic, Tropis Kontemporer'],
            ['name' => 'Villa Tropis', 'slug' => 'villa-tropis', 'icon' => 'sun', 'count' => 4, 'subtypes' => 'Cliffside Sanctuary, Beachfront Oasis, Jungle Retreat'],
            ['name' => 'Penthouse & Apartemen', 'slug' => 'penthouse-apartemen', 'icon' => 'building', 'count' => 3, 'subtypes' => 'Sky Penthouse, Luxury High-Rise, Serviced Residence'],
            ['name' => 'Komersial & Ruko', 'slug' => 'komersial-ruko', 'icon' => 'briefcase', 'count' => 2, 'subtypes' => 'Boutique Office, Premium Shophouse, F&B Space'],
            ['name' => 'Lahan & Plot Investasi', 'slug' => 'lahan-plot-investasi', 'icon' => 'map', 'count' => 2, 'subtypes' => 'Komersial Prime, Plot Residensial, Resort Development'],
            ['name' => 'Semua Tipe Properti', 'slug' => 'semua', 'icon' => 'grid', 'count' => 19, 'subtypes' => 'Katalog Lengkap Portofolio Kumala'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug'] ?? Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'] ?? Str::slug($cat['name']),
                    'icon' => $cat['icon'] ?? 'home',
                    'count' => $cat['count'] ?? 0,
                    'subtypes' => $cat['subtypes'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        // 4. Seed Regions
        $regions = $data['regions'] ?? [
            ['name' => 'Jakarta Selatan', 'city' => 'Jakarta Selatan', 'area' => 'Senopati, Dharmawangsa, Menteng', 'slug' => 'jakarta-selatan', 'image' => 'https://images.unsplash.com/photo-1577717903315-1691ae25ab3f?auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Bali Prime', 'city' => 'Bali', 'area' => 'Uluwatu, Canggu, Seminyak', 'slug' => 'bali-prime', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Surabaya Barat', 'city' => 'Surabaya', 'area' => 'CitraLand, Graha Famili, Pakuwon', 'slug' => 'surabaya-barat', 'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80'],
            ['name' => 'BSD City & Serpong', 'city' => 'Tangerang', 'area' => 'Nava Park, The Zora, Greenwich', 'slug' => 'bsd-city-serpong', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Bandung Utara', 'city' => 'Bandung', 'area' => 'Dago Pakar, Setiabudi, Ciumbuleuit', 'slug' => 'bandung-utara', 'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Semua Wilayah', 'city' => 'Indonesia', 'area' => 'Portofolio Seluruh Wilayah', 'slug' => 'semua-wilayah', 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80'],
        ];

        foreach ($regions as $reg) {
            Region::updateOrCreate(
                ['name' => $reg['name']],
                [
                    'name' => $reg['name'],
                    'slug' => $reg['slug'] ?? Str::slug($reg['name']),
                    'city' => $reg['city'] ?? $reg['name'],
                    'area' => $reg['area'] ?? '',
                    'image' => $reg['image'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        // 5. Seed Properties
        $properties = $data['properties'] ?? [];
        foreach ($properties as $p) {
            $prop = Property::updateOrCreate(
                ['id' => $p['id']],
                [
                    'slug' => $p['slug'] ?? Str::slug($p['title']) . '-' . rand(100, 999),
                    'title' => $p['title'],
                    'category' => $p['category'] ?? 'Rumah Mewah',
                    'transaction_type' => $p['transaction_type'] ?? 'Jual',
                    'price' => (float)($p['price'] ?? 1000000000),
                    'price_formatted' => $p['price_formatted'] ?? ('Rp ' . number_format((float)($p['price'] ?? 1000000000), 0, ',', '.')),
                    'price_short' => $p['price_short'] ?? 'Rp 10 Miliar',
                    'price_label' => $p['price_label'] ?? 'Harga Penawaran Eksklusif',
                    'price_notes' => $p['price_notes'] ?? 'Legalitas Lengkap • Siap Transaksi',
                    'price_negotiable' => true,
                    'location_city' => $p['location_city'] ?? 'Jakarta Selatan',
                    'location_area' => $p['location_area'] ?? 'Kebayoran Baru',
                    'location_address' => $p['location_address'] ?? 'Jakarta Selatan, DKI Jakarta',
                    'legal_status' => $p['legal_status'] ?? 'SHM (Hak Milik)',
                    'verified' => (bool)($p['verified'] ?? true),
                    'featured' => (bool)($p['featured'] ?? false),
                    'highlight_badge' => $p['highlight_badge'] ?? null,
                    'land_size' => (int)($p['land_size'] ?? 200),
                    'building_size' => (int)($p['building_size'] ?? 180),
                    'floors' => (int)($p['floors'] ?? 2),
                    'bedrooms' => (int)($p['bedrooms'] ?? 3),
                    'bathrooms' => (int)($p['bathrooms'] ?? 3),
                    'orientation' => $p['orientation'] ?? 'Utara',
                    'electricity' => $p['electricity'] ?? '4400 VA',
                    'water_source' => $p['water_source'] ?? 'PDAM',
                    'garage' => $p['garage'] ?? '2 Mobil',
                    'year_built' => (string)($p['year_built'] ?? date('Y')),
                    'furnishing' => $p['furnishing'] ?? 'Semi Furnished',
                    'specs_tags' => $p['specs_tags'] ?? [],
                    'image_main' => $p['image_main'] ?? ($p['images'][0] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'),
                    'images' => $p['images'] ?? [],
                    'features' => $p['features'] ?? [],
                    'description' => $p['description'] ?? 'Hunian eksklusif terkurasi oleh Kumala Property.',
                    'distance_points' => $p['distance_points'] ?? [],
                    'status' => $p['status'] ?? 'ACTIVE',
                ]
            );

            // Populate child photos
            if (!empty($p['images']) && is_array($p['images'])) {
                PropertyPhoto::where('property_id', $prop->id)->delete();
                foreach ($p['images'] as $idx => $photoUrl) {
                    PropertyPhoto::create([
                        'property_id' => $prop->id,
                        'photo_url' => $photoUrl,
                        'caption' => $prop->title . ' - Foto ' . ($idx + 1),
                        'is_main' => ($idx === 0),
                        'sort_order' => $idx,
                    ]);
                }
            }

            // Populate child features
            if (!empty($p['features']) && is_array($p['features'])) {
                PropertyFeature::where('property_id', $prop->id)->delete();
                foreach ($p['features'] as $feat) {
                    PropertyFeature::create([
                        'property_id' => $prop->id,
                        'title' => $feat['title'] ?? 'Fasilitas Premium',
                        'desc' => $feat['desc'] ?? '',
                        'icon' => $feat['icon'] ?? 'shield',
                    ]);
                }
            }
        }

        // 6. Seed Submissions (Titip Properti)
        $submissions = $data['submissions'] ?? [];
        foreach ($submissions as $s) {
            Submission::updateOrCreate(
                ['id' => $s['id']],
                [
                    'property_id' => $s['property_id'] ?? null,
                    'property_slug' => $s['property_slug'] ?? null,
                    'title' => $s['title'] ?? 'Pengajuan Titip Properti',
                    'owner_name' => $s['owner'] ?? ($s['owner_name'] ?? 'Pemilik Properti'),
                    'owner_phone' => $s['phone'] ?? ($s['owner_phone'] ?? '+62 812-8899-2345'),
                    'owner_email' => $s['email'] ?? ($s['owner_email'] ?? 'pemilik@kumalaproperty.id'),
                    'owner_status' => $s['ownership'] ?? ($s['owner_status'] ?? 'Pemilik Langsung'),
                    'trx_type' => $s['trx_type'] ?? 'Jual',
                    'category' => $s['category'] ?? 'Rumah Mewah',
                    'city_region' => $s['city_region'] ?? 'Jakarta Selatan',
                    'specific_area' => $s['specific_area'] ?? 'Kawasan Prime',
                    'location' => $s['location'] ?? 'Jakarta Selatan',
                    'address' => $s['address'] ?? 'Alamat terverifikasi',
                    'asking_price' => $s['price'] ?? ($s['asking_price'] ?? 'Rp 5.000.000.000'),
                    'asking_val' => (int)($s['asking_val'] ?? 5000000000),
                    'valuation_range' => $s['valuation_range'] ?? 'Rp 4,8 M - Rp 5,3 M',
                    'land_size' => (int)($s['land_size'] ?? 200),
                    'building_size' => (int)($s['building_size'] ?? 180),
                    'bedrooms' => (string)($s['bedrooms'] ?? '3 KT'),
                    'bathrooms' => (string)($s['bathrooms'] ?? '3 KM'),
                    'electricity' => (string)($s['electricity'] ?? '4400 VA'),
                    'legal' => $s['legal'] ?? 'SHM (Hak Milik)',
                    'facilities' => $s['facilities'] ?? [],
                    'photos' => $s['photos'] ?? [],
                    'photos_count' => (int)($s['photos_count'] ?? count($s['photos'] ?? [])),
                    'priority' => $s['priority'] ?? 'Normal',
                    'status' => $s['status'] ?? 'pending',
                    'checklist' => $s['checklist'] ?? ['identity' => true, 'maps' => true, 'shm_match' => true, 'photos_hd' => true],
                    'notes' => $s['notes'] ?? '',
                    'time_ago' => $s['time_ago'] ?? 'Baru saja',
                    'submitted_at' => $s['created_at'] ?? now(),
                ]
            );
        }

        // 7. Seed Leads & Inquiries
        $inquiries = $data['inquiries'] ?? [];
        foreach ($inquiries as $inq) {
            Lead::updateOrCreate(
                ['id' => $inq['id']],
                [
                    'property_id' => $inq['property_id'] ?? null,
                    'property_title' => $inq['property_title'] ?? 'Properti Pilihan Kumala',
                    'property_price' => $inq['property_price'] ?? 'Rp 15 Miliar',
                    'client_name' => $inq['client_name'] ?? ($inq['name'] ?? 'Calon Pembeli VIP'),
                    'client_phone' => $inq['phone'] ?? ($inq['client_phone'] ?? '+62 812-3456-7890'),
                    'client_email' => $inq['email'] ?? ($inq['client_email'] ?? 'klien@kumalaproperty.id'),
                    'buyer_category' => $inq['buyer_type'] ?? ($inq['buyer_category'] ?? 'Calon Pembeli Eksklusif (HNWIs)'),
                    'buyer_type' => $inq['buyer_type'] ?? 'HNWIs',
                    'quote' => $inq['quote'] ?? 'Inquiry jadwal survei privat.',
                    'message' => $inq['quote'] ?? 'Inquiry jadwal survei privat.',
                    'stage' => $inq['stage'] ?? 'Baru',
                    'assigned_agent' => $inq['assigned_to'] ?? ($inq['assigned_agent'] ?? 'Private Client Concierge'),
                    'assigned_to' => $inq['assigned_to'] ?? ($inq['assigned_agent'] ?? 'Private Client Concierge'),
                    'sla_badge' => $inq['sla_badge'] ?? 'SLA Hijau (< 15 mnt)',
                    'sla_status' => $inq['sla_status'] ?? 'urgent',
                    'time_ago' => $inq['time_ago'] ?? 'Baru saja',
                ]
            );
        }

        // 8. Seed Surveys
        $surveys = $data['surveys_today'] ?? [
            ['time' => '10:30 WIB', 'property' => 'Penthouse Sky Residence Sudirman', 'client' => 'Bpk. Hendra Gunawan', 'agent' => 'Aditya Pratama', 'status' => 'Terkonfirmasi'],
            ['time' => '13:00 WIB', 'property' => 'Villa Cliffside Horizon Uluwatu', 'client' => 'Mr. Julian Vance', 'agent' => 'Gusti Ayu', 'status' => 'Terkonfirmasi'],
            ['time' => '15:30 WIB', 'property' => 'Modern Sanctuary Villa Canggu', 'client' => 'Ibu Maya Santoso', 'agent' => 'Farhan Kamil', 'status' => 'Menunggu Klien'],
            ['time' => '16:45 WIB', 'property' => 'The Grand Senopati Residence', 'client' => 'Dr. Bambang Sutrisno', 'agent' => 'Aditya Pratama', 'status' => 'Terkonfirmasi'],
        ];

        Survey::truncate();
        foreach ($surveys as $surv) {
            Survey::create([
                'property_title' => $surv['property'] ?? $surv['property_title'],
                'client_name' => $surv['client'] ?? $surv['client_name'],
                'agent_name' => $surv['agent'] ?? ($surv['agent_name'] ?? 'Tim Concierge Kumala'),
                'survey_time' => $surv['time'] ?? ($surv['survey_time'] ?? '14:00 WIB'),
                'survey_date' => now()->toDateString(),
                'status' => $surv['status'] ?? 'Terkonfirmasi',
            ]);
        }

        // 9. Seed Transactions
        $transactions = $data['transactions'] ?? [];
        foreach ($transactions as $t) {
            $dealVal = (int)preg_replace('/[^0-9]/', '', (string)($t['deal_price'] ?? '0')) ?: 9500000000;
            Transaction::updateOrCreate(
                ['id' => $t['id']],
                [
                    'date' => $t['date'] ?? date('d M Y'),
                    'property_id' => $t['property_id'] ?? '#KP-8821',
                    'property_title' => $t['property_title'] ?? 'Properti Closing',
                    'property_image' => $t['property_image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                    'location' => $t['location'] ?? 'Jakarta Selatan',
                    'trx_type' => $t['trx_type'] ?? 'TERJUAL - JUAL BELI',
                    'asking_price' => $t['asking_price'] ?? 'Rp 10.000.000.000',
                    'deal_price' => $t['deal_price'] ?? 'Rp 9.500.000.000',
                    'deal_val' => $dealVal,
                    'diff_pct' => $t['diff_pct'] ?? '-5.0%',
                    'parties' => $t['parties'] ?? 'Penjual & Pembeli Terverifikasi PPAT',
                    'commission_val' => $t['commission_val'] ?? 'Rp 237.500.000 (2.5%)',
                    'notary' => $t['notary'] ?? 'Notaris Rekanan Kumala PPAT',
                    'status_doc' => $t['status_doc'] ?? 'AJB Selesai',
                    'days_on_market' => $t['days_on_market'] ?? '30 Hari',
                ]
            );
        }

        // 10. Seed Settings
        $settings = $data['settings'] ?? [];
        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($val) ? json_encode($val) : (string)$val, 'group' => 'general']
            );
        }

        // 11. Seed Priority Actions
        $priorityActions = $data['priority_actions'] ?? [];
        PriorityAction::truncate();
        foreach ($priorityActions as $pa) {
            PriorityAction::create([
                'action_id' => $pa['id'] ?? ('#ACT-' . rand(100, 999)),
                'type' => $pa['type'] ?? 'listing',
                'title' => $pa['title'] ?? 'Tindakan Prioritas',
                'owner' => $pa['owner'] ?? null,
                'phone' => $pa['phone'] ?? null,
                'time_ago' => $pa['time_ago'] ?? 'Baru saja',
                'sla_status' => $pa['sla_status'] ?? 'normal',
                'sla_badge' => $pa['sla_badge'] ?? 'SLA 4 Jam',
                'detail' => $pa['detail'] ?? '',
                'btn_action_1' => $pa['btn_action_1'] ?? 'Tinjau Unit',
                'btn_action_2' => $pa['btn_action_2'] ?? 'Hubungi Klien',
                'is_resolved' => false,
            ]);
        }

        // 12. Seed Audit Logs
        $auditLogs = $data['audit_logs'] ?? [];
        AuditLog::truncate();
        foreach (array_slice($auditLogs, 0, 50) as $log) {
            AuditLog::create([
                'timestamp_display' => $log['time'] ?? ($log['timestamp_display'] ?? date('d M Y - H:i:s')),
                'action' => $log['action'] ?? 'Aktivitas Sistem',
                'user' => $log['user'] ?? 'System Daemon',
                'status' => $log['status'] ?? 'Success',
                'details' => $log['details'] ?? null,
            ]);
        }
    }
}
