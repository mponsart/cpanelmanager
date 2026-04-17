<?php

namespace App\Console\Commands;

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
        } catch (\Throwable $e) {
            $this->error('Échec du changement de mot de passe cPanel : ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info('Mot de passe cPanel changé avec succès.');

        return self::SUCCESS;
    }
}
