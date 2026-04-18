@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')

<div class="page-header">
    <h1>Utilisateurs</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Nouvel utilisateur</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Statut</th>
                    <th>Permissions</th>
                    <th>Dernière connexion</th>
                    <th>Créé le</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr @if($user->trashed()) class="row-trashed" @endif>
                        <td>{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->trashed())
                                <span class="badge badge-muted">Supprimé</span>
                            @elseif($user->status === 'active')
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-error">Inactif</span>
                            @endif
                            @if($user->is_super_admin)
                                <span class="badge badge-accent">Super Admin</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->permissions->count() }} permission(s)</td>
                        <td class="text-muted text-sm">{{ $user->last_login_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="text-muted text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-ghost btn-sm">Voir</a>
                                @if($user->trashed())
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-sm">Restaurer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="table-empty">Aucun utilisateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->withQueryString()->links('vendor.pagination.default') }}
</div>

@endsection
