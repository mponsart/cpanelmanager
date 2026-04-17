@extends('layouts.app')

@section('title', 'Permissions de ' . $user->name)
@section('page-title', 'Permissions : ' . $user->name)

@section('content')

<div class="page-header">
    <h1>Permissions de {{ $user->name }}</h1>
    <a href="{{ route('users.show', $user) }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="card">
    <form action="{{ route('permissions.sync', $user) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($allPermissions as $module => $perms)
            <div class="mb-3">
                <p style="font-weight:600; text-transform:uppercase; letter-spacing:0.5px; font-size:11px; color: var(--text-muted); margin-bottom:10px;">
                    {{ ucfirst($module) }}
                </p>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 8px;">
                    @foreach($perms as $perm)
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:10px 12px; background:var(--panel-soft); border-radius:var(--radius); border: 1px solid var(--border); font-size:13px; font-weight:400; color:var(--text); text-transform:none; letter-spacing:0;">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $perm->id }}"
                                {{ in_array($perm->id, $userPermIds) ? 'checked' : '' }}
                                style="width:16px; height:16px; accent-color: var(--accent);"
                            >
                            <span>
                                <strong style="font-size:12px;">{{ $perm->label }}</strong>
                                <br><small class="text-muted">{{ $perm->key }}</small>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Enregistrer les permissions</button>
            <a href="{{ route('users.show', $user) }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>

@endsection
