<?php

namespace Database\Seeders;

use App\Models\AdsGoogle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsGoogleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdsGoogle::factory(1000)->create();
    }
}
