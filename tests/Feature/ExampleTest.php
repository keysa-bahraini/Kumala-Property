<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\AuditLog;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Submission;
use App\Models\Survey;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    private function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@kumalaproperty.com'],
            [
                'name' => 'Aditya Pratama',
                'password' => bcrypt('@adminkumala'),
                'role' => 'super_admin',
                'avatar' => 'AP',
            ]
        );
    }

    private function getCuratorUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'curator@kumalaproperty.com'],
            [
                'name' => 'Budi Kurator',
                'password' => bcrypt('@adminkumala'),
                'role' => 'curator',
                'avatar' => 'BK',
            ]
        );
    }

    /**
     * Test Page 1: Landing Page (Beranda)
     */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KUMALA PROPERTY');
        $response->assertSee('Temukan Hunian Eksklusif');
    }

    /**
     * Test Page 2: Explore Properti
     */
    public function test_explore_page_returns_successful_response(): void
    {
        $response = $this->get('/explore');
        $response->assertStatus(200);
        $response->assertSee('Eksplorasi Properti');
        $response->assertSee('Menampilkan');
    }

    /**
     * Test Page 3: Detail Properti
     */
    public function test_detail_page_returns_successful_response(): void
    {
        $response = $this->get('/properti/villa-cliffside-horizon-uluwatu');
        $response->assertStatus(200);
        $response->assertSee('Villa Cliffside Horizon Uluwatu');
        $response->assertSee('Tertarik dengan Properti Ini?');
    }

    /**
     * Test Page 4: Titip Properti
     */
    public function test_titip_properti_page_returns_successful_response(): void
    {
        $response = $this->get('/titip-properti');
        $response->assertStatus(200);
        $response->assertSee('Titipkan Properti Anda');
        $response->assertSee('Data Pemilik Properti');
    }

    /**
     * Test Page 5: Properti Favorit Saya (Telah Dihapus Sesuai Keputusan Mentor)
     */
    public function test_favorites_page_is_not_found(): void
    {
        $response = $this->get('/favorit');
        $response->assertStatus(404);
    }

    /**
     * Test Page 6: Tentang Kami & Kontak
     */
    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(200);
        $response->assertSee('Kurasi Presisi untuk Koleksi Aset Bernilai Abadi');
        $response->assertSee('Tiga Pilar Layanan Kumala');
    }

    /**
     * Test Page 7: Login Admin
     */
    public function test_admin_login_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Portal Internal');
        $response->assertSee('admin@kumalaproperty.com');
    }

    /**
     * Test Admin Login Authentication & Throttling
     */
    public function test_admin_authentication_success_and_logout(): void
    {
        $admin = $this->getAdminUser();

        // Failed login
        $failedResponse = $this->post('/admin/login', [
            'email' => 'admin@kumalaproperty.com',
            'password' => 'wrongpassword',
        ]);
        $failedResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        // Successful login
        $successResponse = $this->post('/admin/login', [
            'email' => 'admin@kumalaproperty.com',
            'password' => '@adminkumala',
        ]);
        $successResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        // POST Logout
        $logoutResponse = $this->post('/admin/logout');
        $logoutResponse->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    /**
     * Test Middleware: Guest redirect on protected admin routes
     */
    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test Page 8: Admin Dashboard
     */
    public function test_admin_dashboard_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('KUMALA ADMIN');
        $response->assertSee('Perlu Ditindaklanjuti Segera');
    }

    /**
     * Test Page 9: Manajemen Properti (Master)
     */
    public function test_admin_properties_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/properti');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Properti');
        $response->assertSee('INVENTORY HUB');
    }

    /**
     * Test Page 10: Manajemen Listing Masuk (Gatekeeper)
     */
    public function test_admin_submissions_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/listing-masuk');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Listing Masuk');
        $response->assertSee('SLA GATEKEEPER VERIFICATION');
    }

    /**
     * Test Page 11: Riwayat Terjual & Tersewa (Closing Archive)
     */
    public function test_admin_transactions_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/riwayat-transaksi');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Terjual & Tersewa');
        $response->assertSee('CLOSING ARCHIVE');
    }

    /**
     * Test Page 12: Manajemen Data Inquiry (CRM)
     */
    public function test_admin_inquiries_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/inquiry');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Inquiry');
        $response->assertSee('LEAD PIPELINE', false);
    }

    /**
     * Test Page 13: Pengaturan Sistem & Master Data
     */
    public function test_admin_settings_page_returns_successful_response(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get('/admin/pengaturan');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem & Master Data');
        $response->assertSee('SYSTEM CONFIGURATION', false);
    }

    /**
     * Phase 3: Test PropertyDataService reads from database
     */
    public function test_property_data_service_and_filtering(): void
    {
        // Seed properties
        $this->seed(\Database\Seeders\PropertySeeder::class);

        $all = \App\Services\PropertyDataService::getAll();
        $this->assertNotEmpty($all);
        $this->assertEquals('#KP-8821', $all[0]['id']);

        $bySlug = \App\Services\PropertyDataService::findBySlug('villa-cliffside-horizon-uluwatu');
        $this->assertNotNull($bySlug);
        $this->assertEquals('Villa Cliffside Horizon Uluwatu', $bySlug['title']);

        $filtered = \App\Services\PropertyDataService::filter('Jual', 'Villa', 'Bali');
        $this->assertNotEmpty($filtered);
    }

    /**
     * Phase 3: Test Admin Property filter and pagination
     */
    public function test_admin_properties_filter_and_pagination(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // Test with search filter
        $response = $this->actingAs($admin)->get('/admin/properti?search=Villa&status=Aktif&type=Villa&region=Bali');
        $response->assertStatus(200);
        $response->assertSee('Villa Cliffside Horizon Uluwatu');

        // Test pagination page 2
        $responsePage2 = $this->actingAs($admin)->get('/admin/properti?page=2');
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Menampilkan');
    }

    /**
     * Phase 4: Test submit titip properti form with real database persistence
     */
    public function test_titip_properti_submission_persists_to_database(): void
    {
        $this->seed(\Database\Seeders\SubmissionSeeder::class);

        $payload = [
            'owner_name' => 'Bambang Soedarmono',
            'owner_phone' => '081234567890',
            'owner_email' => 'bambang@soedar.id',
            'ownership_status' => 'direct_owner',
            'transaction_type' => 'Jual',
            'property_category' => 'Villa Mewah',
            'property_title' => 'Cliffside Sunset Palace Uluwatu',
            'city_region' => 'Bali - Badung',
            'specific_area' => 'Uluwatu Cliff',
            'full_address' => 'Jl. Pantai Suluban No. 99, Pecatu, Badung',
            'asking_price' => '25.000.000.000',
            'land_size' => 600,
            'building_size' => 450,
            'bedrooms' => '4 KT',
            'bathrooms' => '4 KM',
            'facilities' => ['pool', 'furnished', 'shm'],
            'description' => 'Villa tropis eksklusif pemandangan laut lepas dengan spesifikasi mewah berstandar internasional.',
            'legal_consent' => '1',
        ];

        $response = $this->postJson('/titip-properti', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $subId = $response->json('submission_id');
        $this->assertMatchesRegularExpression('/^#TP-\d+$/', $subId);

        // Verify in database
        $this->assertDatabaseHas('submissions', [
            'id' => $subId,
            'title' => 'Cliffside Sunset Palace Uluwatu',
            'owner_name' => 'Bambang Soedarmono',
            'status' => 'pending',
        ]);

        // Verify it appears in Admin Submissions Page
        $admin = $this->getAdminUser();
        $adminRes = $this->actingAs($admin)->get('/admin/listing-masuk');
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Cliffside Sunset Palace Uluwatu');
    }

    /**
     * Phase 4: Test submit inquiry form with real database persistence
     */
    public function test_inquiry_submission_persists_to_database(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $this->seed(\Database\Seeders\LeadSeeder::class);

        $payload = [
            'property_id' => '#KP-8821',
            'name' => 'Dr. William Hartono',
            'phone' => '+62 811-2233-4455',
            'email' => 'dr.william@hartonogroup.com',
            'inquiry_type' => 'beli',
            'payment_scheme' => 'Cash Keras',
            'survey_date' => '2026-10-15',
            'notes' => 'Kami membutuhkan jadwal survei privat hari Kamis pukul 10.00 WIB.',
        ];

        $response = $this->postJson('/inquiry', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $leadId = $response->json('lead_id');
        $this->assertMatchesRegularExpression('/^#IQ-\d+$/', $leadId);

        // Verify in database
        $this->assertDatabaseHas('leads', [
            'id' => $leadId,
            'client_name' => 'Dr. William Hartono',
            'stage' => 'Baru',
        ]);

        // Verify it appears in Admin Inquiries Page
        $admin = $this->getAdminUser();
        $adminRes = $this->actingAs($admin)->get('/admin/inquiry');
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Dr. William Hartono');
    }

    /**
     * Phase 5: Test Submission Approval Flow creates ACTIVE Property and Audit Log
     */
    public function test_phase_5_submission_approval_flow(): void
    {
        $this->seed(\Database\Seeders\SubmissionSeeder::class);
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        $subId = rawurlencode('#TP-204');

        // 1. Update checklist and curator notes
        $chkRes = $this->actingAs($admin)->postJson("/admin/submissions/{$subId}/checklist", [
            'identity' => true,
            'maps' => true,
            'shm_match' => true,
            'photos_hd' => true,
            'notes' => 'Aset lolos kurasi legalitas dan foto HD telah dioptimasi.',
        ]);
        $chkRes->assertStatus(200);
        $this->assertDatabaseHas('submissions', [
            'id' => '#TP-204',
            'notes' => 'Aset lolos kurasi legalitas dan foto HD telah dioptimasi.',
        ]);

        // 2. Approve submission -> automatically becomes ACTIVE property
        $appRes = $this->actingAs($admin)->postJson("/admin/submissions/{$subId}/approve");
        $appRes->assertStatus(200);
        $appRes->assertJson(['success' => true]);

        $createdPropertyId = $appRes->json('property_id');
        $this->assertNotNull($createdPropertyId);

        // Verify submission status is approved
        $this->assertDatabaseHas('submissions', [
            'id' => '#TP-204',
            'status' => 'approved',
        ]);

        // Verify property is created and ACTIVE
        $this->assertDatabaseHas('properties', [
            'id' => $createdPropertyId,
            'status' => 'ACTIVE',
        ]);

        // Verify audit log exists
        $this->assertDatabaseHas('audit_logs', [
            'status' => 'Published',
        ]);
    }

    /**
     * Phase 5: Test Closing Transaction updates Property to SOLD/RENTED
     */
    public function test_phase_5_transaction_closing_flow(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // Property to close: #KP-8821 (Villa Cliffside Horizon Uluwatu)
        $prop = \App\Models\Property::find('#KP-8821');
        $this->assertNotNull($prop);
        $this->assertEquals('ACTIVE', $prop->status);

        $payload = [
            'property_id' => '#KP-8821',
            'deal_price' => 'Rp 40.000.000.000',
            'parties' => 'Penjual: Bpk. Made / Pembeli: Konsorsium Bali',
            'notary' => 'Dra. Sri Wahyuni, S.H.',
            'trx_type' => 'TERJUAL - JUAL BELI',
            'date' => '01 Okt 2026',
        ];

        $res = $this->actingAs($admin)->postJson('/admin/riwayat-transaksi', $payload);
        $res->assertStatus(200);
        $res->assertJson(['success' => true]);

        // Verify transaction recorded
        $this->assertDatabaseHas('transactions', [
            'property_id' => '#KP-8821',
            'deal_price' => 'Rp 40.000.000.000',
        ]);

        // Verify property status automatically changed to SOLD
        $prop->refresh();
        $this->assertEquals('SOLD', $prop->status);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'status' => 'Closed',
        ]);
    }

    /**
     * Phase 5: Test Lead Stage, Assign, and Survey Schedule
     */
    public function test_phase_5_lead_and_survey_actions(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $this->seed(\Database\Seeders\LeadSeeder::class);
        $admin = $this->getAdminUser();

        $leadId = rawurlencode('#IQ-2209');

        // 1. Update Lead Stage
        $stageRes = $this->actingAs($admin)->postJson("/admin/inquiry/{$leadId}/stage", [
            'stage' => 'Follow-Up',
        ]);
        $stageRes->assertStatus(200);
        $this->assertDatabaseHas('leads', [
            'id' => '#IQ-2209',
            'stage' => 'Follow-Up',
        ]);

        // 2. Schedule Survey
        $surveyRes = $this->actingAs($admin)->postJson("/admin/inquiry/{$leadId}/survey", [
            'survey_date' => '2026-10-10',
            'survey_time' => '14:00',
            'property_id' => '#KP-8821',
            'client_name' => 'Bpk. Richard Tan',
        ]);
        $surveyRes->assertStatus(200);
        $this->assertDatabaseHas('surveys', [
            'lead_id' => '#IQ-2209',
            'status' => 'Terkonfirmasi',
        ]);

        // 3. Manual Lead Input
        $manualRes = $this->actingAs($admin)->postJson('/admin/inquiry/manual', [
            'client_name' => 'Bpk. Hendra Gunawan',
            'client_phone' => '+62 811-3344-5566',
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'message' => 'Minat survei unit minggu depan.',
        ]);
        $manualRes->assertStatus(200);
        $this->assertDatabaseHas('leads', [
            'client_name' => 'Bpk. Hendra Gunawan',
        ]);
    }

    /**
     * Phase 6: Test Property CRUD (store, update, toggle status, delete)
     */
    public function test_phase_6_property_crud(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Store new property
        $response = $this->actingAs($admin)->post('/admin/properti/store', [
            'title' => 'Penthouse Sudirman Suites SCBD',
            'category' => 'Apartemen & Penthouse',
            'transaction_type' => 'Jual',
            'price' => 'Rp 35.000.000.000',
            'price_notes' => 'Nego Tipis',
            'location_city' => 'Jakarta Selatan',
            'location_area' => 'SCBD Senayan',
            'location_address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan',
            'legal_status' => 'Strata Title',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'land_size' => 0,
            'building_size' => 320,
            'floors' => 1,
            'electricity' => '11000 VA',
            'orientation' => 'Utara',
            'description' => 'Penthouse mewah dengan pemandangan cakrawala kota Jakarta 360 derajat.',
            'status' => 'ACTIVE',
            'features' => 'Private Pool, Smart Home, Concierge 24 Jam',
        ]);
        $response->assertRedirect('/admin/properti');

        $newProp = \App\Models\Property::where('title', 'Penthouse Sudirman Suites SCBD')->first();
        $this->assertNotNull($newProp);
        $this->assertEquals('ACTIVE', $newProp->status);

        // 2. Update property
        $encodedId = rawurlencode($newProp->id);
        $updateRes = $this->actingAs($admin)->post("/admin/properti/{$encodedId}/update", [
            'title' => 'Penthouse Sudirman Suites SCBD - Renovated',
            'category' => 'Apartemen & Penthouse',
            'transaction_type' => 'Jual',
            'price' => 'Rp 38.000.000.000',
            'location_city' => 'Jakarta Selatan',
            'location_address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan',
            'legal_status' => 'Strata Title',
            'description' => 'Penthouse mewah full renovated.',
            'status' => 'ACTIVE',
        ]);
        $updateRes->assertRedirect('/admin/properti');
        $this->assertDatabaseHas('properties', [
            'id' => $newProp->id,
            'title' => 'Penthouse Sudirman Suites SCBD - Renovated',
        ]);

        // 3. Toggle Status to ARCHIVED
        $toggleRes = $this->actingAs($admin)->postJson("/admin/properti/{$encodedId}/status", [
            'status' => 'ARCHIVED',
        ]);
        $toggleRes->assertStatus(200);
        $this->assertDatabaseHas('properties', [
            'id' => $newProp->id,
            'status' => 'ARCHIVED',
        ]);

        // 4. Delete property
        $delRes = $this->actingAs($admin)->postJson("/admin/properti/{$encodedId}/delete");
        $delRes->assertStatus(200);
        $this->assertDatabaseMissing('properties', [
            'id' => $newProp->id,
        ]);
    }

    /**
     * Phase 6: Test Batch Property Actions
     */
    public function test_phase_6_batch_property_actions(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        $ids = ['#KP-8821', '#KP-8822'];

        // Batch unpublish
        $res = $this->actingAs($admin)->post('/admin/properti/batch', [
            'property_ids' => $ids,
            'batch_action' => 'unpublish',
        ]);
        $res->assertRedirect('/admin/properti');
        $this->assertEquals('ARCHIVED', \App\Models\Property::find('#KP-8821')->status);
        $this->assertEquals('ARCHIVED', \App\Models\Property::find('#KP-8822')->status);

        // Batch publish
        $res2 = $this->actingAs($admin)->post('/admin/properti/batch', [
            'property_ids' => $ids,
            'batch_action' => 'publish',
        ]);
        $res2->assertRedirect('/admin/properti');
        $this->assertEquals('ACTIVE', \App\Models\Property::find('#KP-8821')->status);
        $this->assertEquals('ACTIVE', \App\Models\Property::find('#KP-8822')->status);
    }

    /**
     * Phase 6: Test Settings, Category, and Region management
     */
    public function test_phase_6_settings_and_master_data(): void
    {
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $admin = $this->getAdminUser();

        // 1. Update general settings
        $res = $this->actingAs($admin)->post('/admin/pengaturan', [
            'hotline_admin' => '+62 899-9999-0000',
            'email_business' => 'vip@kumalaproperty.co.id',
            'standard_commission_pct' => '3.0',
            'standard_commission_rent_pct' => '6.0',
            'notify_new_submission' => '1',
        ]);
        $res->assertRedirect('/admin/pengaturan');
        $this->assertEquals('+62 899-9999-0000', \App\Models\Setting::get('hotline_admin'));
        $this->assertEquals('3.0', \App\Models\Setting::get('standard_commission_pct'));

        // 2. Add Category
        $catRes = $this->actingAs($admin)->post('/admin/pengaturan/kategori/store', [
            'name' => 'Kondominium Mewah',
            'subtypes' => 'High Floor, Sea View',
        ]);
        $catRes->assertRedirect('/admin/pengaturan');
        $this->assertDatabaseHas('categories', [
            'name' => 'Kondominium Mewah',
        ]);

        // 3. Add Region
        $regRes = $this->actingAs($admin)->post('/admin/pengaturan/wilayah/store', [
            'name' => 'DI Yogyakarta - Sleman',
            'city' => 'Yogyakarta',
            'area' => 'Sleman, Kaliurang',
        ]);
        $regRes->assertRedirect('/admin/pengaturan');
        $this->assertDatabaseHas('regions', [
            'name' => 'DI Yogyakarta - Sleman',
        ]);

        // 4. Verify public pages receive active master data
        $exploreRes = $this->get('/explore');
        $exploreRes->assertStatus(200);
        $exploreRes->assertSee('Kondominium Mewah');
        $exploreRes->assertSee('DI Yogyakarta - Sleman');
    }

    /**
     * Phase 7: Test Admin Global Search and Redirections with Audit Log
     */
    public function test_phase_7_admin_global_search(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Search #KP-xxx (Property)
        $resKp = $this->actingAs($admin)->get('/admin/search?q=%23KP-8821');
        $resKp->assertRedirect(route('admin.properties', ['search' => '#KP-8821']));

        // 2. Search #TP-xxx (Submission)
        $resTp = $this->actingAs($admin)->get('/admin/search?q=%23TP-1001');
        $resTp->assertRedirect(route('admin.submissions', ['status' => 'all', 'search' => '#TP-1001']));

        // 3. Search #IQ-xxx (Inquiry)
        $resIq = $this->actingAs($admin)->get('/admin/search?q=%23IQ-9001');
        $resIq->assertRedirect(route('admin.inquiries'));

        // 4. Search #CLS-xxx (Closing Transaction)
        $resCls = $this->actingAs($admin)->get('/admin/search?q=%23CLS-501');
        $resCls->assertRedirect(route('admin.transactions'));

        // 5. Search generic keyword (Location / Name)
        $resGeneric = $this->actingAs($admin)->get('/admin/search?q=Menteng');
        $resGeneric->assertRedirect(route('admin.properties', ['search' => 'Menteng']));

        // 6. Verify Audit Log entry created for SEARCH
        $this->assertDatabaseHas('audit_logs', [
            'status' => 'Search',
        ]);
    }

    /**
     * Phase 7: Test Export Properties Inventory CSV
     */
    public function test_phase_7_export_properties_csv(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/export/properties');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'kumala_properties_'));

        // Verify CSV content contains header columns and sample property ID
        $content = $response->streamedContent();
        $this->assertStringContainsString('ID Properti', $content);
        $this->assertStringContainsString('Judul Unit', $content);
        $this->assertStringContainsString('#KP-8821', $content);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Ekspor Data Inventaris Properti (.CSV)',
        ]);
    }

    /**
     * Phase 7: Test Export Daily Operational Report CSV
     */
    public function test_phase_7_export_daily_report_csv(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $this->seed(\Database\Seeders\TransactionSeeder::class);
        $this->seed(\Database\Seeders\LeadSeeder::class);
        $this->seed(\Database\Seeders\SubmissionSeeder::class);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/export/daily-report');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'kumala_daily_report_'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('METRIK LAPORAN OPERASIONAL HARIAN', $content);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Ekspor Laporan Harian Operasional (.CSV)',
        ]);
    }

    /**
     * Phase 7: Test Export Closing Transactions Official Printable/PDF View
     */
    public function test_phase_7_export_transactions_pdf(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $this->seed(\Database\Seeders\TransactionSeeder::class);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/export/transactions');
        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI CLOSING TRANSAKSI');
        $response->assertSee('Kumala Property Group');

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Cetak / Ekspor Rekapitulasi Closing Transaksi (PDF)',
        ]);
    }

    /**
     * Revisi 1: Test explore page does NOT render active filters section when no filter is applied or defaults are used
     */
    public function test_explore_page_does_not_render_active_filters_when_no_filter_applied(): void
    {
        // 1. Plain /explore
        $response = $this->get('/explore');
        $response->assertStatus(200);
        $response->assertDontSee('FILTER AKTIF:');
        $response->assertDontSee('active-filters-section');
        $response->assertDontSee('Reset semua');
        $response->assertDontSee('Wilayah Strategis');

        // 2. Default values provided
        $responseDefaults = $this->get('/explore?type=Semua&category=Semua+Tipe+Properti&location=Semua+Wilayah&price_range=Semua+Rentang+Harga');
        $responseDefaults->assertStatus(200);
        $responseDefaults->assertDontSee('FILTER AKTIF:');
        $responseDefaults->assertDontSee('active-filters-section');
        $responseDefaults->assertDontSee('Reset semua');
    }

    /**
     * Revisi 1: Test explore page renders active filters chips only for applied non-default filters
     */
    public function test_explore_page_renders_active_filters_when_filter_applied(): void
    {
        $response = $this->get('/explore?type=Jual&category=Villa&location=Bali');
        $response->assertStatus(200);
        $response->assertSee('FILTER AKTIF:');
        $response->assertSee('Tipe: Jual');
        $response->assertSee('Villa');
        $response->assertSee('Bali');
        $response->assertSee('Reset semua');
        $response->assertDontSee('Wilayah Strategis');
    }

    /**
     * Revisi 2: Test newly submitted property is REVIEW and does NOT appear in public catalog until approved
     */
    public function test_newly_submitted_property_does_not_appear_in_public_catalog_until_approved(): void
    {
        $payload = [
            'owner_name' => 'Pak Hendra Pratama',
            'owner_phone' => '081299887766',
            'owner_email' => 'hendra@testreview.id',
            'ownership_status' => 'direct_owner',
            'transaction_type' => 'Jual',
            'property_category' => 'Rumah Mewah',
            'property_title' => 'Mansion Mewah Pondok Indah Unit Uji',
            'city_region' => 'Jakarta Selatan',
            'specific_area' => 'Pondok Indah',
            'full_address' => 'Jl. Bukit Hijau No. 12, Pondok Indah',
            'asking_price' => '45.000.000.000',
            'land_size' => 700,
            'building_size' => 600,
            'bedrooms' => '5 KT',
            'bathrooms' => '5 KM',
            'facilities' => ['pool', 'furnished', 'shm'],
            'description' => 'Mansion mewah eksklusif siap huni di kawasan Pondok Indah.',
            'legal_consent' => '1',
        ];

        $postRes = $this->postJson('/titip-properti', $payload);
        $postRes->assertStatus(200);
        $postRes->assertJson(['success' => true]);

        $subId = $postRes->json('submission_id');
        $propId = $postRes->json('property_id');
        $propSlug = $postRes->json('property_slug');

        // Verify status in database: submission is pending, property is REVIEW
        $this->assertDatabaseHas('submissions', [
            'id' => $subId,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('properties', [
            'id' => $propId,
            'status' => 'REVIEW',
        ]);

        // Verify it DOES NOT appear in public catalog
        // 1. Home
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertDontSee('Mansion Mewah Pondok Indah Unit Uji');

        // 2. Explore
        $exploreRes = $this->get('/explore');
        $exploreRes->assertStatus(200);
        $exploreRes->assertDontSee('Mansion Mewah Pondok Indah Unit Uji');

        // 3. Detail page for guest: 404
        $detailRes = $this->get("/properti/{$propSlug}");
        $detailRes->assertStatus(404);

        // Approve via admin
        $admin = $this->getAdminUser();
        $listingBeforeRes = $this->actingAs($admin)->get('/admin/listing-masuk');
        $listingBeforeRes->assertStatus(200);
        $listingBeforeRes->assertSee('Mansion Mewah Pondok Indah Unit Uji');

        $encodedSubId = rawurlencode($subId);
        $approveRes = $this->actingAs($admin)->postJson("/admin/listing-masuk/{$encodedSubId}/approve");
        $approveRes->assertStatus(200);
        $approveRes->assertJson(['success' => true]);

        // Verify status after approval in database
        $this->assertDatabaseHas('submissions', [
            'id' => $subId,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('properties', [
            'id' => $propId,
            'status' => 'ACTIVE',
        ]);

        // After approve, listing disappears from active queue in /admin/listing-masuk
        AdminNotification::query()->delete();
        $listingAfterRes = $this->actingAs($admin)->get('/admin/listing-masuk');
        $listingAfterRes->assertStatus(200);
        $listingAfterRes->assertDontSee('Mansion Mewah Pondok Indah Unit Uji');

        // But it remains in database and can be seen in approved history tab
        $listingApprovedTabRes = $this->actingAs($admin)->get('/admin/listing-masuk?status=approved');
        $listingApprovedTabRes->assertStatus(200);
        $listingApprovedTabRes->assertSee('Mansion Mewah Pondok Indah Unit Uji');

        // Now it MUST appear in Explore catalog
        $exploreAfterRes = $this->get('/explore');
        $exploreAfterRes->assertStatus(200);
        $exploreAfterRes->assertSee('Mansion Mewah Pondok Indah Unit Uji');

        // Detail page for guest is now accessible
        $detailAfterRes = $this->get("/properti/{$propSlug}");
        $detailAfterRes->assertStatus(200);
        $detailAfterRes->assertSee('Mansion Mewah Pondok Indah Unit Uji');
    }

    /**
     * Revisi 2: Test admin can reject submission with reason and property is archived
     */
    public function test_admin_can_reject_submission_with_reason(): void
    {
        $payload = [
            'owner_name' => 'Budi Santoso',
            'owner_phone' => '081233445566',
            'owner_email' => 'budi@testreject.id',
            'ownership_status' => 'direct_owner',
            'transaction_type' => 'Jual',
            'property_category' => 'Rumah Mewah',
            'property_title' => 'Rumah Ditolak Spesifikasi Tidak Lengkap',
            'city_region' => 'Jakarta Selatan',
            'specific_area' => 'Cilandak',
            'full_address' => 'Jl. Cilandak No. 5',
            'asking_price' => '10.000.000.000',
            'land_size' => 200,
            'building_size' => 150,
            'bedrooms' => '3 KT',
            'bathrooms' => '2 KM',
            'facilities' => ['shm'],
            'description' => 'Rumah tes penolakan kurator.',
            'legal_consent' => '1',
        ];

        $postRes = $this->postJson('/titip-properti', $payload);
        $postRes->assertStatus(200);

        $subId = $postRes->json('submission_id');
        $propId = $postRes->json('property_id');

        $admin = $this->getAdminUser();
        $encodedSubId = rawurlencode($subId);
        $rejectRes = $this->actingAs($admin)->postJson("/admin/listing-masuk/{$encodedSubId}/reject", [
            'notes' => 'Dokumen SHM tidak dapat diverifikasi BPN.',
        ]);
        $rejectRes->assertStatus(200);
        $rejectRes->assertJson(['success' => true]);

        $this->assertDatabaseHas('submissions', [
            'id' => $subId,
            'status' => 'rejected',
            'notes' => 'Dokumen SHM tidak dapat diverifikasi BPN.',
        ]);

        $this->assertDatabaseHas('properties', [
            'id' => $propId,
            'status' => 'ARCHIVED',
        ]);

        $exploreRes = $this->get('/explore');
        $exploreRes->assertDontSee('Rumah Ditolak Spesifikasi Tidak Lengkap');
    }

    /**
     * Revisi Listing Masuk: Submission disappears from active queue upon approval, appears in public catalog, and remains in database
     */
    public function test_approved_submission_disappears_from_active_queue_and_appears_in_public_catalog(): void
    {
        $payload = [
            'owner_name' => 'Hendro Wijaya',
            'owner_phone' => '081299887766',
            'owner_email' => 'hendro@testapprove.id',
            'ownership_status' => 'direct_owner',
            'transaction_type' => 'Jual',
            'property_category' => 'Villa Mewah',
            'property_title' => 'Villa Sanur Sunrise Eksklusif Unit Uji',
            'city_region' => 'Bali',
            'specific_area' => 'Sanur, Denpasar',
            'full_address' => 'Jl. Danau Tamblingan No. 88, Sanur',
            'asking_price' => '25.000.000.000',
            'land_size' => 600,
            'building_size' => 450,
            'bedrooms' => '4 KT',
            'bathrooms' => '4 KM',
            'facilities' => ['shm', 'swimming_pool'],
            'description' => 'Villa tropis mewah dekat pantai Sanur.',
            'legal_consent' => '1',
        ];

        $postRes = $this->postJson('/titip-properti', $payload);
        $postRes->assertStatus(200);

        $subId = $postRes->json('submission_id');
        $propId = $postRes->json('property_id');
        $propSlug = $postRes->json('property_slug');

        $admin = $this->getAdminUser();

        // 1. Before approval: item is present in /admin/listing-masuk active queue
        $queueBefore = $this->actingAs($admin)->get('/admin/listing-masuk');
        $queueBefore->assertStatus(200);
        $queueBefore->assertSee('Villa Sanur Sunrise Eksklusif Unit Uji');

        // Not yet in public catalog
        $exploreBefore = $this->get('/explore');
        $exploreBefore->assertDontSee('Villa Sanur Sunrise Eksklusif Unit Uji');

        // 2. Approve via admin
        $encodedSubId = rawurlencode($subId);
        $approveRes = $this->actingAs($admin)->postJson("/admin/listing-masuk/{$encodedSubId}/approve");
        $approveRes->assertStatus(200);
        $approveRes->assertJson(['success' => true]);

        // 3. After approval: item disappears from active queue in /admin/listing-masuk
        AdminNotification::query()->delete();
        $queueAfter = $this->actingAs($admin)->get('/admin/listing-masuk');
        $queueAfter->assertStatus(200);
        $queueAfter->assertDontSee('Villa Sanur Sunrise Eksklusif Unit Uji');

        // 4. Item still exists in database with status approved
        $this->assertDatabaseHas('submissions', [
            'id' => $subId,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('properties', [
            'id' => $propId,
            'status' => 'ACTIVE',
        ]);

        // 5. Item is visible in the approved history tab
        $approvedTab = $this->actingAs($admin)->get('/admin/listing-masuk?status=approved');
        $approvedTab->assertStatus(200);
        $approvedTab->assertSee('Villa Sanur Sunrise Eksklusif Unit Uji');

        // 6. Item is immediately visible in public catalog (/explore)
        $exploreAfter = $this->get('/explore');
        $exploreAfter->assertStatus(200);
        $exploreAfter->assertSee('Villa Sanur Sunrise Eksklusif Unit Uji');

        // 7. Property detail page is accessible
        $detailAfter = $this->get("/properti/{$propSlug}");
        $detailAfter->assertStatus(200);
        $detailAfter->assertSee('Villa Sanur Sunrise Eksklusif Unit Uji');
    }

    /**
     * Test notification and Indonesian validation messages for Titip Properti and Inquiry
     */
    public function test_form_validation_and_notification_responses(): void
    {
        $this->seed(\Database\Seeders\SettingSeeder::class);

        // 1. Validation failure for Titip Properti returns Indonesian error
        $failTitip = $this->postJson('/titip-properti', []);
        $failTitip->assertStatus(422);
        $failTitip->assertJsonValidationErrors(['owner_name', 'owner_phone', 'owner_email', 'property_title', 'description']);
        $failTitip->assertJsonFragment([
            'owner_name' => ['Nama lengkap pemilik atau kuasa hukum wajib diisi.'],
        ]);

        // 2. Validation failure for Inquiry returns Indonesian error
        $failInquiry = $this->postJson('/inquiry', []);
        $failInquiry->assertStatus(422);
        $failInquiry->assertJsonValidationErrors(['name', 'phone', 'email']);
        $failInquiry->assertJsonFragment([
            'name' => ['Nama lengkap wajib diisi.'],
            'phone' => ['Nomor WhatsApp atau telepon wajib diisi.'],
            'email' => ['Alamat email aktif wajib diisi.'],
        ]);

        // 3. Successful Titip Properti response contains submission ID and review notice
        $successTitip = $this->postJson('/titip-properti', [
            'owner_name' => 'Bpk. Test Notif',
            'owner_phone' => '+62 812-3344-5566',
            'owner_email' => 'test.notif@example.com',
            'property_title' => 'Penthouse Test Notifikasi Unit',
            'city_region' => 'Jakarta Selatan',
            'specific_area' => 'Kebayoran Baru',
            'full_address' => 'Jl. Senopati No. 10',
            'asking_price' => '18.000.000.000',
            'description' => 'Unit penthouse mewah dengan legalitas lengkap siap huni.',
            'legal_consent' => '1',
        ]);
        $successTitip->assertStatus(200);
        $successTitip->assertJson(['success' => true]);
        $this->assertNotEmpty($successTitip->json('submission_id'));

        // 4. Successful Inquiry response contains lead ID
        $successInquiry = $this->postJson('/inquiry', [
            'name' => 'Ibu Pembeli Test',
            'phone' => '+62 813-9988-7766',
            'email' => 'pembeli.test@example.com',
            'notes' => 'Tolong jadwalkan survei hari Sabtu.',
        ]);
        $successInquiry->assertStatus(200);
        $successInquiry->assertJson(['success' => true]);
        $this->assertNotEmpty($successInquiry->json('lead_id'));
    }

    /**
     * Test admin notification badges increase with new items and decrease when acted upon
     */
    public function test_admin_notification_badges_and_flash_messages(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Check initial active inquiries and pending submissions count
        $initialPending = \App\Models\Submission::where('status', 'pending')->count();
        $initialActiveInq = \App\Models\Lead::whereNotIn('stage', ['Closing', 'closing', 'Closing / Selesai', 'won', 'lost', 'batal', 'Batal', 'Selesai', 'selesai'])->count();

        // 2. Submit new inquiry
        $inqRes = $this->postJson('/inquiry', [
            'name' => 'Klien Prospek Baru',
            'phone' => '+62 812-0000-1111',
            'email' => 'prospek.baru@test.id',
            'notes' => 'Minat unit villa tropis.',
        ]);
        $inqRes->assertStatus(200);
        $newLeadId = $inqRes->json('lead_id');

        // Verify active inquiry badge increased by 1
        $currentActiveInq = \App\Models\Lead::whereNotIn('stage', ['Closing', 'closing', 'Closing / Selesai', 'won', 'lost', 'batal', 'Batal', 'Selesai', 'selesai'])->count();
        $this->assertEquals($initialActiveInq + 1, $currentActiveInq);

        // 3. Admin acts on the lead -> changes stage to 'Closing'
        $encodedLeadId = rawurlencode($newLeadId);
        $stageRes = $this->actingAs($admin)->postJson("/admin/inquiry/{$encodedLeadId}/stage", [
            'stage' => 'Closing',
        ]);
        $stageRes->assertStatus(200);

        // Verify active inquiry badge decreased back
        $afterClosingActiveInq = \App\Models\Lead::whereNotIn('stage', ['Closing', 'closing', 'Closing / Selesai', 'won', 'lost', 'batal', 'Batal', 'Selesai', 'selesai'])->count();
        $this->assertEquals($initialActiveInq, $afterClosingActiveInq);

        // 4. Test Flash Session rendering in admin layout
        $flashRes = $this->actingAs($admin)->withSession(['success' => 'Unit uji transaksi closing berhasil!'])->get('/admin/riwayat-transaksi');
        $flashRes->assertStatus(200);
        $flashRes->assertSee('Unit uji transaksi closing berhasil!');
        $flashRes->assertSee('showToast');

        // 5. Test Settings page does not double-render toast flash
        $settingsRes = $this->actingAs($admin)->withSession(['success' => 'Konfigurasi tersimpan'])->get('/admin/pengaturan');
        $settingsRes->assertStatus(200);
        $settingsRes->assertSee('Konfigurasi tersimpan');
    }

    /**
     * Test Tugas B: Admin notification metrics calculation and polling endpoint
     */
    public function test_admin_notification_metrics_endpoint_and_logic(): void
    {
        $admin = $this->getAdminUser();

        // 1. Guest cannot access endpoint (redirected to admin login)
        $guestRes = $this->get('/admin/notifications/count');
        $guestRes->assertRedirect(route('admin.login'));

        $guestJsonRes = $this->getJson('/admin/notifications/count');
        $guestJsonRes->assertRedirect(route('admin.login'));

        // 2. Initial state (empty database before test data)
        $authRes = $this->actingAs($admin)->getJson('/admin/notifications/count');
        $authRes->assertStatus(200);
        $authRes->assertJson([
            'pending_submissions' => 0,
            'new_inquiries' => 0,
            'upcoming_surveys' => 0,
            'total' => 0,
        ]);

        // 3. Submissions: pending counted, revision NOT counted
        \App\Models\Submission::create([
            'id' => 'SUB-TEST-1',
            'title' => 'Submission Pending',
            'owner_name' => 'Pak Budi',
            'owner_phone' => '08123456789',
            'owner_email' => 'budi@test.com',
            'status' => 'pending',
        ]);
        \App\Models\Submission::create([
            'id' => 'SUB-TEST-2',
            'title' => 'Submission Revisi',
            'owner_name' => 'Pak Joko',
            'owner_phone' => '08123456788',
            'owner_email' => 'joko@test.com',
            'status' => 'revision',
        ]);

        // 4. Inquiries: only 'Baru' / 'new' / 'NEW' counted, 'Follow-Up' NOT counted
        \App\Models\Lead::create([
            'id' => 'LEAD-TEST-1',
            'client_name' => 'Lead Baru',
            'client_phone' => '0811111111',
            'client_email' => 'lead1@test.com',
            'stage' => 'Baru',
        ]);
        \App\Models\Lead::create([
            'id' => 'LEAD-TEST-2',
            'client_name' => 'Lead Follow Up',
            'client_phone' => '0822222222',
            'client_email' => 'lead2@test.com',
            'stage' => 'Follow-Up',
        ]);

        // 5. Surveys: today/future counted; past NOT counted; finished/cancelled NOT counted
        \App\Models\Survey::create([
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'client_name' => 'Survei Besok',
            'agent_name' => 'Agent A',
            'survey_time' => '10:00 WIB',
            'survey_date' => now()->addDay()->toDateString(),
            'status' => 'Terkonfirmasi',
        ]);
        \App\Models\Survey::create([
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'client_name' => 'Survei Kemarin',
            'agent_name' => 'Agent B',
            'survey_time' => '11:00 WIB',
            'survey_date' => now()->subDay()->toDateString(),
            'status' => 'Terkonfirmasi',
        ]);
        \App\Models\Survey::create([
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'client_name' => 'Survei Selesai',
            'agent_name' => 'Agent C',
            'survey_time' => '13:00 WIB',
            'survey_date' => now()->addDay()->toDateString(),
            'status' => 'Selesai',
        ]);
        \App\Models\Survey::create([
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'client_name' => 'Survei Batal',
            'agent_name' => 'Agent D',
            'survey_time' => '14:00 WIB',
            'survey_date' => now()->addDay()->toDateString(),
            'status' => 'Batal',
        ]);

        // Check metrics: pending_submissions=1, new_inquiries=1, upcoming_surveys=1, total=3
        $checkRes = $this->actingAs($admin)->getJson('/admin/notifications/count');
        $checkRes->assertStatus(200);
        $checkRes->assertJson([
            'pending_submissions' => 1,
            'new_inquiries' => 1,
            'upcoming_surveys' => 1,
            'total' => 3,
        ]);

        // 6. When submission is approved and inquiry moves to 'Follow-Up', count decreases
        \App\Models\Submission::where('id', 'SUB-TEST-1')->update(['status' => 'approved']);
        \App\Models\Lead::where('id', 'LEAD-TEST-1')->update(['stage' => 'Follow-Up']);

        $afterRes = $this->actingAs($admin)->getJson('/admin/notifications/count');
        $afterRes->assertStatus(200);
        $afterRes->assertJson([
            'pending_submissions' => 0,
            'new_inquiries' => 0,
            'upcoming_surveys' => 1,
            'total' => 1,
        ]);
    }

    /**
     * Test Item 1.1: About page contact consultation form submission and validation
     */
    public function test_about_page_contact_form_submission_and_validation(): void
    {
        $admin = $this->getAdminUser();

        // 1. Validation fails when name, phone, or email is empty
        $invalidRes = $this->postJson('/inquiry', [
            'name' => '',
            'phone' => '',
            'email' => '',
            'subject' => 'Konsultasi Sewa Jangka Panjang / Korporat',
            'message' => 'Mencari villa untuk sewa 1 tahun.',
        ]);
        $invalidRes->assertStatus(422);
        $invalidRes->assertJsonValidationErrors(['name', 'phone', 'email']);

        // 2. Successful consultation submission from /tentang-kami
        $validRes = $this->postJson('/inquiry', [
            'name' => 'Bpk. Bambang Sutanto',
            'phone' => '+62 812-9988-7766',
            'email' => 'bambang@perusahaan.com',
            'subject' => 'Konsultasi Sewa Jangka Panjang / Korporat',
            'message' => 'Mencari villa untuk sewa korporat 2 tahun di Canggu.',
        ]);
        $validRes->assertStatus(200);
        $validRes->assertJson([
            'success' => true,
            'client_name' => 'Bpk. Bambang Sutanto',
            'property_title' => 'Konsultasi Umum (Tentang Kami)',
        ]);
        $leadId = $validRes->json('lead_id');
        $this->assertNotEmpty($leadId);

        // 3. Verify lead stored in DB with status 'Baru', property_id null, notes mapped correctly
        $lead = \App\Models\Lead::where('id', $leadId)->first();
        $this->assertNotNull($lead);
        $this->assertEquals('Baru', $lead->stage);
        $this->assertNull($lead->property_id);
        $this->assertEquals('Konsultasi Umum (Tentang Kami)', $lead->property_title);
        $this->assertEquals('sewa', $lead->inquiry_type);
        $this->assertStringContainsString('[Topik: Konsultasi Sewa Jangka Panjang / Korporat] Mencari villa untuk sewa korporat 2 tahun di Canggu.', $lead->quote);

        // 4. Verify admin notification bell counts this new inquiry
        $metricsRes = $this->actingAs($admin)->getJson('/admin/notifications/count');
        $metricsRes->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $metricsRes->json('new_inquiries'));

        // 5. Verify /admin/inquiry and /admin/dashboard render safely with null property_id
        $inquiryPageRes = $this->actingAs($admin)->get('/admin/inquiry');
        $inquiryPageRes->assertStatus(200);
        $inquiryPageRes->assertSee('Bpk. Bambang Sutanto');
        $inquiryPageRes->assertSee('Konsultasi Umum');

        $dashboardRes = $this->actingAs($admin)->get('/admin/dashboard');
        $dashboardRes->assertStatus(200);
        $dashboardRes->assertSee('Bpk. Bambang Sutanto');
    }

    /**
     * Test Item 1.2: Explore pagination, sorting, query persistence, and fallback
     */
    public function test_explore_page_pagination_sorting_and_fallback(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);

        // 1. Page 1 returns first 6 properties
        $resPage1 = $this->get('/explore?page=1');
        $resPage1->assertStatus(200);
        $resPage1->assertSee('Halaman 1 dari 2');
        $resPage1->assertSee('Total 9 properti terverifikasi');

        // 2. Page 2 returns remaining 3 properties and differs from Page 1
        $resPage2 = $this->get('/explore?page=2');
        $resPage2->assertStatus(200);
        $resPage2->assertSee('Halaman 2 dari 2');
        $this->assertNotEquals($resPage1->getContent(), $resPage2->getContent());

        // 3. Page out of range redirects to last valid page
        $outOfRangeRes = $this->get('/explore?page=999');
        $outOfRangeRes->assertRedirect('/explore?page=2');

        // 4. Invalid sort parameter gracefully falls back to default without error
        $invalidSortRes = $this->get('/explore?sort=unknown_param');
        $invalidSortRes->assertStatus(200);
        $invalidSortRes->assertSee('Eksplorasi Properti');

        // 5. Sort by harga_rendah and harga_tinggi
        $resHargaRendah = $this->get('/explore?sort=harga_rendah');
        $resHargaRendah->assertStatus(200);
        $resHargaTinggi = $this->get('/explore?sort=harga_tinggi');
        $resHargaTinggi->assertStatus(200);

        // 6. Filter parameters persist in pagination links when > 1 page
        \App\Services\KumalaDataHub::addProperty([
            'id' => '#KP-9999',
            'title' => 'Extra Unit Jual',
            'category' => 'Rumah Mewah',
            'transaction_type' => 'Jual',
            'price' => 20000000000,
            'location_city' => 'Jakarta Selatan',
            'location_area' => 'Kemang',
        ]);

        $resFiltered = $this->get('/explore?type=Jual&sort=harga_rendah&page=1');
        $resFiltered->assertStatus(200);
        $resFiltered->assertSee('type=Jual');
        $resFiltered->assertSee('sort=harga_rendah');
    }

    /**
     * Test Item 1.3: Admin property edit button, modal integration, and update flow
     */
    public function test_admin_properties_edit_button_modal_and_update_flow(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Admin table renders Edit button with .btn-icon-subtle and openPropertyModal("edit", ...)
        $tableRes = $this->actingAs($admin)->get('/admin/properti');
        $tableRes->assertStatus(200);
        $tableRes->assertSee('title="Edit Properti"', false);
        $tableRes->assertSee('btn-icon-subtle');
        $tableRes->assertSee('openPropertyModal("edit"', false);

        // 2. Submit property update
        $prop = \App\Models\Property::first();
        $this->assertNotNull($prop);
        $encodedId = rawurlencode($prop->id);

        $updatePayload = [
            'title' => 'Villa Horizon Uluwatu - Super Luxury Edition',
            'category' => 'Villa Mewah & Tropis',
            'transaction_type' => 'Jual',
            'price' => 'Rp 55.000.000.000',
            'price_notes' => 'Sertifikat Hak Milik On Hand',
            'location_city' => 'Bali',
            'location_area' => 'Uluwatu, Badung',
            'location_address' => 'Jl. Pantai Suluban No. 10, Pecatu, Uluwatu',
            'legal_status' => 'SHM (Hak Milik)',
            'status' => 'ACTIVE',
            'bedrooms' => 5,
            'bathrooms' => 5,
            'land_size' => 1200,
            'building_size' => 850,
            'description' => 'Edisi termewah dengan pemandangan tebing laut lepas.',
        ];

        $updateRes = $this->actingAs($admin)->post("/admin/properti/{$encodedId}/update", $updatePayload);
        $updateRes->assertRedirect('/admin/properti');

        // 3. Updated property appears on admin table
        $afterTableRes = $this->actingAs($admin)->get('/admin/properti');
        $afterTableRes->assertStatus(200);
        $afterTableRes->assertSee('Villa Horizon Uluwatu - Super Luxury Edition');
        $afterTableRes->assertSee('Rp 55.000.000.000');

        // 4. Updated property appears on public explore catalog
        $exploreRes = $this->get('/explore');
        $exploreRes->assertStatus(200);
        $exploreRes->assertSee('Villa Horizon Uluwatu - Super Luxury Edition');

        // 5. Validation error prevents update when required field is empty
        $invalidRes = $this->actingAs($admin)->post("/admin/properti/{$encodedId}/update", [
            'title' => '',
            'price' => '',
        ]);
        $invalidRes->assertSessionHasErrors(['title', 'price']);
    }

    /**
     * Test Fase 2.1: Dynamic regional recommendations and Option B fallback
     */
    public function test_detail_dynamic_region_recommendations(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);

        // 1. Property with other properties in same region (Bali)
        $detailRes = $this->get('/properti/villa-cliffside-horizon-uluwatu');
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Rekomendasi Properti Serupa di Bali');
        $detailRes->assertSee(route('explore', ['location' => 'Bali']));
        $detailRes->assertSee('Jelajahi Semua Listing Bali');

        // 2. Option B fallback: Property in a unique region with no other properties
        \App\Models\Property::create([
            'id' => '#KP-TEST-LOMBOK',
            'title' => 'Lombok Private Island Villa',
            'slug' => 'lombok-private-island-villa',
            'price' => 25000000000,
            'price_formatted' => 'Rp 25.000.000.000',
            'price_short' => 'Rp 25 Miliar',
            'price_label' => 'HARGA PENAWARAN',
            'price_notes' => 'Sertifikat Hak Milik (SHM)',
            'category' => 'Villa Mewah & Tropis',
            'transaction_type' => 'Jual',
            'legal_status' => 'SHM (Hak Milik)',
            'location_city' => 'Lombok',
            'location_area' => 'Mandalika',
            'location_address' => 'Jl. Pantai Kuta Mandalika No. 1',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'land_size' => 1500,
            'building_size' => 600,
            'image_main' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef',
            'images' => ['https://images.unsplash.com/photo-1540555700478-4be289fbecef'],
            'specs_tags' => ['4 KT', 'LT 1500m²', 'LB 600m²'],
            'status' => 'ACTIVE',
            'views_count' => 10,
            'inquiries_count' => 2,
            'description' => 'Villa tropis di tepi pantai Mandalika.',
        ]);

        $lombokRes = $this->get('/properti/lombok-private-island-villa');
        $lombokRes->assertStatus(200);
        $lombokRes->assertSee('Rekomendasi Properti Lainnya');
        $lombokRes->assertSee(route('explore'));
        $lombokRes->assertSee('Jelajahi Semua Listing');
        // Ensure the opened property is not in its own recommendations
        $lombokRes->assertDontSee('Lombok Private Island Villa</a>', false);
    }

    /**
     * Test Fase 2.2: Centralized hotline WA normalization & dynamic rendering across views
     */
    public function test_dynamic_hotline_wa_normalization_and_links(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);

        // 1. Helper normalization unit check
        $this->assertEquals('6281234567890', \App\Services\KumalaDataHub::normalizePhone('0812-3456-7890'));
        $this->assertEquals('6281199887711', \App\Services\KumalaDataHub::normalizePhone('+62 811-9988-7711'));
        $this->assertEquals('6289988776655', \App\Services\KumalaDataHub::normalizePhone('0899 8877 6655'));
        $this->assertEquals('6281199887711', \App\Services\KumalaDataHub::normalizePhone(''));

        // 2. Default hotline renders on public pages without hardcoded 6281288992345
        $pages = ['/', '/explore', '/properti/villa-cliffside-horizon-uluwatu', '/titip-properti'];
        foreach ($pages as $url) {
            $res = $this->get($url);
            $res->assertStatus(200);
            $res->assertSee('wa.me/6281199887711');
            $res->assertDontSee('wa.me/6281288992345');
        }

        // 3. Changing hotline in CMS dynamically updates all public WhatsApp links
        \App\Models\Setting::updateOrCreate(
            ['key' => 'hotline_wa'],
            ['value' => '0877-9900-1122', 'group' => 'general']
        );

        foreach ($pages as $url) {
            $res = $this->get($url);
            $res->assertStatus(200);
            $res->assertSee('wa.me/6287799001122');
            $res->assertDontSee('wa.me/6281199887711');
            $res->assertDontSee('wa.me/6281288992345');
        }
    }

    /**
     * Test Fase 2.3: Detail property Lightbox modal and accessible attributes
     */
    public function test_detail_lightbox_gallery_markup_and_accessibility(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);

        $res = $this->get('/properti/villa-cliffside-horizon-uluwatu');
        $res->assertStatus(200);

        // Lightbox dialog and accessibility attributes
        $res->assertSee('id="propertyLightboxModal"', false);
        $res->assertSee('role="dialog"', false);
        $res->assertSee('aria-modal="true"', false);
        $res->assertSee('aria-label="Galeri Foto Properti"', false);
        $res->assertSee('id="lightboxCloseBtn"', false);
        $res->assertSee('id="lightboxCounter"', false);
        $res->assertSee('id="lightboxPrevBtn"', false);
        $res->assertSee('id="lightboxNextBtn"', false);
        $res->assertSee('id="lightboxMainImg"', false);

        // Thumbnail triggers
        $res->assertSee('openGalleryLightbox(0)', false);
        $res->assertSee('openGalleryLightbox(4)', false);
    }

    /**
     * Test Fase 2.4: Admin modals deduplication and CSS restored
     */
    public function test_admin_modal_unification_and_btn_fav_tool(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Admin Properties: "+ Tambah Properti Baru" triggers openPropertyModal('create')
        $propRes = $this->actingAs($admin)->get('/admin/properti');
        $propRes->assertStatus(200);
        $propRes->assertSee("openPropertyModal('create')", false);
        $propRes->assertDontSee('id="addPropModal"', false);
        $propRes->assertDontSee('openAddPropertyModal', false);

        // 2. Admin Transactions: "+ Catat Transaksi Closing" opens modalClosing, no duplicate closingModal
        $txRes = $this->actingAs($admin)->get('/admin/riwayat-transaksi');
        $txRes->assertStatus(200);
        $txRes->assertSee('onclick="openClosingModal()"', false);
        $txRes->assertSee('id="modalClosing"', false);
        $txRes->assertDontSee('id="closingModal"', false);

        // 3. Verify .btn-fav-tool exists in style.css
        $css = file_get_contents(public_path('css/style.css'));
        $this->assertStringContainsString('.btn-fav-tool {', $css);
        $this->assertStringContainsString('.lightbox-modal {', $css);
    }

    /**
     * Test Fase 3.1: Admin daily report CSV download link and response
     */
    public function test_admin_daily_report_csv_download(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Dashboard renders download link without toast
        $dashRes = $this->actingAs($admin)->get('/admin/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('href="' . route('admin.export.daily') . '"', false);
        $dashRes->assertDontSee("onclick=\"showToast('Mengekspor Laporan Operasional Harian", false);

        // 2. Download returns 200 with proper CSV headers and BOM
        $csvRes = $this->actingAs($admin)->get('/admin/export/daily-report');
        $csvRes->assertStatus(200);
        $csvRes->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment; filename="kumala_daily_report_', $csvRes->headers->get('content-disposition'));
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csvRes->streamedContent());

        // 3. Guest is denied and redirected to login
        auth()->logout();
        $guestRes = $this->get('/admin/export/daily-report');
        $guestRes->assertRedirect('/admin/login');
    }

    /**
     * Test Fase 3.2: PDF print link in transactions page and response
     */
    public function test_admin_transactions_pdf_link_and_response(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Transactions page contains PDF link with target="_blank"
        $txRes = $this->actingAs($admin)->get('/admin/riwayat-transaksi');
        $txRes->assertStatus(200);
        $txRes->assertSee('href="' . route('admin.export.transactions') . '"', false);
        $txRes->assertSee('target="_blank"', false);
        $txRes->assertSee('Cetak Rekap PDF');

        // 2. Export PDF route responds successfully
        $pdfRes = $this->actingAs($admin)->get('/admin/export/transactions');
        $pdfRes->assertStatus(200);
        $pdfRes->assertSee('Rekapitulasi Closing Transaksi');
    }

    /**
     * Test Fase 3.3: Master Region creation, case-insensitive duplicate rejection, and explore filtering
     */
    public function test_admin_settings_store_region_and_explore_dropdown(): void
    {
        $this->seed(\Database\Seeders\PropertySeeder::class);
        $admin = $this->getAdminUser();

        // 1. Settings page renders "+ Tambah Wilayah" and #modal-region
        $settingsRes = $this->actingAs($admin)->get('/admin/pengaturan');
        $settingsRes->assertStatus(200);
        $settingsRes->assertSee('+ Tambah Wilayah');
        $settingsRes->assertSee('id="modal-region"', false);
        $settingsRes->assertSee('openRegionModal()', false);

        // 2. Successfully add new region
        $storeRes = $this->actingAs($admin)->post('/admin/pengaturan/wilayah/store', [
            '_form' => 'region',
            'name' => 'Jawa Barat - Bandung Raya',
            'city' => 'Bandung',
        ]);
        $storeRes->assertRedirect(route('admin.settings'));
        $storeRes->assertSessionHas('success');

        $this->assertDatabaseHas('regions', [
            'name' => 'Jawa Barat - Bandung Raya',
            'city' => 'Bandung',
            'is_active' => true,
        ]);

        // 3. Case-insensitive and whitespace-tolerant duplicate rejection
        $dupRes = $this->actingAs($admin)->post('/admin/pengaturan/wilayah/store', [
            '_form' => 'region',
            'name' => '  jawa   barat - bandung raya  ',
            'city' => 'Bandung',
        ]);
        $dupRes->assertSessionHasErrors(['name']);
        $dupErrors = session('errors')->get('name');
        $this->assertContains('Nama wilayah tersebut sudah terdaftar.', $dupErrors);

        // 4. Create property matching the new region city ('Bandung')
        \App\Models\Property::create([
            'id' => '#KP-TEST-BDG',
            'title' => 'Dago Heritage Highland Villa',
            'slug' => 'dago-heritage-highland-villa',
            'price' => 18000000000,
            'price_formatted' => 'Rp 18.000.000.000',
            'price_short' => 'Rp 18 Miliar',
            'price_label' => 'HARGA PENAWARAN',
            'price_notes' => 'SHM',
            'category' => 'Villa Mewah & Tropis',
            'transaction_type' => 'Jual',
            'legal_status' => 'SHM (Hak Milik)',
            'location_city' => 'Bandung',
            'location_area' => 'Dago Pakar',
            'location_address' => 'Jl. Dago Pakar Timur No. 12',
            'bedrooms' => 5,
            'bathrooms' => 4,
            'land_size' => 1000,
            'building_size' => 500,
            'image_main' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef',
            'images' => ['https://images.unsplash.com/photo-1540555700478-4be289fbecef'],
            'specs_tags' => ['5 KT', 'LT 1000m²', 'LB 500m²'],
            'status' => 'ACTIVE',
            'views_count' => 0,
            'inquiries_count' => 0,
            'description' => 'Villa sejuk di perbukitan Dago Pakar Bandung.',
        ]);

        // 5. Explore page renders new region in dropdown and finds the property
        $exploreRes = $this->get('/explore');
        $exploreRes->assertStatus(200);
        $exploreRes->assertSee('value="Bandung"', false);
        $exploreRes->assertSee('Jawa Barat - Bandung Raya');

        // Filter explore with location=Bandung finds Dago Heritage Highland Villa
        $filterRes = $this->get('/explore?location=Bandung');
        $filterRes->assertStatus(200);
        $filterRes->assertSee('Dago Heritage Highland Villa');
    }

    /**
     * Test Fase 3.4: Privacy & terms generic placeholder page and footer link
     */
    public function test_privacy_and_terms_page_and_footer_link(): void
    {
        // 1. Page returns 200 with generic placeholders
        $res = $this->get('/privasi-syarat');
        $res->assertStatus(200);
        $res->assertSee('Privasi &amp; Syarat Ketentuan', false);
        $res->assertSee('Kebijakan Privasi');
        $res->assertSee('Ketentuan Layanan');
        $res->assertSee('Ketentuan Konsinyasi dan Kurasi Listing');
        $res->assertSee('Kontak');
        $res->assertSee('[Teks resmi menunggu dari pihak Kumala Property]');

        // Ensure forbidden claims are NOT present
        $res->assertDontSee('Kumala Realty');
        $res->assertDontSee('HNWI');
        $res->assertDontSee('Versi 1.0 (Resmi)');

        // 2. Footer link on home page points to route('privacy-terms') and not '#'
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('href="' . route('privacy-terms') . '"', false);
        $homeRes->assertDontSee('<a href="#">Privasi & Syarat</a>', false);
    }

    /**
     * Test Fase 4.1: Guest cannot request revision
     */
    public function test_phase_4_guest_cannot_request_revision(): void
    {
        $response = $this->post('/admin/listing-masuk/TP-999/status', [
            'status' => 'revision',
            'notes' => 'Tolong lengkapi foto',
        ]);
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test Fase 4.1: Validation on empty or whitespace notes and max length
     */
    public function test_phase_4_revision_submission_validation(): void
    {
        $admin = $this->getAdminUser();

        $sub = Submission::create([
            'id' => '#TP-VAL-' . rand(100, 999),
            'title' => 'Test Unit Validation',
            'owner_name' => 'Budi Santoso',
            'owner_phone' => '081234567890',
            'owner_email' => 'budi@example.com',
            'status' => 'pending',
            'notes' => 'Catatan awal',
        ]);

        // 1. Empty notes -> 422
        $resEmpty = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($sub->id) . '/status', [
            'status' => 'revision',
            'notes' => '',
        ]);
        $resEmpty->assertStatus(422);

        // 2. Whitespace only notes -> 422
        $resSpaces = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($sub->id) . '/status', [
            'status' => 'revision',
            'notes' => '    ',
        ]);
        $resSpaces->assertStatus(422);

        // Status should NOT change
        $sub->refresh();
        $this->assertEquals('pending', $sub->status);
        $this->assertEquals('Catatan awal', $sub->notes);

        // 3. Notes > 1000 characters -> 422
        $resTooLong = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($sub->id) . '/status', [
            'status' => 'revision',
            'notes' => str_repeat('A', 1001),
        ]);
        $resTooLong->assertStatus(422);
    }

    /**
     * Test Fase 4.1: WhatsApp link generation, truncation at 500 chars, audit log, and DB persistence
     */
    public function test_phase_4_revision_submission_whatsapp_link_and_truncation(): void
    {
        $admin = $this->getAdminUser();

        $subId = '#TP-TEST-' . rand(100, 999);
        $sub = Submission::create([
            'id' => $subId,
            'title' => 'Villa Cliffside Uluwatu & Resort',
            'owner_name' => 'Maya Indah',
            'owner_phone' => '087712345678',
            'owner_email' => 'maya@example.com',
            'status' => 'pending',
            'asking_price' => 'Rp 15.000.000.000',
        ]);

        // A. Valid revision with special characters and quotes
        $notesWithChars = 'Mohon unggah ulang foto sertifikat SHM (hal. 2 & 3) dengan resolusi > 1080p, dan bukti PBB 2024!';
        $res = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($subId) . '/status', [
            'status' => 'revision',
            'notes' => $notesWithChars,
        ]);

        $res->assertStatus(200);
        $res->assertJson([
            'success' => true,
            'wa_valid' => true,
            'message' => 'Permintaan revisi dicatat. Lanjutkan kirim lewat WhatsApp.',
        ]);

        $waUrl = $res->json('wa_url');
        $this->assertNotNull($waUrl);
        $this->assertStringStartsWith('https://wa.me/6287712345678?text=', $waUrl);

        // Parse and decode query text
        $parsedUrl = parse_url($waUrl);
        parse_str($parsedUrl['query'] ?? '', $query);
        $decodedText = $query['text'] ?? '';

        $this->assertStringContainsString('Maya Indah', $decodedText);
        $this->assertStringContainsString($subId, $decodedText);
        $this->assertStringContainsString('Villa Cliffside Uluwatu & Resort', $decodedText);
        $this->assertStringContainsString($notesWithChars, $decodedText);
        $this->assertStringContainsString('Tim Kurator Kumala Property', $decodedText);

        // Assert database persistence
        $sub->refresh();
        $this->assertEquals('revision', $sub->status);
        $this->assertEquals($notesWithChars, $sub->notes);

        // Assert audit log recorded without sensitive phone data
        $this->assertDatabaseHas('audit_logs', [
            'action' => "Permintaan Revisi Pengajuan Listing {$subId}",
            'status' => 'Revision',
        ]);

        // B. Notes > 500 characters: full saved in DB, but truncated in WhatsApp URL
        $longNotes = str_repeat('Koreksi dokumen fisik wajib diperbarui. ', 20); // ~800 chars
        $resLong = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($subId) . '/status', [
            'status' => 'revision',
            'notes' => $longNotes,
        ]);
        $resLong->assertStatus(200);

        $sub->refresh();
        $this->assertEquals(trim($longNotes), $sub->notes); // Full 800 chars in database!

        $longWaUrl = $resLong->json('wa_url');
        parse_str(parse_url($longWaUrl)['query'] ?? '', $queryLong);
        $decodedLongText = $queryLong['text'] ?? '';
        $this->assertStringContainsString('...', $decodedLongText); // Truncated in URL
    }

    /**
     * Test Fase 4.1: Phone normalization formats (0877..., +62 877..., 62877...)
     */
    public function test_phase_4_phone_normalization_formats(): void
    {
        $admin = $this->getAdminUser();

        $formats = [
            '087712345678' => '6287712345678',
            '+62 877-1234-5678' => '6287712345678',
            '6287712345678' => '6287712345678',
            '+62 812-8899-2345' => '6281288992345',
        ];

        foreach ($formats as $inputPhone => $expectedDigits) {
            $sub = Submission::create([
                'id' => '#TP-PH-' . rand(1000, 9999),
                'title' => 'Test Phone Unit',
                'owner_name' => 'Owner Test',
                'owner_phone' => $inputPhone,
                'owner_email' => 'owner' . rand(100, 999) . '@example.com',
                'status' => 'pending',
            ]);

            $res = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($sub->id) . '/status', [
                'status' => 'revision',
                'notes' => 'Catatan revisi format telepon.',
            ]);

            $res->assertStatus(200);
            $this->assertTrue($res->json('wa_valid'));
            $this->assertStringStartsWith("https://wa.me/{$expectedDigits}?text=", $res->json('wa_url'));
        }
    }

    /**
     * Test Fase 4.1: Invalid or empty phone does not fail saving notes
     */
    public function test_phase_4_invalid_or_empty_phone_handling(): void
    {
        $admin = $this->getAdminUser();

        $invalidPhones = ['', '12345', '081', 'abc'];

        foreach ($invalidPhones as $idx => $invPhone) {
            $sub = Submission::create([
                'id' => '#TP-INV-' . $idx . '-' . rand(100, 999),
                'title' => 'Unit Tanpa Telepon Valid',
                'owner_name' => 'Tanpa Nomor',
                'owner_phone' => $invPhone,
                'owner_email' => 'inv' . $idx . '@example.com',
                'status' => 'pending',
            ]);

            $res = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($sub->id) . '/status', [
                'status' => 'revision',
                'notes' => 'Catatan tetap tersimpan meskipun nomor invalid.',
            ]);

            $res->assertStatus(200);
            $res->assertJson([
                'success' => true,
                'wa_valid' => false,
                'wa_url' => null,
                'message' => 'Catatan revisi tersimpan, namun nomor WhatsApp pemilik tidak valid sehingga chat tidak dapat dibuka.',
            ]);

            // Database should still be updated to revision with notes!
            $sub->refresh();
            $this->assertEquals('revision', $sub->status);
            $this->assertEquals('Catatan tetap tersimpan meskipun nomor invalid.', $sub->notes);
        }
    }

    /**
     * Test Submission: Edit and Delete flow with security, validation, approved protection, and audit logs
     */
    public function test_submission_edit_and_delete_flow_and_security(): void
    {
        $admin = $this->getAdminUser();

        // 1. Create a pending submission
        $sub = Submission::create([
            'id' => '#TP-TEST-' . rand(100, 999),
            'title' => 'Pending Luxury Villa for Edit',
            'owner_name' => 'Bpk. Rahadian',
            'owner_phone' => '081234567890',
            'owner_email' => 'rahadian@example.com',
            'owner_status' => 'Pemilik Langsung',
            'trx_type' => 'Jual',
            'category' => 'Villa Mewah',
            'city_region' => 'Bali',
            'specific_area' => 'Canggu',
            'location' => 'Canggu, Bali',
            'address' => 'Jl. Batu Bolong No. 10',
            'asking_price' => 'Rp 5.000.000.000',
            'asking_val' => 5000000000,
            'land_size' => 300,
            'building_size' => 250,
            'bedrooms' => '3 KT',
            'bathrooms' => '3 KM',
            'electricity' => '5500 VA',
            'legal' => 'SHM (Hak Milik)',
            'status' => 'pending',
            'notes' => 'Catatan awal kurasi.',
        ]);

        // 2. Guest cannot edit or delete
        $guestEdit = $this->post('/admin/listing-masuk/' . rawurlencode($sub->id) . '/update', [
            'title' => 'Hacked Title',
        ]);
        $guestEdit->assertRedirect(route('admin.login'));

        $guestDel = $this->delete('/admin/listing-masuk/' . rawurlencode($sub->id));
        $guestDel->assertRedirect(route('admin.login'));

        // 3. Admin edits submission with valid data (attempting to alter status should be ignored)
        $editRes = $this->actingAs($admin)->post('/admin/listing-masuk/' . rawurlencode($sub->id) . '/update', [
            'title' => 'Updated Modern Villa Canggu',
            'trx_type' => 'Jual',
            'category' => 'Villa Mewah & Tropis',
            'asking_price' => 'Rp 5.500.000.000',
            'legal' => 'SHM',
            'city_region' => 'Bali',
            'specific_area' => 'Pererenan',
            'address' => 'Jl. Pantai Pererenan No. 12',
            'land_size' => 350,
            'building_size' => 280,
            'bedrooms' => '4 KT',
            'bathrooms' => '4 KM',
            'electricity' => '7700 VA',
            'owner_name' => 'Bpk. Rahadian Pratama',
            'owner_phone' => '081299998888',
            'owner_email' => 'rahadian.pratama@example.com',
            'owner_status' => 'Kuasa Jual',
            'notes' => 'Catatan kurasi telah diperbarui.',
            'status' => 'approved', // Status injection attempt should be ignored!
        ]);

        $editRes->assertRedirect(route('admin.submissions', ['selected' => $sub->id]));
        $sub->refresh();
        $this->assertEquals('Updated Modern Villa Canggu', $sub->title);
        $this->assertEquals('Pererenan, Bali', $sub->location);
        $this->assertEquals(5500000000, $sub->asking_val);
        $this->assertEquals('pending', $sub->status); // Status must remain pending!

        // Audit log must NOT contain phone or email
        $editAudit = AuditLog::where('action', 'like', "%{$sub->id}%")->latest()->first();
        $this->assertNotNull($editAudit);
        $this->assertArrayNotHasKey('phone', $editAudit->details ?? []);
        $this->assertArrayNotHasKey('owner_phone', $editAudit->details ?? []);
        $this->assertArrayNotHasKey('owner_email', $editAudit->details ?? []);
        $this->assertArrayNotHasKey('email', $editAudit->details ?? []);

        // 4. Create an approved submission linked to an active property
        $approvedSub = Submission::create([
            'id' => '#TP-APP-' . rand(100, 999),
            'property_id' => '#KP-TEST-99',
            'title' => 'Already Approved Villa',
            'owner_name' => 'Ibu Melinda',
            'owner_phone' => '081122334455',
            'owner_email' => 'melinda@example.com',
            'status' => 'approved',
            'asking_price' => 'Rp 10.000.000.000',
        ]);

        // 4a. Edit approved submission must be rejected
        $editApprovedRes = $this->actingAs($admin)->postJson('/admin/listing-masuk/' . rawurlencode($approvedSub->id) . '/update', [
            'title' => 'New Title for Approved',
        ]);
        $editApprovedRes->assertStatus(422);
        $editApprovedRes->assertJson([
            'success' => false,
            'message' => 'Pengajuan sudah disetujui dan tayang di katalog. Perubahan data harus dilakukan melalui menu Manajemen Properti.',
        ]);

        // 4b. Delete approved submission must be rejected
        $delApprovedRes = $this->actingAs($admin)->deleteJson('/admin/listing-masuk/' . rawurlencode($approvedSub->id));
        $delApprovedRes->assertStatus(422);
        $delApprovedRes->assertJson([
            'success' => false,
            'message' => 'Pengajuan ini telah disetujui dan unit telah aktif di katalog. Harap arsipkan/hapus unit melalui menu Manajemen Properti terlebih dahulu.',
        ]);

        // 5. Delete pending submission must succeed
        $delRes = $this->actingAs($admin)->delete('/admin/listing-masuk/' . rawurlencode($sub->id));
        $delRes->assertRedirect(route('admin.submissions'));

        $this->assertDatabaseMissing('submissions', [
            'id' => $sub->id,
        ]);

        // Deletion audit log must contain ID and title only, no phone/email
        $delAudit = AuditLog::where('action', 'like', "%Hapus Pengajuan Listing {$sub->id}%")->first();
        $this->assertNotNull($delAudit);
        $this->assertArrayNotHasKey('owner_phone', $delAudit->details ?? []);
        $this->assertArrayNotHasKey('owner_email', $delAudit->details ?? []);
    }

    /**
     * Test Transactions: Edit, Delete, Property Status Restoration, and Role Authorization
     */
    public function test_transaction_edit_delete_flow_property_restoration_and_super_admin_role(): void
    {
        $superAdmin = $this->getAdminUser();
        $curator = $this->getCuratorUser();

        $prop = Property::create([
            'id' => '#KP-TX-' . rand(100, 999),
            'slug' => 'villa-closing-test-' . uniqid(),
            'title' => 'Villa Closing Test',
            'category' => 'Villa Mewah',
            'transaction_type' => 'Jual',
            'price' => 40000000000,
            'price_formatted' => 'Rp 40.000.000.000',
            'location_city' => 'Bali',
            'location_area' => 'Seminyak',
            'location_address' => 'Jl. Kayu Aya No. 9',
            'legal_status' => 'SHM',
            'status' => 'ACTIVE',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'land_size' => 600,
            'building_size' => 450,
            'specs_tags' => ['4 KT', '4 KM'],
            'image_main' => 'https://example.com/tx.jpg',
            'description' => 'Test description for tx',
        ]);

        // 1. Create first closing transaction (JUAL)
        $tx1Id = '#CLS-TX1-' . rand(100, 999);
        $tx1 = Transaction::create([
            'id' => $tx1Id,
            'date' => now()->format('d M Y'),
            'property_id' => $prop->id,
            'property_title' => $prop->title,
            'location' => 'Bali - Seminyak',
            'trx_type' => 'TERJUAL - JUAL BELI',
            'asking_price' => 'Rp 40.000.000.000',
            'deal_price' => 'Rp 38.000.000.000',
            'deal_val' => 38000000000,
            'diff_pct' => '-5%',
            'parties' => 'Penjual: Bpk. Dani / Pembeli: Ibu Siska',
            'notary' => 'Dra. Sri Wahyuni, S.H.',
        ]);

        // Simulate property status set to SOLD
        $prop->status = 'SOLD';
        $prop->save();

        // 2. Guest cannot edit or delete transaction
        $guestEdit = $this->post('/admin/riwayat-transaksi/' . rawurlencode($tx1->id) . '/update', [
            'trx_type' => 'TERSEWA - 2 TAHUN',
            'deal_price' => 'Rp 39.000.000.000',
            'parties' => 'Penjual: Bpk. Dani / Pembeli: Ibu Siska',
            'notary' => 'Dra. Sri Wahyuni, S.H.',
        ]);
        $guestEdit->assertRedirect(route('admin.login'));

        $guestDel = $this->delete('/admin/riwayat-transaksi/' . rawurlencode($tx1->id));
        $guestDel->assertRedirect(route('admin.login'));

        // 3. Non-super_admin (curator) can edit transaction, and property status syncs to RENTED
        $curatorEdit = $this->actingAs($curator)->post('/admin/riwayat-transaksi/' . rawurlencode($tx1->id) . '/update', [
            'trx_type' => 'TERSEWA - 2 TAHUN',
            'deal_price' => 'Rp 39.000.000.000',
            'parties' => 'Pemilik: Bpk. Dani / Penyewa: Mr. John',
            'notary' => 'Dra. Sri Wahyuni, S.H., M.Kn.',
        ]);
        $curatorEdit->assertRedirect(route('admin.transactions'));

        $tx1->refresh();
        $this->assertEquals('TERSEWA - 2 TAHUN', $tx1->trx_type);
        $this->assertEquals('Rp 39.000.000.000', $tx1->deal_price);

        $prop->refresh();
        $this->assertEquals('RENTED', $prop->status); // Synced to RENTED!

        // 4. Non-super_admin (curator) is FORBIDDEN (403) from deleting transaction
        $curatorDel = $this->actingAs($curator)->deleteJson('/admin/riwayat-transaksi/' . rawurlencode($tx1->id));
        $curatorDel->assertStatus(403);
        $curatorDel->assertJson([
            'success' => false,
            'message' => 'Akses ditolak: Hanya Super Admin yang berwenang menghapus riwayat transaksi closing.',
        ]);

        // 5. Create a second transaction for the same property
        $tx2Id = '#CLS-TX2-' . rand(100, 999);
        $tx2 = Transaction::create([
            'id' => $tx2Id,
            'date' => now()->format('d M Y'),
            'property_id' => $prop->id,
            'property_title' => $prop->title,
            'location' => 'Bali - Seminyak',
            'trx_type' => 'TERSEWA - 1 TAHUN',
            'asking_price' => 'Rp 2.000.000.000',
            'deal_price' => 'Rp 2.000.000.000',
            'deal_val' => 2000000000,
            'diff_pct' => '0%',
            'parties' => 'Pemilik: Bpk. Dani / Penyewa: PT Global',
            'notary' => 'Dra. Sri Wahyuni, S.H.',
        ]);

        // 6. Super Admin deletes transaction 2 -> Since transaction 1 still exists, property status remains RENTED
        $superDelTx2 = $this->actingAs($superAdmin)->delete('/admin/riwayat-transaksi/' . rawurlencode($tx2->id));
        $superDelTx2->assertRedirect(route('admin.transactions'));

        $this->assertDatabaseMissing('transactions', ['id' => $tx2->id]);
        $prop->refresh();
        $this->assertEquals('RENTED', $prop->status); // Status must NOT be restored to ACTIVE yet!

        // 7. Super Admin deletes transaction 1 -> No other transaction exists, property status restored to ACTIVE!
        $superDelTx1 = $this->actingAs($superAdmin)->delete('/admin/riwayat-transaksi/' . rawurlencode($tx1->id));
        $superDelTx1->assertRedirect(route('admin.transactions'));

        $this->assertDatabaseMissing('transactions', ['id' => $tx1->id]);
        $prop->refresh();
        $this->assertEquals('ACTIVE', $prop->status); // Now restored to ACTIVE!

        // 8. Verify audit log contains transaction summary (ID, property, type, price, parties, notary)
        $txAudit = AuditLog::where('action', 'like', "%Hapus Transaksi {$tx1->id}%")->first();
        $this->assertNotNull($txAudit);
        $this->assertEquals($tx1->id, $txAudit->details['transaction_id'] ?? null);
        $this->assertEquals($prop->id, $txAudit->details['property_id'] ?? null);
        $this->assertEquals('TERSEWA - 2 TAHUN', $txAudit->details['trx_type'] ?? null);
        $this->assertEquals('Rp 39.000.000.000', $txAudit->details['deal_price'] ?? null);
        $this->assertNotNull($txAudit->details['parties'] ?? null);
        $this->assertNotNull($txAudit->details['notary'] ?? null);
    }

    /**
     * Test Admin Notification Bell Badge: 0 (hidden), Normal (display exact number), and >99 (displays 99+)
     */
    public function test_admin_notification_bell_badge_display_and_tab_title(): void
    {
        $admin = $this->getAdminUser();

        // 1. Case: Unread is 0
        AdminNotification::query()->delete();
        Submission::query()->delete();
        Lead::query()->delete();
        Survey::query()->delete();

        $page0 = $this->actingAs($admin)->get('/admin/dashboard');
        $page0->assertStatus(200);
        // Badge is hidden
        $page0->assertSee('id="bell-badge-count" style="display: none;"', false);
        $page0->assertSee('aria-label="Notifikasi Operasional, tidak ada notifikasi baru"', false);
        $page0->assertSee('id="notif-unread-badge" class="dropdown-item-badge" style="background: #E0F2FE; color: #0284C7;">0 Baru</span>', false);

        // 2. Case: 3 unread notifications, while queue has 2 pending submissions and 1 new inquiry
        Submission::create([
            'id' => '#TP-BELL-1',
            'title' => 'Pending Villa 1',
            'owner_name' => 'Owner 1',
            'owner_phone' => '0812345678',
            'owner_email' => 'owner1@example.com',
            'status' => 'pending',
        ]);
        Submission::create([
            'id' => '#TP-BELL-2',
            'title' => 'Pending Villa 2',
            'owner_name' => 'Owner 2',
            'owner_phone' => '0812345679',
            'owner_email' => 'owner2@example.com',
            'status' => 'pending',
        ]);
        Lead::create([
            'id' => '#LD-BELL-1',
            'client_name' => 'Client 1',
            'client_phone' => '0812345680',
            'client_email' => 'client1@example.com',
            'stage' => 'Baru',
        ]);

        AdminNotification::create([
            'type' => 'listing',
            'title' => 'Pengajuan Listing Baru',
            'message' => 'Pending Villa 1 (#TP-BELL-1) menunggu kurasi & verifikasi berkas.',
            'target_url' => '/admin/listing-masuk?id=TP-BELL-1',
            'reference_id' => 'TP-BELL-1',
            'read_at' => null,
        ]);
        AdminNotification::create([
            'type' => 'listing',
            'title' => 'Pengajuan Listing Baru',
            'message' => 'Pending Villa 2 (#TP-BELL-2) menunggu kurasi & verifikasi berkas.',
            'target_url' => '/admin/listing-masuk?id=TP-BELL-2',
            'reference_id' => 'TP-BELL-2',
            'read_at' => null,
        ]);
        AdminNotification::create([
            'type' => 'inquiry',
            'title' => 'Inquiry Baru',
            'message' => 'Client 1 menanyakan Pending Villa 1.',
            'target_url' => '/admin/inquiry',
            'reference_id' => 'LD-BELL-1',
            'read_at' => null,
        ]);

        $pageNorm = $this->actingAs($admin)->get('/admin/dashboard');
        $pageNorm->assertStatus(200);
        $pageNorm->assertSee('id="bell-badge-count" style="display: inline-flex;"', false);
        $pageNorm->assertSee('>3</span>', false);
        $pageNorm->assertSee('aria-label="Notifikasi Operasional, 3 notifikasi baru"', false);
        $pageNorm->assertSee('id="notif-unread-badge" class="dropdown-item-badge" style="background: #E0F2FE; color: #0284C7;">3 Baru</span>', false);

        // Sidebar queue badges must continue reflecting real database queue (2 submissions, 1 inquiry)
        $pageNorm->assertSee('id="sidebar-submissions-badge">2</span>', false);
        $pageNorm->assertSee('id="sidebar-inquiries-badge">1</span>', false);

        // 3. Case: Total unread > 99 (e.g. 100)
        for ($i = 4; $i <= 100; $i++) {
            AdminNotification::create([
                'type' => 'listing',
                'title' => 'Pengajuan Listing Baru',
                'message' => "Unit {$i} menunggu kurasi.",
                'target_url' => '/admin/listing-masuk?id=' . $i,
                'reference_id' => (string)$i,
                'read_at' => null,
            ]);
        }

        $pageOver99 = $this->actingAs($admin)->get('/admin/dashboard');
        $pageOver99->assertStatus(200);
        $pageOver99->assertSee('id="bell-badge-count" style="display: inline-flex;"', false);
        $pageOver99->assertSee('>99+</span>', false);
        $pageOver99->assertSee('aria-label="Notifikasi Operasional, 100 notifikasi baru"', false);
        $pageOver99->assertSee('id="notif-unread-badge" class="dropdown-item-badge" style="background: #E0F2FE; color: #0284C7;">100 Baru</span>', false);

        // Check JSON count endpoint as well
        $apiRes = $this->actingAs($admin)->getJson('/admin/notifications/count');
        $apiRes->assertStatus(200);
        $apiRes->assertJson([
            'unread_count' => 100,
            'total' => 3,
            'pending_submissions' => 2,
            'new_inquiries' => 1,
        ]);
        $this->assertCount(15, $apiRes->json('notifications'));
    }

    /**
     * Test Event-Based Notification Creation for Web and API Routes (Single Point Creation)
     */
    public function test_event_based_admin_notifications_creation_for_web_and_api(): void
    {
        $admin = $this->getAdminUser();
        AdminNotification::query()->delete();

        // 1. Web submission (/titip-properti) -> creates exactly 1 notification
        $webSubCountBefore = AdminNotification::count();
        $subPayload = [
            'owner_name' => 'Bpk. Surya Wijaya',
            'owner_phone' => '081299887766',
            'owner_email' => 'surya@example.com',
            'ownership_status' => 'owner_direct',
            'property_category' => 'Rumah Mewah',
            'transaction_type' => 'Jual',
            'property_title' => 'Mansion Pondok Indah Asri',
            'city_region' => 'Jakarta Selatan',
            'specific_area' => 'Pondok Indah',
            'full_address' => 'Jl. Metro Pondok Indah No. 10',
            'asking_price' => '25000000000',
            'land_size' => 500,
            'building_size' => 400,
            'bedrooms' => '5 KT',
            'bathrooms' => '5 KM',
            'facilities' => ['pool', 'shm'],
            'description' => 'Mansion mewah dengan fasilitas lengkap.',
            'legal_consent' => '1',
        ];
        $webSubRes = $this->postJson('/titip-properti', $subPayload);
        $webSubRes->assertStatus(200);
        $this->assertEquals($webSubCountBefore + 1, AdminNotification::count());

        $webNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('listing', $webNotif->type);
        $this->assertEquals('Pengajuan Listing Baru', $webNotif->title);
        $this->assertStringContainsString('Mansion Pondok Indah Asri', $webNotif->message);
        $this->assertStringContainsString('/admin/listing-masuk?id=', $webNotif->target_url);
        $this->assertStringNotContainsString('#', parse_url($webNotif->target_url, PHP_URL_QUERY));
        $this->assertNull($webNotif->read_at);

        // 2. API consignment submission (/api/v1/titip-properti) -> creates exactly 1 notification
        $apiSubCountBefore = AdminNotification::count();
        $apiSubRes = $this->postJson('/api/v1/titip-properti', [
            'owner_name' => 'Ibu Maria Ulfah',
            'owner_phone' => '081344556677',
            'property_title' => 'Penthouse Senopati Suites',
            'property_category' => 'Apartemen Mewah',
            'city_region' => 'Jakarta Selatan',
            'asking_price' => '18000000000',
        ]);
        $apiSubRes->assertStatus(201);
        $this->assertEquals($apiSubCountBefore + 1, AdminNotification::count());

        $apiNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('listing', $apiNotif->type);
        $this->assertStringContainsString('Penthouse Senopati Suites', $apiNotif->message);

        // 3. Web inquiry (/inquiry from detail page) -> creates exactly 1 notification
        $webInqCountBefore = AdminNotification::count();
        $inqPayload = [
            'name' => 'Dr. Robert Tan',
            'phone' => '081122334455',
            'email' => 'robert@investor.com',
            'property_id' => '#KP-9911',
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
            'property_price' => 'Rp 42 Miliar',
            'payment_scheme' => 'Cash Keras',
            'message' => 'Mohon info legalitas dan jadwal survei.',
        ];
        $webInqRes = $this->postJson('/inquiry', $inqPayload);
        $webInqRes->assertStatus(200);
        $this->assertEquals($webInqCountBefore + 1, AdminNotification::count());

        $webInqNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('inquiry', $webInqNotif->type);
        $this->assertEquals('Inquiry Baru', $webInqNotif->title);
        $this->assertStringContainsString('Dr. Robert Tan', $webInqNotif->message);
        $this->assertStringContainsString('Villa Cliffside Horizon Uluwatu', $webInqNotif->message);
        $this->assertEquals('/admin/inquiry', $webInqNotif->target_url);

        // 4. API inquiry (/api/v1/inquiry) -> creates exactly 1 notification
        $apiInqCountBefore = AdminNotification::count();
        $apiInqRes = $this->postJson('/api/v1/inquiry', [
            'name' => 'Bpk. Ahmad Fauzi',
            'phone' => '081277665544',
            'property_title' => 'Modern Minimalist BSD City',
        ]);
        $apiInqRes->assertStatus(201);
        $this->assertEquals($apiInqCountBefore + 1, AdminNotification::count());

        // 5. Web contact form inquiry (/inquiry from tentang-kami with is_consultation = 1)
        $aboutInqCountBefore = AdminNotification::count();
        $aboutInqRes = $this->postJson('/inquiry', [
            'name' => 'Ibu Felicia Lim',
            'phone' => '081999888777',
            'email' => 'felicia@consulting.id',
            'subject' => 'Konsultasi Portfolio Investasi',
            'message' => 'Ingin konsultasi unit komersial prime.',
            'is_consultation' => 1,
        ]);
        $aboutInqRes->assertStatus(200);
        $this->assertEquals($aboutInqCountBefore + 1, AdminNotification::count());

        $aboutNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('inquiry', $aboutNotif->type);
        $this->assertStringContainsString('Ibu Felicia Lim', $aboutNotif->message);
        $this->assertStringContainsString('Konsultasi Umum (Tentang Kami)', $aboutNotif->message);

        // 6. Schedule survey -> creates exactly 1 survey notification
        $lead = Lead::first();
        $encodedLeadId = rawurlencode($lead->id);
        $survCountBefore = AdminNotification::count();
        $survRes = $this->actingAs($admin)->postJson("/admin/inquiry/{$encodedLeadId}/survey", [
            'survey_date' => '2026-11-20',
            'survey_time' => '14:30 WIB',
            'client_name' => 'Klien VIP Pratama',
            'property_title' => 'Villa Cliffside Horizon Uluwatu',
        ]);
        $survRes->assertStatus(200);
        $this->assertEquals($survCountBefore + 1, AdminNotification::count());

        $survNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('survey', $survNotif->type);
        $this->assertEquals('Jadwal Survei Baru', $survNotif->title);
        $this->assertStringContainsString('2026-11-20', $survNotif->message);
        $this->assertStringContainsString('14:30 WIB', $survNotif->message);
        $this->assertStringContainsString('Klien VIP Pratama', $survNotif->message);
        $this->assertEquals('/admin/inquiry', $survNotif->target_url);

        // 7. Rescheduling / Stage advancement of lead does NOT create another notification
        $stageCountBefore = AdminNotification::count();
        $stageRes = $this->actingAs($admin)->postJson("/admin/inquiry/{$encodedLeadId}/stage", [
            'stage' => 'Follow-Up',
        ]);
        $stageRes->assertStatus(200);
        $this->assertEquals($stageCountBefore, AdminNotification::count());

        // 8. Direct survey submission from modal (/admin/inquiry/survey) without route parameter
        $directSurvCountBefore = AdminNotification::count();
        $directSurvRes = $this->actingAs($admin)->post('/admin/inquiry/survey', [
            'client' => 'Bpk. Handoko VIP',
            'property' => 'Penthouse Sudirman Suites',
            'time' => '15:00 WIB',
            'agent' => 'Tim Concierge Kumala',
            'lead_id' => '',
        ]);
        $directSurvRes->assertRedirect(route('admin.inquiries'));
        $this->assertEquals($directSurvCountBefore + 1, AdminNotification::count());

        $directSurvNotif = AdminNotification::latest('id')->first();
        $this->assertEquals('survey', $directSurvNotif->type);
        $this->assertStringContainsString('Bpk. Handoko VIP', $directSurvNotif->message);
        $this->assertStringContainsString('Penthouse Sudirman Suites', $directSurvNotif->message);
    }

    /**
     * Test Mark Notification as Read and Mark All Read Endpoints
     */
    public function test_mark_notification_as_read_and_mark_all_read_endpoints(): void
    {
        $admin = $this->getAdminUser();
        AdminNotification::query()->delete();

        $n1 = AdminNotification::create([
            'type' => 'listing',
            'title' => 'Pengajuan 1',
            'message' => 'Pesan 1',
            'target_url' => '/admin/listing-masuk?id=1',
            'reference_id' => '1',
            'read_at' => null,
        ]);
        $n2 = AdminNotification::create([
            'type' => 'inquiry',
            'title' => 'Inquiry 2',
            'message' => 'Pesan 2',
            'target_url' => '/admin/inquiry',
            'reference_id' => '2',
            'read_at' => null,
        ]);

        $this->assertEquals(2, AdminNotification::whereNull('read_at')->count());

        // 1. Guest access must be rejected
        $guestReadRes = $this->postJson("/admin/notifications/{$n1->id}/read");
        $guestReadRes->assertRedirect(route('admin.login'));

        $guestAllRes = $this->postJson('/admin/notifications/mark-all-read');
        $guestAllRes->assertRedirect(route('admin.login'));

        // 2. GET requests to read/mark-all-read must be rejected (Method Not Allowed)
        $getReadRes = $this->actingAs($admin)->get("/admin/notifications/{$n1->id}/read");
        $getReadRes->assertStatus(405);

        $getAllRes = $this->actingAs($admin)->get('/admin/notifications/mark-all-read');
        $getAllRes->assertStatus(405);

        // 3. Admin marks single notification as read
        $readRes = $this->actingAs($admin)->postJson("/admin/notifications/{$n1->id}/read");
        $readRes->assertStatus(200);
        $readRes->assertJson([
            'success' => true,
            'unread_count' => 1,
        ]);

        $this->assertNotNull($n1->fresh()->read_at);
        $this->assertNull($n2->fresh()->read_at);
        $this->assertEquals(1, AdminNotification::whereNull('read_at')->count());

        // 4. Admin marks all as read
        $markAllRes = $this->actingAs($admin)->postJson('/admin/notifications/mark-all-read');
        $markAllRes->assertStatus(200);
        $markAllRes->assertJson([
            'success' => true,
            'unread_count' => 0,
        ]);

        $this->assertNotNull($n2->fresh()->read_at);
        $this->assertEquals(0, AdminNotification::whereNull('read_at')->count());
    }

    /**
     * Test Notification Security, Privacy (No PII), Target URL Validation, and HTML Escaping
     */
    public function test_admin_notifications_security_and_target_url_validation(): void
    {
        $admin = $this->getAdminUser();
        AdminNotification::query()->delete();

        // 1. Privacy test: creating submission and inquiry must NOT store phone numbers, emails, or notes in notification
        \App\Services\KumalaDataHub::createSubmission([
            'owner_name' => 'Bpk. Hendra Gunawan',
            'owner_phone' => '081299998888',
            'owner_email' => 'secret_hendra@bank.com',
            'title' => 'Penthouse Permata Hijau',
            'description' => 'Rahasia keluarga: unit mau dijual buru-buru.',
        ]);

        $notif = AdminNotification::latest('id')->first();
        $this->assertNotNull($notif);
        $this->assertStringNotContainsString('081299998888', $notif->message);
        $this->assertStringNotContainsString('secret_hendra@bank.com', $notif->message);
        $this->assertStringNotContainsString('Rahasia keluarga', $notif->message);

        // 2. Target URL validation: external or unsafe URLs must be sanitized to /admin/dashboard
        $unsafeNotif = \App\Services\AdminNotificationService::createNotification(
            'listing',
            'Judul Tes',
            'Pesan Tes',
            'https://evil.com/phishing',
            '999'
        );
        $this->assertEquals('/admin/dashboard', $unsafeNotif->target_url);

        $jsNotif = \App\Services\AdminNotificationService::createNotification(
            'listing',
            'Judul Tes 2',
            'Pesan Tes 2',
            'javascript:alert(1)',
            '998'
        );
        $this->assertEquals('/admin/dashboard', $jsNotif->target_url);

        // 3. HTML characters in input must NOT be executed as HTML (must be escaped in view)
        AdminNotification::create([
            'type' => 'inquiry',
            'title' => '<script>alert("xss-title")</script>',
            'message' => '<img src=x onerror=alert("xss-msg")>',
            'target_url' => '/admin/inquiry',
            'reference_id' => 'XSS-1',
            'read_at' => null,
        ]);

        $page = $this->actingAs($admin)->get('/admin/dashboard');
        $page->assertStatus(200);
        // Script and onerror tags must be HTML entity escaped by Blade {{ }}
        $page->assertDontSee('<script>alert("xss-title")</script>', false);
        $page->assertSee('&lt;script&gt;alert(&quot;xss-title&quot;)&lt;/script&gt;', false);
        $page->assertDontSee('<img src=x onerror=alert("xss-msg")>', false);
        $page->assertSee('&lt;img src=x onerror=alert(&quot;xss-msg&quot;)&gt;', false);

        // 4. Listing Masuk page parameter handling: ?id=TP-xxx
        $sub = Submission::first();
        $cleanSubId = ltrim($sub->id, '#');
        $listingPage = $this->actingAs($admin)->get('/admin/listing-masuk?id=' . rawurlencode($cleanSubId));
        $listingPage->assertStatus(200);
        $listingPage->assertSee($sub->title, false);
    }

    /**
     * Test Graceful Fallback when admin_notifications Table or Database is Absent
     */
    public function test_admin_notifications_graceful_fallback_when_table_is_absent(): void
    {
        // Drop the table temporarily to test resilience
        \Illuminate\Support\Facades\Schema::dropIfExists('admin_notifications');

        // Service methods must return 0 or empty without throwing any exception
        $this->assertEquals(0, \App\Services\AdminNotificationService::getUnreadCount());
        $this->assertEquals([], \App\Services\AdminNotificationService::getLatest());
        $this->assertNull(\App\Services\AdminNotificationService::notifyListing(['id' => '#TP-FALLBACK', 'title' => 'Fallback']));
        $this->assertNull(\App\Services\AdminNotificationService::notifyInquiry(['id' => '#IQ-FALLBACK', 'client_name' => 'Fallback']));
        $this->assertNull(\App\Services\AdminNotificationService::notifySurvey(['id' => 1, 'property_title' => 'Fallback']));
        $this->assertFalse(\App\Services\AdminNotificationService::markAsRead(1));
        $this->assertEquals(0, \App\Services\AdminNotificationService::markAllAsRead());

        // createSubmission and createInquiry must still succeed smoothly
        $sub = \App\Services\KumalaDataHub::createSubmission([
            'owner_name' => 'Bpk. Fallback Test',
            'owner_phone' => '081211112222',
            'title' => 'Villa Fallback',
        ]);
        $this->assertNotNull($sub);
        $this->assertEquals('Villa Fallback', $sub['title']);

        $inq = \App\Services\KumalaDataHub::createInquiry([
            'name' => 'Ibu Fallback Inquiry',
            'phone' => '081233334444',
        ]);
        $this->assertNotNull($inq);
        $this->assertEquals('Ibu Fallback Inquiry', $inq['client_name']);

        // Recreate the table so subsequent tests/teardown stay clean
        $migration = require database_path('migrations/2026_11_04_000002_create_admin_notifications_table.php');
        $migration->up();
    }
}



