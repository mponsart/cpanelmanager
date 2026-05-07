@extends('layouts.app')

@section('title', 'Associations')
@section('page-title', 'Associations MonAsso')

@section('content')

<div class="page-header">
    <h1>Associations MonAsso</h1>
</div>

{{-- ── Création ───────────────────────────────────────────────────────── --}}
<div class="card mb-3">
    <div class="card-title">Créer une association</div>

    <form action="{{ route('association.store') }}" method="POST" class="inline-form">
        @csrf

        <div class="form-group">
            <label>Nom du dossier</label>
            <input type="text"
                   name="name"
                   required
                   maxlength="100"
                   pattern="[a-zA-Z0-9_-]+"
                   placeholder="mon-association"
                   value="{{ old('name') }}">

            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>

{{-- ── Liste ─────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-title">
        Associations ({{ count($associations) }})
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th style="width:120px;text-align:right;">Taille</th>
                    <th style="width:120px;text-align:center;">Quota</th>
                    <th style="width:170px;">Dernière modif.</th>
                    <th style="width:260px;text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($associations as $asso)
                <tr style="{{ $asso['suspended'] ? 'background:rgba(234,179,8,.05);' : '' }}">

                    {{-- NOM --}}
                    <td style="font-weight:500;">
                        <span style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span>{{ $asso['name'] }}</span>

                            @if($asso['suspended'])
                                <span style="font-size:11px;padding:2px 6px;background:#fef3c7;border:1px solid #fcd34d;border-radius:6px;">
                                    Suspendu
                                </span>
                            @endif
                        </span>
                    </td>

                    {{-- TAILLE --}}
                    <td style="text-align:right;">
                        @php
                            $size = $asso['size'];
                            if ($size >= 1073741824) $display = round($size/1073741824,2).' Go';
                            elseif ($size >= 1048576) $display = round($size/1048576,1).' Mo';
                            elseif ($size >= 1024) $display = round($size/1024,0).' Ko';
                            else $display = $size.' o';
                        @endphp
                        {{ $display }}
                    </td>

                    {{-- QUOTA --}}
                    <td style="text-align:center;">
                        @php $quota = (int) ($asso['quota_gb'] ?? 10); @endphp
                        <button type="button"
                                class="btn btn-ghost btn-sm"
                                onclick="openQuotaModal('{{ e($asso['name']) }}', {{ $quota }})">
                            {{ $quota }} Go
                        </button>
                    </td>

                    {{-- DATE --}}
                    <td class="text-muted">
                        {{ $asso['modified']
                            ? \Carbon\Carbon::createFromTimestamp($asso['modified'], 'Europe/Paris')->format('d/m/Y H:i')
                            : '—' }}
                    </td>

                    {{-- ACTIONS --}}
                    <td style="text-align:right;display:flex;justify-content:flex-end;gap:6px;">

                        {{-- Rename --}}
                        @if(!$asso['suspended'])
                        <button class="btn btn-ghost btn-sm"
                                onclick="openRenameModal('{{ e($asso['name']) }}')">
                            Renommer
                        </button>
                        @endif

                        {{-- Suspend --}}
                        @if(!$asso['suspended'])
                        <button class="btn btn-ghost btn-sm"
                                onclick="openSuspendModal('{{ e($asso['name']) }}')"
                                style="color:#d97706;">
                            Suspendre
                        </button>
                        @else
                        <form action="{{ route('association.unsuspend') }}" method="POST">
                            @csrf
                            <input type="hidden" name="name" value="{{ $asso['name'] }}">
                            <button class="btn btn-ghost btn-sm" style="color:#16a34a;">
                                Réactiver
                            </button>
                        </form>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('association.destroy') }}" method="POST"
                              onsubmit="return confirm('Supprimer cette association ?')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="name" value="{{ $asso['name'] }}">
                            <button class="btn btn-ghost btn-sm" style="color:#dc2626;">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">Aucune association</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODALE QUOTA ───────────────────────────────────────────────────── --}}
<div id="quota-modal" class="modal">
    <div class="modal-box">
        <h3>Quota stockage</h3>

        <form action="{{ route('association.storage-quota') }}" method="POST">
            @csrf
            <input type="hidden" name="name" id="quota-name">

            <select name="quota_gb" id="quota-select">
                @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}">{{ $i }} Go</option>
                @endfor
            </select>

            <div class="modal-actions">
                <button type="button" onclick="closeQuotaModal()">Annuler</button>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODALE SUSPEND ─────────────────────────────────────────────────── --}}
<div id="suspend-modal" class="modal">
    <div class="modal-box">
        <h3>Suspendre</h3>

        <form action="{{ route('association.suspend') }}" method="POST">
            @csrf
            <input type="hidden" name="name" id="suspend-name">

            <textarea name="reason"
                      id="suspend-reason"
                      required
                      minlength="5"
                      placeholder="Raison..."></textarea>

            <button type="submit" id="suspend-submit" disabled>Suspendre</button>
        </form>

        <button onclick="closeSuspendModal()">Annuler</button>
    </div>
</div>

{{-- ── MODALE RENAME CLEAN ───────────────────────────────────────────── --}}
<div id="rename-modal" class="modal">
    <div class="modal-box">
        <h3>Renommer</h3>

        <form action="{{ route('association.rename') }}" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="old_name" id="rename-old">

            <input type="text"
                   name="new_name"
                   id="rename-input"
                   required
                   pattern="[a-zA-Z0-9_-]+">

            <p id="rename-error" style="display:none;color:red;font-size:12px;"></p>

            <div class="modal-actions">
                <button type="button" onclick="closeRenameModal()">Annuler</button>
                <button type="submit" id="rename-submit" disabled>OK</button>
            </div>
        </form>
    </div>
</div>

{{-- ── JS ─────────────────────────────────────────────────────────────── --}}
<script>
function openQuotaModal(name, quota){
    document.getElementById('quota-modal').style.display='flex';
    document.getElementById('quota-name').value=name;
    document.getElementById('quota-select').value=quota;
}
function closeQuotaModal(){
    document.getElementById('quota-modal').style.display='none';
}

function openSuspendModal(name){
    document.getElementById('suspend-modal').style.display='flex';
    document.getElementById('suspend-name').value=name;
    document.getElementById('suspend-reason').value='';
}

function closeSuspendModal(){
    document.getElementById('suspend-modal').style.display='none';
}

function openRenameModal(name){
    document.getElementById('rename-modal').style.display='flex';
    document.getElementById('rename-old').value=name;
    document.getElementById('rename-input').value=name;
}

function closeRenameModal(){
    document.getElementById('rename-modal').style.display='none';
}

document.getElementById('suspend-reason')?.addEventListener('input', e=>{
    document.getElementById('suspend-submit').disabled = e.target.value.length < 5;
});

document.getElementById('rename-input')?.addEventListener('input', e=>{
    const val = e.target.value.trim();
    const ok = /^[a-zA-Z0-9_-]+$/.test(val);
    document.getElementById('rename-submit').disabled = !ok;
    document.getElementById('rename-error').style.display = ok ? 'none':'block';
});
</script>

@endsection