@extends('layouts.app')

@section('title', 'Associations')
@section('page-title', 'Associations MonAsso')

@section('content')

<div class="page-header">
    <h1>Associations MonAsso</h1>
</div>

<div class="card mb-3">
    <div class="card-title">Créer une association</div>
    <form action="{{ route('association.store') }}" method="POST" class="inline-form">
        @csrf
        <div class="form-group">
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
                    <th style="width:120px;text-align:center;">Quota</th>
                    <th style="width:170px;">Dernière modification</th>
                    <th style="width:280px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($associations as $asso)
                    <tr style="{{ $asso['suspended'] ? 'background:rgba(234,179,8,.04);' : '' }}">
                        <td style="font-weight:500;">
                            <span style="display:inline-flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="{{ $asso['suspended'] ? '#d97706' : 'var(--accent)' }}" stroke-width="1.5"><path d="M2 3.5A1.5 1.5 0 013.5 2h3l1.5 2h4.5A1.5 1.5 0 0114 5.5v7a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 12.5v-9z"/></svg>
                                <span style="{{ $asso['suspended'] ? 'opacity:.7;' : '' }}">{{ $asso['name'] }}</span>
                                @if($asso['suspended'])
                                    @php $si = $asso['suspend_info']; @endphp
                                    <button type="button"
                                        onclick="document.getElementById('suspend-info-{{ $loop->index }}').style.display = document.getElementById('suspend-info-{{ $loop->index }}').style.display === 'none' ? 'block' : 'none'"
                                        style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:2px 7px;border-radius:4px;background:rgba(234,179,8,.15);color:#92400e;border:1px solid rgba(234,179,8,.4);cursor:pointer;line-height:1.5;">
                                        <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="3" height="12" rx="1"/><rect x="9" y="2" width="3" height="12" rx="1"/></svg>
                                        Suspendu
                                    </button>
                                    <div id="suspend-info-{{ $loop->index }}" style="display:none;width:100%;margin-top:6px;padding:10px 12px;background:rgba(234,179,8,.07);border:1px solid rgba(234,179,8,.3);border-radius:8px;font-size:12px;color:var(--text);line-height:1.6;">
                                        @if(!empty($si['reason']))
                                            <div style="margin-bottom:4px;"><strong>Raison :</strong> {{ $si['reason'] }}</div>
                                        @endif
                                        @if(!empty($si['suspended_by']))
                                            <div style="color:var(--text-muted,#6b7280);"><strong>Par :</strong> {{ $si['suspended_by'] }}</div>
                                        @endif
                                        @if(!empty($si['suspended_at']))
                                            <div style="color:var(--text-muted,#6b7280);"><strong>Le :</strong> {{ \Carbon\Carbon::parse($si['suspended_at'])->setTimezone('Europe/Paris')->format('d/m/Y à H:i') }}</div>
                                        @endif
                                    </div>
                                @endif
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
                        <td style="text-align:center;">
                            @php $currentQuota = (int) ($asso['quota_gb'] ?? 10); @endphp
                            <button type="button"
                                class="btn btn-ghost btn-sm"
                                title="Configurer le quota"
                                onclick="openQuotaModal('{{ e($asso['name']) }}', {{ $currentQuota }})"
                                style="padding:4px 8px;display:inline-flex;align-items:center;gap:6px;">
                                <span style="font-size:11px;color:var(--text-muted);">{{ $currentQuota }} Go</span>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                            </button>
                        </td>
                        <td class="text-muted text-sm">
                            {{ $asso['modified'] ? \Carbon\Carbon::createFromTimestamp($asso['modified'], 'Europe/Paris')->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                {{-- Renommer (désactivé si suspendu) --}}
                                @if(!$asso['suspended'])
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
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </button>
                                </form>
                                @endif

                                {{-- Suspendre / Réactiver --}}
                                @if($asso['suspended'])
                                    <form action="{{ route('association.unsuspend') }}" method="POST"
                                          data-confirm="Réactiver l'association « {{ e($asso['name']) }} » ?"
                                          onsubmit="return confirm(this.dataset.confirm)">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $asso['name'] }}">
                                        <button type="submit" class="btn btn-ghost btn-sm" title="Réactiver" style="color:#16a34a;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                            <span style="font-size:11px;margin-left:2px;">Réactiver</span>
                                        </button>
                                    </form>
                                @else
                                    <button type="button"
                                        onclick="openSuspendModal('{{ e($asso['name']) }}')"
                                        class="btn btn-ghost btn-sm" title="Suspendre" style="color:#d97706;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        <span style="font-size:11px;margin-left:2px;">Suspendre</span>
                                    </button>
                                @endif

                                {{-- Supprimer --}}
                                <form action="{{ route('association.destroy') }}" method="POST"
                                      data-confirm="Supprimer définitivement l'association « {{ e($asso['name']) }} » et tout son contenu ?"
                                      onsubmit="return confirm(this.dataset.confirm)">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="name" value="{{ $asso['name'] }}">
                                    <button type="submit" class="btn btn-ghost btn-sm" title="Supprimer" style="color:var(--danger);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="table-empty">Aucune association trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Modale de quota ─────────────────────────────────────────────────── --}}
