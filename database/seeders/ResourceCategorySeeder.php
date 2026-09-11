<?php
namespace Database\Seeders;
use App\Models\ResourceCategory;
use Illuminate\Database\Seeder;

class ResourceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['name' => 'Financement', 'slug' => 'financement', 'description' => 'Subventions, appels à projets, prêts d\'honneur, microfinance, guides de dossier de financement.', 'icon' => 'banknotes', 'sort_order' => 1],
            ['name' => 'Réglementation des activités', 'slug' => 'reglementation', 'description' => 'Textes réglementaires par secteur, normes sanitaires et environnementales, autorisations sectorielles.', 'icon' => 'scale', 'sort_order' => 2],
            ['name' => 'Forme juridique', 'slug' => 'forme-juridique', 'description' => 'Glossaire OHADA, comparatif des formes, fiches par pays.', 'icon' => 'building', 'sort_order' => 3],
            ['name' => 'Fiscalité et cotisations sociales', 'slug' => 'fiscalite', 'description' => 'Régimes d\'imposition, calendrier fiscal, obligations sociales, CNSS.', 'icon' => 'calculator', 'sort_order' => 4],
            ['name' => 'Étude de marché', 'slug' => 'etude-marche', 'description' => 'Méthodologie, données sectorielles agriculture et tourisme, analyses économiques.', 'icon' => 'chart', 'sort_order' => 5],
            ['name' => 'Mes outils du parcours', 'slug' => 'outils-parcours', 'description' => 'Canvas, tableaux financiers, convention de test terrain et tous les modèles du parcours.', 'icon' => 'wrench', 'sort_order' => 6],
            ['name' => 'Annuaire des partenaires', 'slug' => 'annuaire', 'description' => 'GUFE, CCIMA, CNSS, chambres de métiers, associations locales, contacts utiles par pays.', 'icon' => 'book', 'sort_order' => 7],
            ['name' => 'Témoignages et parcours inspirants', 'slug' => 'temoignages', 'description' => 'Récits d\'anciennes porteuses de projet accompagnées par l\'association.', 'icon' => 'heart', 'sort_order' => 8],
        ];
        foreach ($cats as $c) { ResourceCategory::updateOrCreate(['slug' => $c['slug']], $c); }
    }
}
