<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        // Administration
        ['key' => 'manage_users',   'label' => 'Gérer les utilisateurs',      'module' => 'admin',    'description' => 'Créer, modifier et supprimer les techniciens'],

        // E-mails
        ['key' => 'view_email',     'label' => 'Voir les e-mails',            'module' => 'email',    'description' => 'Lister les adresses e-mail du compte'],
        ['key' => 'create_email',   'label' => 'Créer une adresse e-mail',    'module' => 'email',    'description' => 'Créer, modifier et gérer les adresses e-mail'],
        ['key' => 'delete_email',   'label' => 'Supprimer une adresse e-mail','module' => 'email',    'description' => 'Supprimer définitivement une adresse e-mail'],

        // Bases de données
        ['key' => 'view_db',        'label' => 'Voir les bases de données',   'module' => 'database', 'description' => 'Lister les bases MySQL et utilisateurs'],
        ['key' => 'create_db',      'label' => 'Gérer les bases de données',  'module' => 'database', 'description' => 'Créer des bases, utilisateurs et assigner des privilèges'],

        // Domaines
        ['key' => 'view_domain',    'label' => 'Voir les domaines',           'module' => 'domain',   'description' => 'Lister les domaines et sous-domaines'],
        ['key' => 'create_domain',  'label' => 'Gérer les domaines',          'module' => 'domain',   'description' => 'Ajouter des domaines addon et sous-domaines'],

        // FTP
        ['key' => 'view_ftp',       'label' => 'Voir les comptes FTP',        'module' => 'ftp',      'description' => 'Lister les comptes FTP'],
        ['key' => 'create_ftp',     'label' => 'Gérer les comptes FTP',       'module' => 'ftp',      'description' => 'Créer et supprimer des comptes FTP'],

        // Cron
        ['key' => 'manage_cron',    'label' => 'Gérer les tâches cron',       'module' => 'cron',     'description' => 'Créer, lister et supprimer les tâches planifiées'],

        // Stats
        ['key' => 'view_stats',     'label' => 'Voir les statistiques',       'module' => 'stats',    'description' => 'Consulter les statistiques disque et bande passante'],

        // Associations MonAsso
        ['key' => 'view_associations',   'label' => 'Voir les associations',        'module' => 'association', 'description' => 'Lister les dossiers des associations'],
        ['key' => 'manage_associations', 'label' => 'Gérer les associations',       'module' => 'association', 'description' => 'Créer, renommer et supprimer des associations'],
    ];

    public function run(): void
    {
        foreach ($this->permissions as $data) {
            Permission::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }

        $this->command->info(count($this->permissions) . ' permission(s) créées / mises à jour.');
    }
}
