<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Services\CpanelService;
use App\Services\LoggerService;
use App\Services\PasswordRotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CpanelAccessController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger,
        private PasswordRotationService $rotator
    ) {}

    public function index()
    {
        $host     = config('cpanel.host');
        $port     = config('cpanel.port', 2083);
        $username = config('cpanel.username');
        $domain   = config('cpanel.domain');

        // Mot de passe visible pour tous les utilisateurs ayant accès à la page
        $password    = config('cpanel.password');
        $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;

        $cpanelUrl = $host ? "https://{$host}:{$port}" : null;

        // Dernière rotation du mot de passe
        $lastRotation = ActionLog::where('module', 'cpanel')
            ->whereIn('action', ['cpanel_force_rotate_password', 'cpanel_scheduled_rotate_password', 'cpanel_end_session_rotate'])
            ->where('status', 'success')
            ->latest('created_at')
            ->first();

        $lastRotationAt   = $lastRotation?->created_at;
        $lastRotationType = match ($lastRotation?->action ?? '') {
            'cpanel_force_rotate_password'      => 'Manuelle',
            'cpanel_scheduled_rotate_password'  => 'Planifiée (cron)',
            'cpanel_end_session_rotate'         => 'Fin de session',
            default                             => null,
        };

        return view('cpanel.index', compact(
            'host', 'port', 'username', 'domain', 'password',
            'isSuperAdmin', 'cpanelUrl', 'lastRotationAt', 'lastRotationType'
        ));
    }

    public function manualLogin(Request $request)
    {
        $host = config('cpanel.host');
        $port = (int) config('cpanel.port', 2083);
        $user = config('cpanel.username');

        $configuredPassword = config('cpanel.password');

        if (empty($configuredPassword)) {
            return response()->json(['error' => 'CPANEL_PASSWORD n\'est pas configuré dans le fichier .env.'], 422);
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

                return response()->json(['url' => $location]);
            }

            $this->logger->error('cpanel_manual_login', 'cpanel', 'Réponse sans redirection', null, [], $request);

            return response()->json(['error' => 'Identifiants incorrects ou connexion refusée par cPanel.'], 401);
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_manual_login', 'cpanel', $e->getMessage(), null, [], $request);

            return response()->json(['error' => 'Erreur lors de la connexion : ' . $e->getMessage()], 500);
        }
    }

    public function endSession(Request $request): RedirectResponse
    {
        try {
            $this->rotator->rotate();
            $this->logger->success('cpanel_end_session_rotate', 'cpanel', null, [], $request);

            return back()->with('success', 'Session terminée — le mot de passe cPanel a été changé.');
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_end_session_rotate', 'cpanel', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Session terminée mais la rotation du mot de passe a échoué : ' . $e->getMessage());
        }
    }

    public function forceRotate(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);

        try {
            $this->rotator->rotate();
            $this->logger->success('cpanel_force_rotate_password', 'cpanel', null, [], $request);

            return back()->with('success', 'Mot de passe cPanel changé avec succès.');
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_force_rotate_password', 'cpanel', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Échec de la rotation du mot de passe : ' . $e->getMessage());
        }
    }

    private function rotatePasswordSilently(Request $request): void
    {
        try {
            $this->rotator->rotate();
            $this->logger->success('cpanel_auto_rotate_password', 'cpanel', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_auto_rotate_password', 'cpanel', $e->getMessage(), null, [], $request);
        }
    }
}
