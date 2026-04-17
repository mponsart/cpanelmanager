<?php

return [
    /*
     * Hôte SSH (par défaut : même serveur que cPanel)
     */
    'host' => env('SSH_HOST', env('CPANEL_HOST', '')),

    /*
     * Port SSH (par défaut : 22)
     */
    'port' => (int) env('SSH_PORT', 22),

    /*
     * Utilisateur SSH (par défaut : même que CPANEL_USERNAME)
     */
    'username' => env('SSH_USERNAME', env('CPANEL_USERNAME', '')),

    /*
     * Chemin absolu vers la clé privée SSH
     * Ex : /home/gowo3083/.ssh/id_rsa
     */
    'key_path' => env('SSH_KEY_PATH', ''),
];
