<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CpanelService
{
    private string $host;
    private int    $port;
    private string $username;
    private string $token;

    public function __construct()
    {
        $this->host     = config('cpanel.host');
        $this->port     = (int) config('cpanel.port', 2083);
        $this->username = config('cpanel.username');
        $this->token    = config('cpanel.token');

        if (empty($this->host) || empty($this->username) || empty($this->token)) {
            throw new RuntimeException('Configuration cPanel incomplète. Vérifiez CPANEL_HOST, CPANEL_USERNAME et CPANEL_TOKEN dans .env.');
        }
    }

    /**
     * Point d'entrée unique pour tous les appels cPanel UAPI.
     *
     * @param  string $module   Module cPanel (ex: Email, Mysql, DomainInfo...)
     * @param  string $function Fonction à appeler (ex: add_pop, create_database...)
     * @param  array  $params   Paramètres de l'appel
     * @return array
     */
    public function call(string $module, string $function, array $params = []): array
    {
        $url = sprintf(
            'https://%s:%d/execute/%s/%s',
            $this->host,
            $this->port,
            urlencode($module),
            urlencode($function)
        );

        $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->token,
            ])
            ->withoutVerifying() // Désactivable si certificat SSL valide
            ->timeout(30)
            ->get($url, $params);

        return $this->parseResponse($response, $module, $function);
    }

    /**
     * Appel cPanel API2 (legacy) pour les modules non portés sur UAPI.
     */
    public function callApi2(string $module, string $function, array $params = []): array
    {
        $url = sprintf(
            'https://%s:%d/json-api/cpanel',
            $this->host,
            $this->port
        );

        $payload = array_merge([
            'cpanel_jsonapi_user'    => $this->username,
            'cpanel_jsonapi_apiversion' => '2',
            'cpanel_jsonapi_module'  => $module,
            'cpanel_jsonapi_func'    => $function,
        ], $params);

        $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->token,
            ])
            ->withoutVerifying()
            ->timeout(30)
            ->post($url, $payload);

        return $this->parseResponse($response, $module, $function);
    }

    /**
     * Retourne l'URL phpMyAdmin via le proxy cPanel.
     */
    public function getPhpMyAdminUrl(): string
    {
        return sprintf(
            'https://%s:%d/3rdparty/phpMyAdmin/index.php',
            $this->host,
            $this->port
        );
    }

    /**
     * Retourne l'URL de base cPanel (pour liens directs).
     */
    public function getCpanelBaseUrl(): string
    {
        return sprintf('https://%s:%d', $this->host, $this->port);
    }

    private function parseResponse(Response $response, string $module, string $function): array
    {
        if ($response->failed()) {
            throw new RuntimeException(
                sprintf('Erreur HTTP cPanel [%s::%s] — statut %d', $module, $function, $response->status())
            );
        }

        $data = $response->json();

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                sprintf('Réponse cPanel non-JSON [%s::%s]', $module, $function)
            );
        }

        // UAPI: errors array
        if (isset($data['errors']) && ! empty($data['errors'])) {
            throw new RuntimeException(
                sprintf('Erreur cPanel [%s::%s] : %s', $module, $function, implode(', ', (array) $data['errors']))
            );
        }

        // API2: event/result
        if (isset($data['cpanelresult']['data']['result']) && $data['cpanelresult']['data']['result'] == 0) {
            $reason = $data['cpanelresult']['data']['reason'] ?? 'Erreur inconnue';
            throw new RuntimeException(
                sprintf('Erreur cPanel API2 [%s::%s] : %s', $module, $function, $reason)
            );
        }

        return $data;
    }
}
