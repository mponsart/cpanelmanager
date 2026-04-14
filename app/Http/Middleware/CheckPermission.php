<?php

namespace App\Http\Middleware;

use App\Services\LoggerService;
use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(
        private PermissionService $permissionService,
        private LoggerService     $loggerService
    ) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $this->permissionService->userCan($user, $permission)) {
            $this->loggerService->denied(
                action: $permission,
                module: explode('_', $permission, 2)[1] ?? 'unknown',
                target: $request->route()?->uri(),
                request: $request
            );

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Permission refusée.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        return $next($request);
    }
}
