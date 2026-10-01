<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surveys = [
            [
                'lead_id' => '#IQ-2209',
                'property_id' => '#KP-8821',
                'property_title' => 'Villa Cliffside Horizon Uluwatu',
                'client_name' => 'Bpk. Richard Tan',
                'agent_name' => 'Gusti Ayu',
                'survey_time' => '10:30 WIB',
                'survey_date' => Carbon::today(),
                'status' => 'Terkonfirmasi',
            ],
            [
                'lead_id' => '#IQ-2208',
                'property_id' => '#KP-8830',
                'property_title' => 'Menteng Heritage Mansion',
                'client_name' => 'Ibu Stephanie Halim',
                'agent_name' => 'Farhan Kamil',
                'survey_time' => '14:00 WIB',
                'survey_date' => Carbon::today(),
                'status' => 'Menunggu Klien',
            ],
            [
                'lead_id' => '#IQ-2207',
                'property_id' => '#KP-8832',
                'property_title' => 'Lavish Penthouse SCBD',
                'client_name' => 'Mr. Thomas Vance',
                'agent_name' => 'Aditya Pratama',
                'survey_time' => '16:30 WIB',
                'survey_date' => Carbon::today(),
                'status' => 'Terkonfirmasi',
            ],
        ];

        foreach ($surveys as $srv) {
            Survey::updateOrCreate(
                [
                    'client_name' => $srv['client_name'],
                    'property_title' => $srv['property_title'],
                    'survey_time' => $srv['survey_time'],
                ],
                $srv
            );
        }
    }
}
