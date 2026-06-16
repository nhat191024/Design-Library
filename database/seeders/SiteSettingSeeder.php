<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::firstOrCreate(['key' => 'bg_zone1_image'], ['value' => null]);
        SiteSetting::firstOrCreate(['key' => 'bg_zone1_blur'],  ['value' => '0']);
        SiteSetting::firstOrCreate(['key' => 'bg_zone1_opacity'], ['value' => '0.5']);
        SiteSetting::firstOrCreate(['key' => 'bg_zone2_image'], ['value' => null]);
        SiteSetting::firstOrCreate(['key' => 'bg_zone2_blur'],  ['value' => '0']);
        SiteSetting::firstOrCreate(['key' => 'bg_zone2_opacity'], ['value' => '0.5']);
    }
}
