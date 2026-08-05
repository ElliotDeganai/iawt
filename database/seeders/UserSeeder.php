<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@inafrikawetrust.org'],
            [
                'first_name' => 'Admin',
                'last_name' => 'InAfrikaWeTrust',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => $adminRole?->id,
            ]
        );

        // Quelques comptes "candidat" de démonstration (sans rôle back-office)
        User::factory()->count(5)->create();
    }
}
