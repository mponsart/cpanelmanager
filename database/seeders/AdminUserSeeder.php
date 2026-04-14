<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $name  = env('ADMIN_NAME', 'Administrateur');

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name'           => $name,
                'status'         => 'active',
                'is_super_admin' => true,
            ]
        );

        $this->command->info('Super administrateur : ' . $admin->email);
        $this->command->info('Connexion via Google OAuth uniquement.');

        // Forcer d'autres super admins si défini (emails séparés par des virgules)
        $extraAdmins = env('FORCE_SUPER_ADMINS', '');
        if ($extraAdmins) {
            foreach (array_filter(array_map('trim', explode(',', $extraAdmins))) as $extraEmail) {
                if ($extraEmail === $email) {
                    continue;
                }

                User::updateOrCreate(
                    ['email' => $extraEmail],
                    [
                        'name'           => explode('@', $extraEmail)[0],
                        'status'         => 'active',
                        'is_super_admin' => true,
                    ]
                );

                $this->command->info('Super admin forcé : ' . $extraEmail);
            }
        }
    }
}
