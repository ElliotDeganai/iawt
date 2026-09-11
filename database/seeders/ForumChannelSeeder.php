<?php
namespace Database\Seeders;
use App\Models\ForumChannel;
use Illuminate\Database\Seeder;

class ForumChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            ['name' => 'Le Grand Cercle', 'slug' => 'le-grand-cercle', 'description' => 'Présentation, entraide, échanges sectoriels, annonces de réussites et victoires.', 'icon' => 'circle', 'sort_order' => 1],
            ['name' => 'Bons plans et opportunités', 'slug' => 'bons-plans', 'description' => 'Subventions, formations, offres de collaboration, appels à projets.', 'icon' => 'star', 'sort_order' => 2],
            ['name' => 'Actualités InAfrikaWeTrust', 'slug' => 'actualites-iawt', 'description' => 'Annonces officielles de l\'association.', 'icon' => 'megaphone', 'sort_order' => 3],
            ['name' => 'Tourisme & Agriculture', 'slug' => 'tourisme-agriculture', 'description' => 'Salons, événements, nouvelles lois, actualités sectorielles.', 'icon' => 'globe', 'sort_order' => 4],
        ];
        foreach ($channels as $c) { ForumChannel::updateOrCreate(['slug' => $c['slug']], $c); }
    }
}
