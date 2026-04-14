<?php

namespace App\Services;

use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggerService
{
    /**
     * Enregistre une action réussie.
     */
    public function success(
        string  $action,
        string  $module,
        ?string $target  = null,
        array   $payload = [],
        ?Request $request = null
    ): void {
        $this->write('success', $action, $module, $target, $payload, null, $request);
    }

    /**
     * Enregistre une erreur.
     */
    public function error(
        string  $action,
        string  $module,
        string  $errorMessage,
        ?string $target  = null,
        array   $payload = [],
        ?Request $request = null
    ): void {
        $this->write('error', $action, $module, $target, $payload, $errorMessage, $request);
    }

    /**
     * Enregistre un accès refusé (permission manquante).
     */
    public function denied(
        string  $action,
        string  $module,
        ?string $target  = null,
        ?Request $request = null
    ): void {
        $this->write('denied', $action, $module, $target, [], 'Permission refusée', $request);
    }

    /**
     * Enregistre un événement d'authentification.
     */
    public function auth(
        string  $action,
        string  $status,
        ?string $errorMessage = null,
        ?Request $request = null
    ): void {
        $this->write($status, $action, 'auth', null, [], $errorMessage, $request);
    }

    private function write(
        string   $status,
        string   $action,
        string   $module,
        ?string  $target,
        array    $payload,
        ?string  $errorMessage,
        ?Request $request
    ): void {
        // Sanitize payload : ne jamais logger les mots de passe
        array_walk_recursive($payload, function (&$value, string $key) {
            if (in_array(strtolower($key), ['password', 'password_confirmation', 'token'], true)) {
                $value = '[REDACTED]';
            }
        });

        ActionLog::create([
            'user_id'       => Auth::id(),
            'action'        => $action,
            'module'        => $module,
            'target'        => $target,
            'payload'       => ! empty($payload) ? $payload : null,
            'ip'            => $request?->ip(),
            'user_agent'    => $request ? mb_substr($request->userAgent() ?? '', 0, 500) : null,
            'status'        => $status,
            'error_message' => $errorMessage,
            'created_at'    => now(),
        ]);
    }
}
