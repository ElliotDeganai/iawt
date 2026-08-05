<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Candidatures
            ['name' => 'Voir les candidatures', 'slug' => 'applications.view'],
            ['name' => 'Gérer les candidatures', 'slug' => 'applications.manage'],
            ['name' => 'Gérer les événements', 'slug' => 'events.manage'],
            ['name' => 'Gérer les candidatures', 'slug' => 'applications.manage'],
            // Contenu (pages, pays à l'honneur, agenda, galerie)
            ['name' => 'Gérer les pages', 'slug' => 'pages.manage'],
            ['name' => 'Gérer le contenu de la page d\'accueil', 'slug' => 'home_content.manage'],
            ['name' => 'Gérer les pays à l\'honneur', 'slug' => 'countries.manage'],
            ['name' => 'Gérer l\'agenda', 'slug' => 'agenda.manage'],
            ['name' => 'Gérer la galerie photo', 'slug' => 'gallery.manage'],
            // Parcours entrepreneur / ressources
            ['name' => 'Gérer les webinaires', 'slug' => 'webinars.manage'],
            ['name' => 'Gérer les ressources documentaires', 'slug' => 'resources.manage'],
            ['name' => 'Gérer l\'annuaire des prestataires', 'slug' => 'directory-providers.manage'],
            ['name' => 'Gérer l\'annuaire des partenaires', 'slug' => 'directory-partners.manage'],
            ['name' => 'Modérer le forum', 'slug' => 'forum.moderate'],
            // Utilisateurs & réglages
            ['name' => 'Gérer les utilisateurs', 'slug' => 'users.manage'],
            ['name' => 'Gérer les rôles et permissions', 'slug' => 'roles.manage'],
            ['name' => 'Gérer les réglages du site', 'slug' => 'settings.manage'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        $admin = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrateur']
        );
        $admin->permissions()->sync(Permission::pluck('id'));

        $moderator = Role::updateOrCreate(
            ['slug' => 'moderator'],
            ['name' => 'Modérateur']
        );
        $moderator->permissions()->sync(
            Permission::whereIn('slug', [
                'applications.view',
                'forum.moderate',
                'agenda.manage',
                'gallery.manage',
            ])->pluck('id')
        );

        // Les entrepreneurs/candidats n'ont pas de rôle back-office (role_id = null).
    }
}
