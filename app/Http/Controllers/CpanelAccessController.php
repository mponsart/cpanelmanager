<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CpanelAccessController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index()
    {
        $host = config('cpanel.host');

        return view('cpanel.index', compact('host'));
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

            $this->logger->success('cpanel_autologin', 'cpanel', null, [], $request);

            return redirect()->away($url);
        } catch (\Throwable $e) {
            $this->logger->error('cpanel_autologin', 'cpanel', $e->getMessage(), null, [], $request);

            return back()->with('error', 'Erreur lors de la connexion à cPanel : ' . $e->getMessage());
        }
    }
}
