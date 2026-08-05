<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            PlaceCategorySeeder::class,
            FeaturedCountrySeeder::class,
            EngagementSeeder::class,
            JourneyStepSeeder::class,
            EventSeeder::class,
            StatPartnerSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
