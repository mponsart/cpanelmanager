<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('permissions')
            ->where('key', 'use_terminal')
            ->update([
                'key'         => 'access_cpanel',
                'label'       => 'Accéder à cPanel',
                'module'      => 'cpanel',
                'description' => 'Ouvrir une session cPanel avec connexion automatique',
            ]);
    }

    public function down(): void
    {
        DB::table('permissions')
            ->where('key', 'access_cpanel')
            ->update([
                'key'         => 'use_terminal',
                'label'       => 'Accéder au terminal SSH',
                'module'      => 'terminal',
                'description' => 'Ouvrir un terminal SSH interactif sur le serveur',
            ]);
    }
};
