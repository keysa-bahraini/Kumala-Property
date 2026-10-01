<?php

namespace Tests\Feature;

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
     * Test Page 5: Properti Favorit Saya
     */
    public function test_favorites_page_returns_successful_response(): void
    {
        $response = $this->get('/favorit');
        $response->assertStatus(200);
        $response->assertSee('Properti Favorit Saya');
        $response->assertSee('Bandingkan Spek');
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
}


