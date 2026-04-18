@extends('layouts.app')

@section('title', 'E-mails')
@section('page-title', 'Gestion des e-mails')

@section('content')

<div class="page-header">
    <h1>Adresses e-mail</h1>
    <a href="{{ route('email.forwarders') }}" class="btn btn-ghost">Redirections →</a>
</div>

<div class="card" id="create-email">
    <div class="card-title">Créer une adresse e-mail</div>
    <form action="{{ route('email.store') }}" method="POST">
        @csrf
        <div class="form-row form-row-2">
            <div class="form-group" style="grid-column: span 2;">
                <label>Adresse e-mail</label>
                <div class="input-group">
                    <input type="text" name="email" placeholder="contact" required maxlength="64" pattern="[a-zA-Z0-9._\-]+" value="{{ old('email') }}">
                    <span class="input-addon">@{{ config('cpanel.domain') }}</span>
                </div>
                <input type="hidden" name="domain" value="{{ config('cpanel.domain') }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required minlength="12" maxlength="255">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Quota (Mo, 0 = illimité)</label>
                <input type="number" name="quota" value="{{ old('quota', 0) }}" min="0" max="10240">
                @error('quota')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Créer l'adresse</button>
    </form>
</div>

<div class="card" id="emails-list">
    <div class="card-title">Adresses existantes ({{ count($emails) }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Adresse</th>
                    <th>Quota</th>
                    <th>Utilisé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emails as $mail)
                    @php
                        $login  = $mail['login'] ?? '';
                        $domain = $mail['domain'] ?? '';
                        $full   = $mail['email'] ?? ($login . ($domain ? '@' . $domain : ''));
                    @endphp
                    <tr>
                        <td>{{ $full }}</td>
                        <td class="text-muted">
                            @if(isset($mail['_max_quota']))
                                {{ $mail['_max_quota'] == 0 ? 'Illimité' : $mail['_max_quota'] . ' Mo' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-muted">{{ $mail['_current_quota'] ?? '—' }}</td>
                        <td>
                            <div class="flex gap-2">
                                {{-- Réinitialiser le mot de passe --}}
                                <button type="button" class="btn btn-ghost btn-sm"
                                        onclick="toggleResetForm(this)"
                                        data-email="{{ $login }}"
                                        data-domain="{{ $domain }}"
                                        title="Réinitialiser le mot de passe">
                                    Mot de passe
                                </button>
                                {{-- Supprimer --}}
                                <form action="{{ route('email.destroy') }}" method="POST"
                                      data-confirm="Supprimer {{ e($full) }} ?"
                                      onsubmit="return confirm(this.dataset.confirm)">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="email"  value="{{ $login }}">
                                    <input type="hidden" name="domain" value="{{ $domain }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </div>
                            {{-- Formulaire inline de réinitialisation --}}
                            <div class="reset-pw-form" style="display:none; margin-top:8px;">
                                <form action="{{ route('email.reset-password') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="email"  value="{{ $login }}">
                                    <input type="hidden" name="domain" value="{{ $domain }}">
                                    <div class="flex gap-2" style="align-items:flex-end; flex-wrap:wrap;">
                                        <div class="form-group" style="margin-bottom:0; flex:1; min-width:200px;">
                                            <label>Nouveau mot de passe (min. 12 car.)</label>
                                            <input type="password" name="password" required minlength="12" maxlength="255" placeholder="Nouveau mot de passe">
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm">Valider</button>
                                        <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.reset-pw-form').style.display='none'">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="table-empty">Aucune adresse e-mail.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleResetForm(btn) {
    var form = btn.closest('td').querySelector('.reset-pw-form');
    if (!form) return;
    form.style.display = form.style.display === 'none' ? '' : 'none';
}
</script>
@endpush

