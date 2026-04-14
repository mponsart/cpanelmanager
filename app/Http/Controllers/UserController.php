<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoggerService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        private LoggerService     $logger,
        private PermissionService $permissionService
    ) {}

    public function index(Request $request)
    {
        $users = User::withTrashed()
                     ->with('permissions')
                     ->orderByDesc('created_at')
                     ->paginate(25);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'status'   => $data['status'],
        ]);

        $this->logger->success(
            action: 'create_user',
            module: 'users',
            target: $user->email,
            payload: ['name' => $user->name, 'email' => $user->email, 'status' => $user->status],
            request: $request
        );

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load('permissions');

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Empêcher la désactivation d'un super admin
        if ($user->isSuperAdmin() && $request->input('status') === 'inactive') {
            return back()->with('error', 'Impossible de désactiver un super administrateur.');
        }

        $data = $request->validate([
            'name'   => ['required', 'string', 'min:2', 'max:100'],
            'email'  => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user->update($data);

        if ($data['status'] === 'inactive') {
            $this->permissionService->clearCache($user->id);
        }

        $this->logger->success(
            action: 'update_user',
            module: 'users',
            target: $user->email,
            payload: $data,
            request: $request
        );

        return redirect()->route('users.show', $user)->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Impossible de supprimer un super administrateur.');
        }

        $user->delete();

        $this->permissionService->clearCache($user->id);

        $this->logger->success(
            action: 'delete_user',
            module: 'users',
            target: $user->email,
            request: $request
        );

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function restore(Request $request, int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $this->logger->success(
            action: 'restore_user',
            module: 'users',
            target: $user->email,
            request: $request
        );

        return redirect()->route('users.show', $user)->with('success', 'Utilisateur restauré.');
    }
}
