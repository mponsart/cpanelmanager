@extends('layouts.app')

@section('title', 'Redirections e-mail')
@section('page-title', 'Redirections e-mail')

@section('content')

<div class="page-header">
    <h1>Redirections (Forwarders)</h1>
    <a href="{{ route('email.index') }}" class="btn btn-ghost">← E-mails</a>
</div>

<div class="card">
    <div class="card-title">Créer une redirection</div>
    <form action="{{ route('email.add-forwarder') }}" method="POST">
        @csrf
        <div class="form-row form-row-2">
            <div class="form-group">
                <label>Adresse source (identifiant)</label>
                <input type="text" name="address" placeholder="contact" required maxlength="255" value="{{ old('address') }}">
                @error('address')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Rediriger vers</label>
                <input type="email" name="forwardto" placeholder="destination@exemple.com" required maxlength="255" value="{{ old('forwardto') }}">
                @error('forwardto')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Créer la redirection</button>
    </form>
</div>

<div class="card">
    <div class="card-title">Redirections actives ({{ count($forwarders) }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forwarders as $fwd)
                    @php
                        $source = $fwd['dest'] ?? $fwd['email'] ?? '—';
                        $dest   = $fwd['forward'] ?? '—';
                    @endphp
                    <tr>
                        <td>{{ $source }}</td>
                        <td class="text-muted">{{ $dest }}</td>
                        <td>
                            <form action="{{ route('email.delete-forwarder') }}" method="POST"
                              data-confirm="Supprimer la redirection {{ e($source) }} ?"
                              onsubmit="return confirm(this.dataset.confirm)">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="address" value="{{ $source }}">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted" style="text-align:center;padding:24px;">Aucune redirection.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

