<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Services\LoggerService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function __construct(
        private LoggerService     $logger,
        private PermissionService $permissionService
    ) {}

    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('key')->get()->groupBy('module');

        return view('permissions.index', compact('permissions'));
    }

    public function userPermissions(User $user)
    {
        $allPermissions  = Permission::orderBy('module')->orderBy('key')->get()->groupBy('module');
        $userPermIds     = $user->permissions()->pluck('permissions.id')->toArray();

        return view('permissions.user', compact('user', 'allPermissions', 'userPermIds'));
    }

    public function grant(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Les permissions d\'un super administrateur sont gérées automatiquement.');
        }

        $data = $request->validate([
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
        ]);

        $permission = Permission::findOrFail($data['permission_id']);

        // Vérification doublon
        $exists = UserPermission::where('user_id', $user->id)
                                ->where('permission_id', $permission->id)
                                ->exists();

        if ($exists) {
            return back()->with('error', 'L\'utilisateur possède déjà cette permission.');
        }

        UserPermission::create([
            'user_id'       => $user->id,
            'permission_id' => $permission->id,
            'granted_by'    => auth()->id(),
        ]);

        $this->permissionService->clearCache($user->id);

        $this->logger->success(
            action: 'grant_permission',
            module: 'permissions',
            target: $user->email,
            payload: ['permission' => $permission->key],
            request: $request
        );

        return back()->with('success', 'Permission "' . e($permission->label) . '" accordée.');
    }

    public function revoke(Request $request, User $user, Permission $permission)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Impossible de révoquer une permission d\'un super administrateur.');
        }

        UserPermission::where('user_id', $user->id)
                      ->where('permission_id', $permission->id)
                      ->delete();

        $this->permissionService->clearCache($user->id);

        $this->logger->success(
            action: 'revoke_permission',
            module: 'permissions',
            target: $user->email,
            payload: ['permission' => $permission->key],
            request: $request
        );

        return back()->with('success', 'Permission "' . e($permission->label) . '" révoquée.');
    }

    public function sync(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Les permissions d\'un super administrateur ne peuvent pas être modifiées.');
        }

        $data = $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $data['permissions'] ?? [];

        // Supprimer les permissions existantes et recréer
        UserPermission::where('user_id', $user->id)->delete();

        foreach ($permissionIds as $permId) {
            UserPermission::create([
                'user_id'       => $user->id,
                'permission_id' => $permId,
                'granted_by'    => auth()->id(),
            ]);
        }

        $this->permissionService->clearCache($user->id);

        $this->logger->success(
            action: 'sync_permissions',
            module: 'permissions',
            target: $user->email,
            payload: ['permission_ids' => $permissionIds],
            request: $request
        );

        return redirect()->route('permissions.user', $user)->with('success', 'Permissions synchronisées.');
    }
}
