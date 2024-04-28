<?php

namespace Database\Seeders;

use App\Models\CampaignFacebook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignFacebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CampaignFacebook::factory(1000)->create();
    }
}
