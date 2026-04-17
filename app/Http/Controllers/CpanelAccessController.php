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

        // Generate a per-request RSA key pair for encrypting the manual login password.
        // The private key is kept server-side in the session; the public key is sent to the browser.
        $publicKey = null;
        $keyResource = openssl_pkey_new([
            'digest_alg'       => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        if ($keyResource !== false) {
            $privateKeyPem = '';
            openssl_pkey_export($keyResource, $privateKeyPem);
            session(['cpanel_manual_private_key' => $privateKeyPem]);
            $details   = openssl_pkey_get_details($keyResource);
            $publicKey = $details['key'] ?? null;
        } else {
            \Log::warning('cpanel_manual_login: RSA key generation failed', ['openssl_errors' => openssl_error_string()]);
        }

        return view('cpanel.index', compact('host', 'port', 'username', 'publicKey'));
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

    public function manualLogin(Request $request): RedirectResponse
    {
        $privateKeyPem = session('cpanel_manual_private_key');

        // Clear the private key immediately to prevent replay attacks.
        session()->forget('cpanel_manual_private_key');

        if (empty($privateKeyPem)) {
            return back()->with('error', 'Session expirée. Veuillez recharger la page et réessayer.');
        }

        $encryptedPass = $request->input('encrypted_pass', '');

        if (empty($encryptedPass)) {
            return back()->with('error', 'Mot de passe manquant ou non chiffré.');
        }

        $rawEncrypted = base64_decode($encryptedPass, true);

        if ($rawEncrypted === false) {
            return back()->with('error', 'Données chiffrées invalides. Veuillez recharger la page et réessayer.');
        }

        $privateKey    = openssl_pkey_get_private($privateKeyPem);
        $decryptedPass = '';
        $ok = openssl_private_decrypt(
            $rawEncrypted,
            $decryptedPass,
            $privateKey,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (! $ok || $decryptedPass === '') {
            return back()->with('error', 'Erreur de déchiffrement. Veuillez recharger la page et réessayer.');
        }

        $host = config('cpanel.host');
        $port = (int) config('cpanel.port', 2083);
        $user = $request->input('user', config('cpanel.username'));

        try {
            $response = Http::withoutVerifying()
                ->withOptions(['allow_redirects' => false])
                ->timeout(30)
                ->post("https://{$host}:{$port}/login/", [
                    'user' => $user,
                    'pass' => $decryptedPass,
                ]);

            // Clear the plaintext password from memory as soon as possible.
            $decryptedPass = str_repeat("\0", strlen($decryptedPass));
            unset($decryptedPass);

            $location = $response->header('Location');

            if (! empty($location)) {
                if (! str_starts_with($location, 'http')) {
                    $location = "https://{$host}:{$port}{$location}";
                }
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
