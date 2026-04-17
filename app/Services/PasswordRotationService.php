<?php

namespace App\Services;

use Illuminate\Support\Str;

class PasswordRotationService
{
    public function __construct(
        private CpanelService $cpanel
    ) {}

    /**
     * Change le mot de passe cPanel via l'API et met à jour le .env.
     *
     * @return string Le nouveau mot de passe généré.
     */
    public function rotate(): string
    {
        // Éviter les caractères problématiques pour .env ($, ", \, #, espace)
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@%^&*()-_=+[]{}|;:,.<>?';
        $newPassword = '';
        for ($i = 0; $i < 16; $i++) {
            $newPassword .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $oldPassword = config('cpanel.password');

        $this->cpanel->callApi2('Passwd', 'change_password', [
            'oldpass' => $oldPassword,
            'newpass' => $newPassword,
        ]);

        $this->updateEnv('CPANEL_PASSWORD', $newPassword);

        // Refresh the runtime config so subsequent code sees the new password
        config(['cpanel.password' => $newPassword]);

        return $newPassword;
    }

    private function updateEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        // Utiliser des guillemets simples pour éviter l'interpolation de $
        $escaped = str_replace("'", "\\'", $value);

        if (preg_match('/^' . preg_quote($key, '/') . '=.*/m', $content)) {
            $content = preg_replace(
                '/^' . preg_quote($key, '/') . '=.*/m',
                $key . "='" . $escaped . "'",
                $content
            );
        } else {
            $content .= "\n" . $key . "='" . $escaped . "'" . "\n";
        }

        file_put_contents($envPath, $content, LOCK_EX);
    }
}
