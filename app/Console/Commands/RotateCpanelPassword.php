<?php

namespace App\Console\Commands;

use App\Services\CpanelService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RotateCpanelPassword extends Command
{
    protected $signature = 'cpanel:rotate-password';

    protected $description = 'Génère un nouveau mot de passe cPanel et met à jour le fichier .env';

    public function handle(CpanelService $cpanel): int
    {
        $newPassword = Str::password(32);

        try {
            $cpanel->call('Passwd', 'set_password', [
                'password' => $newPassword,
            ]);
        } catch (\Throwable $e) {
            $this->error('Échec du changement de mot de passe cPanel : ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->updateEnv('CPANEL_PASSWORD', $newPassword);

        $this->info('Mot de passe cPanel changé avec succès.');

        return self::SUCCESS;
    }

    private function updateEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        $escaped = addcslashes($value, '"\\');

        if (preg_match('/^' . preg_quote($key, '/') . '=.*/m', $content)) {
            $content = preg_replace(
                '/^' . preg_quote($key, '/') . '=.*/m',
                $key . '="' . $escaped . '"',
                $content
            );
        } else {
            $content .= "\n" . $key . '="' . $escaped . '"' . "\n";
        }

        file_put_contents($envPath, $content, LOCK_EX);
    }
}