<div id="quota-modal" style="display:none;position:fixed;inset:0;background:rgba(32,33,36,.42);backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);z-index:1000;align-items:center;justify-content:center;padding:18px;">
    <div style="background:#fff;border:1px solid #dadce0;border-radius:28px;width:100%;max-width:460px;box-shadow:0 10px 24px rgba(60,64,67,.28),0 1px 3px rgba(60,64,67,.2);overflow:hidden;">
        <div style="padding:22px 24px 14px;border-bottom:1px solid #e8eaed;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:12px;background:#e8f0fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                </div>
                <div>
                    <div style="font-weight:500;font-size:20px;line-height:1.2;color:#202124;">Quota de stockage</div>
                    <div id="quota-modal-name" style="font-size:13px;color:#5f6368;margin-top:2px;"></div>
                </div>
            </div>
        </div>

        <form id="quota-form" action="{{ route('association.storage-quota') }}" method="POST">
            @csrf
            <input type="hidden" name="name" id="quota-input-name">
            <div style="padding:20px 24px;">
                <label for="quota-gb" style="display:block;font-size:13px;font-weight:500;color:#3c4043;margin-bottom:7px;">
                    Quota autorisé (entre 1 et 10 Go)
                </label>
                <select id="quota-gb" name="quota_gb" required style="width:100%;padding:11px 12px;border:1px solid #dadce0;border-radius:12px;background:#fff;color:#202124;font-size:14px;">
                    @for($q = 1; $q <= 10; $q++)
                        <option value="{{ $q }}">{{ $q }} Go</option>
                    @endfor
                </select>
                <p style="font-size:12px;color:#5f6368;margin-top:9px;line-height:1.5;">
                    La valeur actuelle est chargée automatiquement à l'ouverture de cette modale.
                </p>
            </div>
            <div style="padding:14px 24px;border-top:1px solid #e8eaed;display:flex;justify-content:flex-end;gap:10px;background:#fff;">
                <button type="button" onclick="closeQuotaModal()" class="btn btn-ghost">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modale de suspension ─────────────────────────────────────────────── --}}
