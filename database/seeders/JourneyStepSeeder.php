<?php

namespace Database\Seeders;

use App\Models\JourneyStep;
use Illuminate\Database\Seeder;

class JourneyStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['icon' => 'lightbulb', 'label' => "De l'idée au projet", 'position' => 1],
            ['icon' => 'search', 'label' => 'Étude de marché', 'position' => 2],
            ['icon' => 'chart', 'label' => 'Business model', 'position' => 3],
            ['icon' => 'currency', 'label' => 'Structuration financière', 'position' => 4],
            ['icon' => 'scale', 'label' => 'Choix de la forme juridique', 'position' => 5],
            ['icon' => 'target', 'label' => 'Phase test (terrain)', 'position' => 6],
            ['icon' => 'building', 'label' => "Formalités de création d'entreprise", 'position' => 7],
        ];

        foreach ($steps as $step) {
            JourneyStep::updateOrCreate(['position' => $step['position']], $step);
        }
    }
}
