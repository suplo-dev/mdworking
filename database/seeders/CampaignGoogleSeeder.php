<?php

namespace Database\Seeders;

use App\Models\CampaignGoogle;
use Database\Factories\CampaignGoogleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignGoogleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CampaignGoogle::factory(100)->create();
    }
}
