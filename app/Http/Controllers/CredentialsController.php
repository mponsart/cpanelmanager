<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CredentialsController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);

        $credentials = [
            [
                'section' => 'cPanel',
                'rows' => [
                    ['label' => 'Hôte',         'value' => config('cpanel.host'),     'sensitive' => false],
                    ['label' => 'Port',          'value' => config('cpanel.port'),     'sensitive' => false],
                    ['label' => 'Utilisateur',   'value' => config('cpanel.username'), 'sensitive' => false],
                    ['label' => 'Domaine',       'value' => config('cpanel.domain'),   'sensitive' => false],
                    ['label' => 'Token API',     'value' => config('cpanel.token'),    'sensitive' => true],
                    ['label' => 'Mot de passe',  'value' => config('cpanel.password'), 'sensitive' => true],
                ],
            ],
            [
                'section' => 'Base de données',
                'rows' => [
                    ['label' => 'Hôte',         'value' => config('database.connections.mysql.host'),     'sensitive' => false],
                    ['label' => 'Port',          'value' => config('database.connections.mysql.port'),     'sensitive' => false],
                    ['label' => 'Base',          'value' => config('database.connections.mysql.database'), 'sensitive' => false],
                    ['label' => 'Utilisateur',   'value' => config('database.connections.mysql.username'), 'sensitive' => false],
                    ['label' => 'Mot de passe',  'value' => config('database.connections.mysql.password'), 'sensitive' => true],
                ],
            ],
            [
                'section' => 'Google OAuth',
                'rows' => [
                    ['label' => 'Client ID',     'value' => config('services.google.client_id'),     'sensitive' => false],
                    ['label' => 'Client Secret', 'value' => config('services.google.client_secret'), 'sensitive' => true],
                    ['label' => 'Redirect URI',  'value' => config('services.google.redirect'),      'sensitive' => false],
                ],
            ],
            [
                'section' => 'SSH',
                'rows' => [
                    ['label' => 'Hôte',          'value' => config('ssh.host'),     'sensitive' => false],
                    ['label' => 'Port',           'value' => config('ssh.port'),     'sensitive' => false],
                    ['label' => 'Utilisateur',    'value' => config('ssh.username'), 'sensitive' => false],
                    ['label' => 'Chemin clé SSH', 'value' => config('ssh.key_path'), 'sensitive' => true],
                ],
            ],
        ];

        return view('credentials.index', compact('credentials'));
    }
}
