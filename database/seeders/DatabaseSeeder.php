<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            RegionSeeder::class,
            PropertySeeder::class,
            SubmissionSeeder::class,
            LeadSeeder::class,
            SurveySeeder::class,
            TransactionSeeder::class,
            SettingSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
