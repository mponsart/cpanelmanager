<?php

namespace App\Services;

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
        $oldPassword = config('cpanel.password');
        $newPassword = $this->generateCpanelLikePassword();

        if (empty($oldPassword)) {
            throw new \Exception('Ancien mot de passe cPanel non configuré. Vérifiez CPANEL_PASSWORD dans .env.');
        }

        // Utilise UAPI (moderne) au lieu d'API2 (legacy) pour le changement de mot de passe
        // Documentation: https://api.docs.cpanel.net/
        // Module: Passwd, Fonction: change_password
        // Paramètres: old_password, new_password
        try {
            $this->cpanel->call('Passwd', 'change_password', [
                'old_password' => $oldPassword,
                'new_password' => $newPassword,
            ]);
        } catch (\Throwable $e) {
            // En cas d'erreur UAPI, essayer API2 (fallback)
            \Log::warning('UAPI Passwd::change_password échoué, essai API2', [
                'error' => $e->getMessage()
            ]);
            
            $this->cpanel->callApi2('Passwd', 'change_password', [
                'oldpass' => $oldPassword,
                'newpass' => $newPassword,
            ]);
        }

        // Si l'appel API a réussi, met à jour le fichier .env et la config
        $this->updateEnv('CPANEL_PASSWORD', $newPassword);

        // Refresh the runtime config so subsequent code sees the new password
        config(['cpanel.password' => $newPassword]);

        return $newPassword;
    }

    /**
     * Génère un mot de passe robuste au format "cPanel-like".
     *
     * - longueur 20
     * - au moins 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial sûr
     * - caractères volontairement limités pour éviter les soucis .env/phpMyAdmin/cPanel
     */
    private function generateCpanelLikePassword(int $length = 20): string
    {
        $lower = 'abcdefghjkmnpqrstuvwxyz';
        $upper = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $digits = '23456789';
        $special = '._-+='; // Caractères spéciaux sûrs pour .env, URLs et MySQL
        $all = $lower . $upper . $digits . $special;

        $password = [
            $lower[random_int(0, strlen($lower) - 1)],
            $upper[random_int(0, strlen($upper) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        for ($i = count($password); $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Mélange cryptographiquement sûr (Fisher-Yates)
        for ($i = count($password) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$password[$i], $password[$j]] = [$password[$j], $password[$i]];
        }

        return implode('', $password);
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
