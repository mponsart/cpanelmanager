@extends('layouts.app')

@section('title', $user->name)
@section('page-title', 'Utilisateur : ' . $user->name)

@section('content')

<div class="page-header">
    <h1>{{ $user->name }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost">Modifier</a>
        @if(!$user->is_super_admin)
            <a href="{{ route('permissions.user', $user) }}" class="btn btn-primary">Gérer les permissions</a>
        @endif
    </div>
</div>

<div class="form-row form-row-2 mb-3">
    <div class="card">
        <div class="card-title">Informations</div>
        <table>
            <tr><td class="text-muted" style="width:140px;">Nom</td>       <td>{{ $user->name }}</td></tr>
            <tr><td class="text-muted">E-mail</td>     <td>{{ $user->email }}</td></tr>
            <tr><td class="text-muted">Rôle</td>       <td>
                @if($user->is_super_admin)
                    <span class="badge" style="background:rgba(79,124,255,0.15); color:var(--accent);">Super Administrateur</span>
                @else
                    <span class="badge badge-muted">Technicien</span>
                @endif
            </td></tr>
            <tr><td class="text-muted">Statut</td>     <td>
                @if($user->status === 'active')
                    <span class="badge badge-success">Actif</span>
                @else
                    <span class="badge badge-error">Inactif</span>
                @endif
            </td></tr>
            <tr><td class="text-muted">Créé le</td>    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td class="text-muted">Dernière connexion</td><td>{{ $user->last_login_at?->format('d/m/Y H:i') ?? '—' }}</td></tr>
            <tr><td class="text-muted">Dernière IP</td><td class="text-muted">{{ $user->last_login_ip ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="card">
        <div class="card-title">Authentification</div>
        <table>
            <tr><td class="text-muted" style="width:140px;">Méthode</td><td>Google OAuth</td></tr>
            <tr><td class="text-muted">Google ID</td><td class="text-muted">{{ $user->google_id ?? 'Non encore lié (première connexion en attente)' }}</td></tr>
            @if($user->avatar)
            <tr><td class="text-muted">Avatar</td><td><img src="{{ $user->avatar }}" alt="" style="width:40px;height:40px;border-radius:50%;" referrerpolicy="no-referrer"></td></tr>
            @endif
        </table>
    </div>
</div>

<div class="card">
    <div class="card-title">Permissions actives ({{ $user->permissions->count() }})</div>
    @if($user->is_super_admin)
        <div class="alert alert-warning">
            Ce compte est <strong>Super Administrateur</strong> — il dispose de toutes les permissions système sans restriction. Les permissions ne peuvent pas être modifiées.
        </div>
    @else
        @forelse($user->permissions->groupBy('module') as $module => $perms)
            <div class="mb-3">
                <p class="text-muted text-sm mb-3" style="text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">{{ $module }}</p>
                <div class="flex gap-2" style="flex-wrap: wrap;">
                    @foreach($perms as $perm)
                        <span class="badge badge-muted">{{ $perm->label }}</span>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-muted">Aucune permission assignée.</p>
        @endforelse
    @endif
</div>

@if(auth()->id() !== $user->id && !$user->is_super_admin)
<div class="card" style="border-color: rgba(224,82,82,0.3);">
    <div class="card-title" style="color: var(--danger);">Zone dangereuse</div>
    <form action="{{ route('users.destroy', $user) }}" method="POST"
          onsubmit="return confirm('Supprimer {{ addslashes($user->name) }} ? Cette action est réversible.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Supprimer cet utilisateur</button>
    </form>
</div>
@endif

@endsection
