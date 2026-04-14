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
                <input type="text" name="address" placeholder="contact" required maxlength="255">
                @error('address')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Rediriger vers</label>
                <input type="email" name="forwardto" placeholder="destination@exemple.com" required maxlength="255">
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
                </tr>
            </thead>
            <tbody>
                @forelse($forwarders as $fwd)
                    <tr>
                        <td>{{ $fwd['dest'] ?? $fwd['forward'] ?? '—' }}</td>
                        <td class="text-muted">{{ $fwd['forward'] ?? $fwd['email'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-muted" style="text-align:center;padding:24px;">Aucune redirection.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
