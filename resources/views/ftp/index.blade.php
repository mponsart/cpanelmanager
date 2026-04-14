@extends('layouts.app')

@section('title', 'FTP')
@section('page-title', 'Comptes FTP')

@section('content')

<div class="page-header">
    <h1>Comptes FTP</h1>
</div>

<div class="card">
    <div class="card-title">Créer un compte FTP</div>
    <form action="{{ route('ftp.store') }}" method="POST">
        @csrf
        <div class="form-row form-row-2">
            <div class="form-group">
                <label>Identifiant</label>
                <input type="text" name="user" required maxlength="32" pattern="[a-zA-Z0-9_\-\.]+" placeholder="ftpuser">
                @error('user')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Domaine</label>
                <input type="text" name="domain" value="{{ config('cpanel.domain') }}" required maxlength="253">
                @error('domain')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required minlength="12" maxlength="255">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Quota (Mo, 0 = illimité)</label>
                <input type="number" name="quota" value="0" min="0" max="10240">
                @error('quota')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Répertoire home</label>
                <input type="text" name="homedir" placeholder="public_html" required maxlength="255">
                @error('homedir')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>

<div class="card">
    <div class="card-title">Comptes FTP ({{ count($accounts) }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Domaine</th>
                    <th>Répertoire</th>
                    <th>Quota</th>
                    <th>Utilisé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $account['user'] ?? '—' }}</td>
                        <td class="text-muted">{{ $account['domain'] ?? '—' }}</td>
                        <td class="text-muted text-sm">{{ $account['homedir'] ?? '—' }}</td>
                        <td class="text-muted">{{ isset($account['quota_mb']) ? ($account['quota_mb'] == 0 ? 'Illimité' : $account['quota_mb'] . ' Mo') : '—' }}</td>
                        <td class="text-muted">{{ $account['diskused'] ?? '—' }}</td>
                        <td>
                            <form action="{{ route('ftp.destroy') }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce compte FTP ?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="user"   value="{{ $account['user'] ?? '' }}">
                                <input type="hidden" name="domain" value="{{ $account['domain'] ?? '' }}">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted" style="text-align:center;padding:24px;">Aucun compte FTP.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
var ftpUser = document.querySelector('input[name="user"]');
var ftpDir  = document.querySelector('input[name="homedir"]');
if (ftpUser && ftpDir) {
    ftpUser.addEventListener('input', function() {
        if (!ftpDir._touched) ftpDir.value = this.value ? 'public_html/' + this.value.toLowerCase() : '';
    });
    ftpDir.addEventListener('input', function() { this._touched = !!this.value; });
}
</script>
@endpush
