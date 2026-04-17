<?php

return [
    /*
     * Hôte du serveur cPanel (sans https://, sans port)
     * Ex: server.example.com
     */
    'host' => env('CPANEL_HOST', ''),

    /*
     * Port de l'interface WHM/cPanel (2083 = cPanel SSL, 2087 = WHM SSL)
     */
    'port' => env('CPANEL_PORT', 2083),

    /*
     * Nom d'utilisateur du compte cPanel principal
     */
    'username' => env('CPANEL_USERNAME', ''),

    /*
     * Token API cPanel (Personal Access Token)
     * À générer dans cPanel > Sécurité > Gestion de tokens API
     * JAMAIS exposé en frontend ou dans les logs
     */
    'token' => env('CPANEL_TOKEN', ''),

    /*
     * Mot de passe du compte cPanel (optionnel)
     * Utilisé pour la connexion manuelle proxiée par Laravel.
     * Si défini, l'utilisateur n'a pas besoin de saisir son mot de passe.
     * JAMAIS exposé en frontend ou dans les logs
     */
    'password' => env('CPANEL_PASSWORD', ''),

    /*
     * Domaine principal du compte cPanel
     * Utilisé comme valeur par défaut dans les formulaires
     */
    'domain' => env('CPANEL_DOMAIN', ''),

    /*
     * Chemin absolu vers le répertoire des associations MonAsso
     */
    'monasso_path' => env('MONASSO_PATH', ''),
];
