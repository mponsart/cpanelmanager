<?php

namespace App\Services;

class MysqlUserService
{
    public function __construct(
        private CpanelService $cpanel
    ) {}

    /**
     * Crée ou met à jour l'utilisateur MySQL dédié pour phpMyAdmin.
     * Utilisateur : gowo3083_phpmyadmin
     * Accorde tous les droits sur toutes les bases de données du compte.
     *
     * @return array Contenant 'username', 'password', 'phpmyadmin_url'
     */
    public function setupPhpMyAdminUser(): array
    {
        $username = 'gowo3083_phpmyadmin';
        $password = $this->generateSecurePassword();
        $mainAccount = config('cpanel.username'); // 'gowo3083'

        try {
            // Étape 1 : Créer l'utilisateur MySQL via cPanel API
            $this->cpanel->call('Mysql', 'create_user', [
                'name'     => $username,
                'password' => $password,
            ]);

            // Étape 2 : Accorder tous les droits sur toutes les bases
            $this->cpanel->call('Mysql', 'set_privileges_on_all_databases', [
                'user'       => $username,
                'privileges' => 'ALL', // Tous les droits
            ]);

            // Étape 3 : Stocker le mot de passe dans le .env
            $this->updateEnv('MYSQL_PHPMYADMIN_USER', $username);
            $this->updateEnv('MYSQL_PHPMYADMIN_PASSWORD', $password);

            // Étape 4 : Générer l'URL de connexion automatique
            $phpmyadminUrl = $this->generatePhpMyAdminUrl($username, $password);

            return [
                'username'       => $username,
                'password'       => $password,
                'phpmyadmin_url' => $phpmyadminUrl,
                'message'        => "Utilisateur MySQL {$username} créé avec succès.",
            ];
        } catch (\Throwable $e) {
            // En cas d'erreur, essayer de simplement mettre à jour le mot de passe
            \Log::warning('Création utilisateur MySQL échouée, tentative de changement de mot de passe', [
                'error' => $e->getMessage()
            ]);

            try {
                $this->cpanel->call('Mysql', 'change_password', [
                    'user'     => $username,
                    'password' => $password,
                ]);

                $this->updateEnv('MYSQL_PHPMYADMIN_USER', $username);
                $this->updateEnv('MYSQL_PHPMYADMIN_PASSWORD', $password);

                $phpmyadminUrl = $this->generatePhpMyAdminUrl($username, $password);

                return [
                    'username'       => $username,
                    'password'       => $password,
                    'phpmyadmin_url' => $phpmyadminUrl,
                    'message'        => "Mot de passe de l'utilisateur {$username} mis à jour.",
                ];
            } catch (\Throwable $e2) {
                \Log::error('Gestion utilisateur MySQL échouée', [
                    'error' => $e2->getMessage()
                ]);
                throw new \Exception('Impossible de gérer l\'utilisateur MySQL : ' . $e2->getMessage());
            }
        }
    }

    /**
     * Génère une URL de connexion automatique à phpMyAdmin local.
     * Encode les identifiants pour le formulaire de connexion.
     */
    private function generatePhpMyAdminUrl(string $username, string $password): string
    {
        // URL vers l'installation locale de phpMyAdmin
        // Les identifiants sont passés en tant que paramètres de formulaire
        $url = sprintf(
            '/phpmyadmin/index.php?pma_username=%s&pma_password=%s',
            urlencode($username),
            urlencode($password)
        );
        
        return $url;
    }

    /**
     * Génère un mot de passe sécurisé (même format que celui de cPanel).
     */
    private function generateSecurePassword(int $length = 20): string
    {
        $lower = 'abcdefghjkmnpqrstuvwxyz';
        $upper = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $digits = '23456789';
        $special = '._-+=';
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

        // Fisher-Yates shuffle
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

    /**
     * Retourne l'URL de connexion actuellement configurée.
     */
    public function getPhpMyAdminUrl(): ?string
    {
        $username = config('mysql.phpmyadmin_user');
        $password = config('mysql.phpmyadmin_password');

        if (!$username || !$password) {
            return null;
        }

        return $this->generatePhpMyAdminUrl($username, $password);
    }
}
