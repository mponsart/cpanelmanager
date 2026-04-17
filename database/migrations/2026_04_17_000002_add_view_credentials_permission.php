<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('permissions')->updateOrInsert(
            ['key' => 'view_credentials'],
            [
                'key'         => 'view_credentials',
                'label'       => 'Voir les identifiants',
                'module'      => 'admin',
                'description' => 'Consulter les identifiants confidentiels (cPanel, BDD, SSH, OAuth)',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('permissions')->where('key', 'view_credentials')->delete();
    }
};
