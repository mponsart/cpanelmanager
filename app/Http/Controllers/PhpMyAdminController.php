<?php

namespace App\Http\Controllers;

use App\Services\LoggerService;
use App\Services\MysqlUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PhpMyAdminController extends Controller
{
    public function __construct(
        private MysqlUserService $mysqlUserService,
        private LoggerService $logger
    ) {}

    public function index()
    {
        $user     = config('mysql.phpmyadmin_user');
        $hasSetup = !empty(config('mysql.phpmyadmin_password'));

        return view('database.phpmyadmin', compact('user', 'hasSetup'));
    }

    /**
     * Configure l'utilisateur MySQL dédié phpMyAdmin et génère un mot de passe.
     */
    public function setup(Request $request): RedirectResponse
    {
        try {
            $result = $this->mysqlUserService->setupPhpMyAdminUser();

            $this->logger->success('phpmyadmin_user_setup', 'database', $result['username'], [], $request);

            return back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            $this->logger->error('phpmyadmin_user_setup', 'database', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Erreur lors de la configuration : ' . $e->getMessage());
        }
    }

    /**
     * Redirige automatiquement vers phpMyAdmin avec les identifiants configurés.
     */
    public function connect(Request $request): RedirectResponse
    {
        $url = $this->mysqlUserService->getPhpMyAdminUrl();

        if (!$url) {
            return redirect()->route('phpmyadmin.index')
                ->with('error', 'Aucun identifiant phpMyAdmin configuré. Veuillez initialiser le compte d\'abord.');
        }

        $this->logger->success('phpmyadmin_connect', 'database', null, [], $request);

        return redirect($url);
    }
}
