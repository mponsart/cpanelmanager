<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    private const CACHE_PREFIX = 'user_permissions_';
    private const CACHE_TTL    = 300; // 5 minutes

    /**
     * Vérifie si un utilisateur possède la permission donnée.
     * Les super admins contournent systématiquement cette vérification.
     */
    public function userCan(User $user, string $permissionKey): bool
    {
        if (! $user->isActive()) {
            return false;
        }

        // Le super admin a toutes les permissions, sans exception
        if ($user->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->getUserPermissions($user);

        return in_array($permissionKey, $permissions, true);
    }

    /**
     * Retourne la liste des clés de permissions d'un utilisateur (avec cache).
     */
    public function getUserPermissions(User $user): array
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            return $user->permissions()->pluck('key')->toArray();
        });
    }

    /**
     * Invalide le cache des permissions d'un utilisateur.
     */
    public function clearCache(int $userId): void
    {
        Cache::forget(self::CACHE_PREFIX . $userId);
    }

    /**
     * Vérifie plusieurs permissions à la fois (toutes requises).
     */
    public function userCanAll(User $user, array $permissionKeys): bool
    {
        foreach ($permissionKeys as $key) {
            if (! $this->userCan($user, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si l'utilisateur possède au moins une des permissions.
     */
    public function userCanAny(User $user, array $permissionKeys): bool
    {
        foreach ($permissionKeys as $key) {
            if ($this->userCan($user, $key)) {
                return true;
            }
        }

        return false;
    }
}
