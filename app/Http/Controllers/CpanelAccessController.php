<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CpanelAccessController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index()
    {
        $host     = config('cpanel.host');
        $port     = config('cpanel.port', 2083);
        $username = config('cpanel.username');

        $hasConfiguredPassword = ! empty(config('cpanel.password'));

        return view('cpanel.index', compact('host', 'port', 'username', 'hasConfiguredPassword'));
    }

    public function connect(Request $request): RedirectResponse
    {
        try {
            $data = $this->cpanel->call('Session', 'create_temp_user_session');

            $url = $data['data']['url'] ?? null;

            if (empty($url)) {
                $this->logger->error('cpanel_autologin', 'cpanel', 'Réponse sans URL de session', null, [], $request);

                return back()->with('error', 'Impossible de créer la session cPanel. Vérifiez les permissions du token API.');
            }

            $url = preg_replace('/^http:\/\//i', 'https://', $url);

            $this->logger->success('cpanel_autologin', 'cpanel', null, [], $request);

            return redirect()->away($url);
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_autologin', 'cpanel', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Erreur lors de la connexion à cPanel : ' . $e->getMessage());
        }
    }

    public function manualLogin(Request $request): RedirectResponse
    {
        $host = config('cpanel.host');
        $port = (int) config('cpanel.port', 2083);
        $user = config('cpanel.username');

        $configuredPassword = config('cpanel.password');

        if (empty($configuredPassword)) {
            return back()->with('error', 'CPANEL_PASSWORD n\'est pas configuré dans le fichier .env.');
        }

        try {
            $response = Http::withoutVerifying()
                ->withOptions(['allow_redirects' => false])
                ->timeout(30)
                ->post("https://{$host}:{$port}/login/", [
                    'user' => $user,
                    'pass' => $configuredPassword,
                ]);

            $location = $response->header('Location');

            if (! empty($location)) {
                if (! str_starts_with($location, 'http')) {
                    $location = "https://{$host}:{$port}{$location}";
                }

                $location = preg_replace('/^http:\/\//i', 'https://', $location);

                $this->logger->success('cpanel_manual_login', 'cpanel', null, [], $request);

                return redirect()->away($location);
            }

            $this->logger->error('cpanel_manual_login', 'cpanel', 'Réponse sans redirection', null, [], $request);

            return back()->with('error', 'Identifiants incorrects ou connexion refusée par cPanel.');
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_manual_login', 'cpanel', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }
    }
}
