@extends('layouts.app')

@section('title', 'Associations')
@section('page-title', 'Associations MonAsso')

@section('content')

<div class="card mb-3">
    <div class="card-title">Créer une association</div>
    <form action="{{ route('association.store') }}" method="POST" style="display:flex;align-items:flex-end;gap:12px;">
        @csrf
        <div class="form-group" style="flex:1;margin-bottom:0;">
            <label>Nom du dossier</label>
            <input type="text" name="name" required maxlength="100" pattern="[a-zA-Z0-9_-]+" placeholder="mon-association" value="{{ old('name') }}">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>

<div class="card">
    <div class="card-title">Associations ({{ count($associations) }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th style="width:120px;text-align:right;">Taille</th>
                    <th style="width:170px;">Dernière modification</th>
                    <th style="width:200px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($associations as $asso)
                    <tr>
                        <td style="font-weight:500;">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5"><path d="M2 3.5A1.5 1.5 0 013.5 2h3l1.5 2h4.5A1.5 1.5 0 0114 5.5v7a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 12.5v-9z"/></svg>
                                {{ $asso['name'] }}
                            </span>
                        </td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums;" class="text-muted">
                            @php
                                $size = $asso['size'];
                                if ($size >= 1073741824) $display = round($size / 1073741824, 2) . ' Go';
                                elseif ($size >= 1048576) $display = round($size / 1048576, 1) . ' Mo';
                                elseif ($size >= 1024) $display = round($size / 1024, 0) . ' Ko';
                                else $display = $size . ' o';
                            @endphp
                            {{ $display }}
                        </td>
                        <td class="text-muted text-sm">
                            {{ $asso['modified'] ? \Carbon\Carbon::createFromTimestamp($asso['modified'])->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                {{-- Renommer --}}
                                <form action="{{ route('association.rename') }}" method="POST" style="display:inline-flex;align-items:center;gap:4px;"
                                      onsubmit="
                                        var newName = this.querySelector('[name=new_name]').value;
                                        if (!newName) { event.preventDefault(); this.querySelector('[name=new_name]').style.display='inline'; this.querySelector('[name=new_name]').focus(); return; }
                                      ">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="old_name" value="{{ $asso['name'] }}">
                                    <input type="text" name="new_name" placeholder="Nouveau nom" maxlength="100" pattern="[a-zA-Z0-9_-]+"
                                           style="display:none;width:140px;padding:4px 8px;font-size:0.85rem;border:1px solid var(--border);border-radius:6px;">
                                    <button type="submit" class="btn btn-ghost btn-sm" title="Renommer">
                                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11.5 1.5l3 3L5 14H2v-3L11.5 1.5z"/></svg>
                                    </button>
                                </form>
                                {{-- Supprimer --}}
                                <form action="{{ route('association.destroy') }}" method="POST"
                                      onsubmit="return confirm('Supprimer définitivement l\'association « {{ $asso['name'] }} » et tout son contenu ?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="name" value="{{ $asso['name'] }}">
                                    <button type="submit" class="btn btn-ghost btn-sm" title="Supprimer" style="color:var(--danger);">
                                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3,5 13,5"/><path d="M5.5 5V3.5a1 1 0 011-1h3a1 1 0 011 1V5"/><path d="M4 5l.5 8.5a1 1 0 001 1h5a1 1 0 001-1L12 5"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted" style="text-align:center;padding:20px;">Aucune association trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
