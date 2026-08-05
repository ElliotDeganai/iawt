<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['value' => '12',   'label' => 'Projets accompagnés par promotion', 'position' => 1],
            ['value' => '7',    'label' => 'Étapes clés du parcours',           'position' => 2],
            ['value' => '100+', 'label' => 'Mentors & experts engagés',         'position' => 3],
            ['value' => '15',   'label' => 'Pays représentés depuis 2022',      'position' => 4],
        ];

        foreach ($stats as $s) {
            Stat::firstOrCreate(['label' => $s['label']], $s);
        }

        $partners = ['AFD', 'BOAD', 'ONU Environnement', 'IATA', 'Young Africa', 'NEPAD', 'Banque Populaire', 'DER'];

        foreach ($partners as $i => $name) {
            Partner::firstOrCreate(['name' => $name], ['name' => $name, 'position' => $i + 1]);
        }
    }
}
