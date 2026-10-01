<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General & Company
            ['key' => 'company_name', 'value' => 'Kumala Property', 'group' => 'general'],
            ['key' => 'company_tagline', 'value' => 'Jual & Sewa Properti Eksklusif', 'group' => 'general'],
            
            // Contacts
            ['key' => 'hotline_admin', 'value' => '0812-8888-2900', 'group' => 'contact'],
            ['key' => 'email_customer_care', 'value' => 'cs@kumalaproperty.co.id', 'group' => 'contact'],
            ['key' => 'email_business', 'value' => 'info@kumalaproperty.co.id', 'group' => 'contact'],
            ['key' => 'office_hours', 'value' => 'Senin - Sabtu: 08:30 - 18:00 WIB', 'group' => 'contact'],
            ['key' => 'hq_address', 'value' => 'Kumala Tower Lt. 5, Jl. TB Simatupang No. 18, Jakarta Selatan 12430', 'group' => 'contact'],
            ['key' => 'branch_bali_address', 'value' => 'Jl. Pantai Suluban No. 8, Pecatu, Uluwatu, Badung, Bali 80361', 'group' => 'contact'],
            
            // Social Media
            ['key' => 'instagram_handle', 'value' => '@kumala.realty', 'group' => 'contact'],
            ['key' => 'youtube_channel', 'value' => 'Kumala Property Official', 'group' => 'contact'],
            ['key' => 'facebook_page', 'value' => 'Kumala Realty Indonesia', 'group' => 'contact'],
            
            // Notifications & Automations
            ['key' => 'notify_new_submission', 'value' => '1', 'group' => 'notification'],
            ['key' => 'notify_instant_whatsapp', 'value' => '1', 'group' => 'notification'],
            ['key' => 'auto_watermark', 'value' => '1', 'group' => 'notification'],
            
            // Business Parameters
            ['key' => 'standard_commission_pct', 'value' => '2.5', 'group' => 'business'],
            ['key' => 'sla_submission_hours', 'value' => '4', 'group' => 'business'],
            ['key' => 'sla_inquiry_minutes', 'value' => '15', 'group' => 'business'],
        ];

        foreach ($settings as $st) {
            Setting::updateOrCreate(['key' => $st['key']], $st);
        }
    }
}
