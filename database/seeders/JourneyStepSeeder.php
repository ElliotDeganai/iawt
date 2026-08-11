<?php

namespace Database\Seeders;

use App\Models\JourneyStep;
use Illuminate\Database\Seeder;

class JourneyStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'icon'        => 'lightbulb',
                'label'       => "De l'idée au projet",
                'description' => "Comprendre d'où vient votre idée et si elle vous correspond vraiment.",
                'position'    => 1,
            ],
            [
                'icon'        => 'search',
                'label'       => 'Étude de marché',
                'description' => "Transformer votre idée grâce à une analyse terrain : problème, cibles, concurrence, SWOT.",
                'position'    => 2,
            ],
            [
                'icon'        => 'chart',
                'label'       => 'Business Model Canvas',
                'description' => "Structurer votre modèle économique bloc par bloc : valeur, clients, canaux, coûts, revenus.",
                'position'    => 3,
            ],
            [
                'icon'        => 'currency',
                'label'       => 'Structuration financière',
                'description' => "Construire votre plan de financement initial et votre compte de résultat prévisionnel sur 3 ans.",
                'position'    => 4,
            ],
            [
                'icon'        => 'scale',
                'label'       => 'Choix de la forme juridique',
                'description' => "Analyser votre profil et votre projet pour choisir la forme juridique OHADA adaptée.",
                'position'    => 5,
            ],
            [
                'icon'        => 'target',
                'label'       => 'Business Ground Lab',
                'description' => "3 semaines pour tester votre projet en conditions réelles : vendre, mesurer, apprendre, décider.",
                'position'    => 6,
            ],
            [
                'icon'        => 'building',
                'label'       => "De la décision à l'immatriculation",
                'description' => "Préparer votre dossier, choisir votre pays et parcours de formalisation, et déposer au GUFE.",
                'position'    => 7,
            ],
            [
                'icon'        => 'flag',
                'label'       => 'Mon entreprise est créée',
                'description' => "Checklist finale : RCCM, NIF, compte bancaire, première facture — vous êtes prêt(e) à démarrer.",
                'position'    => 8,
            ],
        ];

        foreach ($steps as $step) {
            JourneyStep::updateOrCreate(['position' => $step['position']], $step);
        }
    }
}