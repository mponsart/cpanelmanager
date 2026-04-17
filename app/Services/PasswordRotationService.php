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
        $newPassword = Str::password(32);
        $oldPassword = config('cpanel.password');

        $this->cpanel->callApi2('Passwd', 'changepasswd', [
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
