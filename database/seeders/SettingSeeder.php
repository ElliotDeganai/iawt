<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'InAfrikaWeTrust',
            'tagline' => "Entreprendre dans l'Afrique de demain",
            'contact_email' => 'contact@inafrikawetrust.org',
            'description' => "Un programme d'accompagnement des jeunes entrepreneurs africains dans la création et le développement de leurs projets dans le secteur du tourisme et de l'agriculture.",
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