<div id="suspend-modal" style="display:none;position:fixed;inset:0;background:rgba(32,33,36,.42);backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);z-index:1000;align-items:center;justify-content:center;padding:18px;">
    <div style="background:#fff;border:1px solid #dadce0;border-radius:28px;width:100%;max-width:520px;box-shadow:0 10px 24px rgba(60,64,67,.28),0 1px 3px rgba(60,64,67,.2);overflow:hidden;">
        <div style="padding:22px 24px 14px;border-bottom:1px solid #e8eaed;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:12px;background:#fef7e0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="#d97706" stroke-width="1.5"><rect x="4" y="2" width="3" height="12" rx="1"/><rect x="9" y="2" width="3" height="12" rx="1"/></svg>
                </div>
                <div>
                    <div style="font-weight:500;font-size:20px;line-height:1.2;color:#202124;">Suspendre l'association</div>
                    <div id="suspend-modal-name" style="font-size:13px;color:#5f6368;margin-top:2px;"></div>
                </div>
            </div>
        </div>
        <form id="suspend-form" action="{{ route('association.suspend') }}" method="POST">
            @csrf
            <input type="hidden" name="name" id="suspend-input-name">
            <div style="padding:20px 24px;">
                <p style="font-size:13px;color:#5f6368;margin:0 0 16px;line-height:1.5;">
                    Les visiteurs seront automatiquement redirigés vers
                    <strong style="color:#202124;">https://monasso.eu/errors/suspended-instance</strong>.
                    Aucun fichier ne sera supprimé.
                </p>
                <div class="form-group" style="margin:0;">
                    <label style="font-size:13px;font-weight:500;color:#3c4043;">Raison de la suspension <span style="color:#d93025;">*</span></label>
                    <textarea id="suspend-reason" name="reason" rows="3" required minlength="5" maxlength="500"
                        placeholder="Ex : Non-respect des conditions d'utilisation, absence de paiement…"
                        style="width:100%;padding:11px 12px;border:1px solid #dadce0;border-radius:12px;background:#fff;color:#202124;font-size:14px;line-height:1.5;resize:vertical;font-family:inherit;box-sizing:border-box;margin-top:6px;"></textarea>
                    <div style="display:flex;justify-content:flex-end;margin-top:4px;">
                        <span id="suspend-char-count" style="font-size:11px;color:#5f6368;">0 / 500</span>
                    </div>
                </div>
                <div style="margin-top:14px;padding:12px 14px;background:#fef7e0;border:1px solid #fce8b2;border-radius:12px;display:flex;gap:10px;align-items:flex-start;">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="#d97706" stroke-width="1.5" style="flex-shrink:0;margin-top:1px;"><circle cx="8" cy="8" r="6.5"/><line x1="8" y1="5" x2="8" y2="8.5"/><circle cx="8" cy="11" r=".5" fill="#d97706"/></svg>
                    <p style="font-size:12px;color:#8a5b00;margin:0;line-height:1.5;">La raison sera enregistrée et visible dans le panel. Elle ne sera <strong>pas</strong> affichée aux visiteurs.</p>
                </div>
            </div>
            <div style="padding:14px 24px;border-top:1px solid #e8eaed;display:flex;justify-content:flex-end;gap:10px;background:#fff;">
                <button type="button" onclick="closeSuspendModal()" class="btn btn-ghost">Annuler</button>
                <button type="submit" id="suspend-submit" class="btn btn-warning" style="background:#d97706;color:#fff;border-color:#d97706;" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:5px;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    Suspendre
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var quotaModal     = document.getElementById('quota-modal');
    var quotaInputName = document.getElementById('quota-input-name');
    var quotaModalName = document.getElementById('quota-modal-name');
    var quotaSelect    = document.getElementById('quota-gb');

    var modal        = document.getElementById('suspend-modal');
    var inputName    = document.getElementById('suspend-input-name');
    var modalName    = document.getElementById('suspend-modal-name');
    var reasonTA     = document.getElementById('suspend-reason');
    var charCount    = document.getElementById('suspend-char-count');
    var submitBtn    = document.getElementById('suspend-submit');

    window.openQuotaModal = function (name, quotaGb) {
        quotaInputName.value = name;
        quotaModalName.textContent = name;
        quotaSelect.value = String(quotaGb || 10);
        quotaModal.style.display = 'flex';
    };

    window.closeQuotaModal = function () {
        quotaModal.style.display = 'none';
    };

    window.openSuspendModal = function (name) {
        inputName.value  = name;
        modalName.textContent = name;
        reasonTA.value   = '';
        charCount.textContent = '0 / 500';
        submitBtn.disabled = true;
        modal.style.display = 'flex';
        setTimeout(function () { reasonTA.focus(); }, 50);
    };

    window.closeSuspendModal = function () {
        modal.style.display = 'none';
    };

    reasonTA.addEventListener('input', function () {
        var len = reasonTA.value.length;
        charCount.textContent = len + ' / 500';
        submitBtn.disabled = len < 5;
    });

    quotaModal.addEventListener('click', function (e) {
        if (e.target === quotaModal) closeQuotaModal();
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeSuspendModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && quotaModal.style.display === 'flex') closeQuotaModal();
        if (e.key === 'Escape' && modal.style.display === 'flex') closeSuspendModal();
    });
})();
</script>

@endsection
