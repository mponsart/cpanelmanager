@extends('layouts.app')

@section('title', 'Domaines')
@section('page-title', 'Gestion des domaines')

@section('content')

<div class="page-header">
    <h1>Gestion des domaines</h1>
</div>

<div class="form-row form-row-2 mb-3">
    <div class="card" id="create-addon-domain">
        <div class="card-title">Ajouter un domaine addon</div>
        <form action="{{ route('domain.create-addon') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nom de domaine</label>
                <input type="text" id="addon-newdomain" name="newdomain" placeholder="exemple.com" required maxlength="253">
                @error('newdomain')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Sous-domaine cPanel (identifiant unique)</label>
                <input type="text" id="addon-subdomain" name="subdomain" placeholder="exemple" required maxlength="63" pattern="[a-zA-Z0-9\-]+">
                @error('subdomain')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Répertoire racine</label>
                <input type="text" id="addon-dir" name="dir" placeholder="public_html/exemple.com" required maxlength="255">
                @error('dir')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <div class="card" id="create-subdomain">
        <div class="card-title">Créer un sous-domaine</div>
        <form action="{{ route('domain.create-subdomain') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Préfixe sous-domaine</label>
                <input type="text" id="sub-prefix" name="domain" placeholder="dev" required maxlength="63" pattern="[a-zA-Z0-9\-]+">
                @error('domain')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Domaine racine</label>
                <input type="text" name="rootdomain" value="{{ config('cpanel.domain') }}" required maxlength="253">
                @error('rootdomain')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Répertoire (optionnel)</label>
                <input type="text" id="sub-dir" name="dir" placeholder="public_html/dev" maxlength="255">
                @error('dir')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</div>

<div class="form-row form-row-2">
    <div class="card">
        <div class="card-title">Domaines actifs</div>
        @if(!empty($domains))
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Domaine</th><th>Type</th></tr></thead>
                    <tbody>
                        @foreach($domains as $domain)
                            <tr>
                                <td>{{ is_array($domain) ? ($domain['domain'] ?? '—') : $domain }}</td>
                                <td class="text-muted text-sm">{{ is_array($domain) ? ($domain['type'] ?? '—') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Aucun domaine disponible.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Sous-domaines actifs</div>
        @if(!empty($subdomains))
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Sous-domaine</th><th>Répertoire</th></tr></thead>
                    <tbody>
                        @foreach($subdomains as $sub)
                            <tr>
                                <td>{{ is_array($sub) ? ($sub['domain'] ?? '—') : $sub }}</td>
                                <td class="text-muted text-sm">{{ is_array($sub) ? ($sub['homedir'] ?? '—') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Aucun sous-domaine.</p>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-remplissage formulaire addon domain
document.getElementById('addon-newdomain').addEventListener('input', function () {
    const domain = this.value.trim().toLowerCase().replace(/[^a-z0-9\-\.]/g, '');
    const slug   = domain.replace(/\./g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');

    const subEl = document.getElementById('addon-subdomain');
    const dirEl = document.getElementById('addon-dir');

    if (!subEl._touched) subEl.value = slug;
    if (!dirEl._touched) dirEl.value = domain;
});
document.getElementById('addon-subdomain').addEventListener('input', function () { this._touched = !!this.value; });
document.getElementById('addon-dir').addEventListener('input',       function () { this._touched = !!this.value; });

// Auto-remplissage formulaire sous-domaine
document.getElementById('sub-prefix').addEventListener('input', function () {
    const prefix = this.value.trim().toLowerCase();
    const dirEl  = document.getElementById('sub-dir');
    if (!dirEl._touched) dirEl.value = prefix ? 'public_html/' + prefix : '';
});
document.getElementById('sub-dir').addEventListener('input', function () { this._touched = !!this.value; });
</script>
@endpush
