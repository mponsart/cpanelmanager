<?php

namespace App\Console\Commands;

use App\Models\ActionLog;
use App\Services\PasswordRotationService;
use Illuminate\Console\Command;

class RotateCpanelPassword extends Command
{
    protected $signature = 'cpanel:rotate-password';

    protected $description = 'Génère un nouveau mot de passe cPanel et met à jour le fichier .env';

    public function handle(PasswordRotationService $rotator): int
    {
        try {
            $rotator->rotate();

            ActionLog::create([
                'user_id'    => null,
                'action'     => 'cpanel_scheduled_rotate_password',
                'module'     => 'cpanel',
                'status'     => 'success',
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            ActionLog::create([
                'user_id'       => null,
                'action'        => 'cpanel_scheduled_rotate_password',
                'module'        => 'cpanel',
                'status'        => 'error',
                'error_message' => $e->getMessage(),
                'created_at'    => now(),
            ]);

            $this->error('Échec du changement de mot de passe cPanel : ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info('Mot de passe cPanel changé avec succès.');

        return self::SUCCESS;
    }
}
