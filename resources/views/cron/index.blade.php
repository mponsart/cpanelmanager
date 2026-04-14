@extends('layouts.app')

@section('title', 'Cron Jobs')
@section('page-title', 'Gestion des tâches cron')

@section('content')

<div class="card">
    <div class="card-title">Créer une tâche cron</div>
    <form action="{{ route('cron.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Raccourci fréquence</label>
            <select id="cron-preset">
                <option value="">— Choisir un modèle —</option>
                <option value="* * * * *">Chaque minute</option>
                <option value="*/5 * * * *">Toutes les 5 minutes</option>
                <option value="*/15 * * * *">Toutes les 15 minutes</option>
                <option value="*/30 * * * *">Toutes les 30 minutes</option>
                <option value="0 * * * *">Chaque heure</option>
                <option value="0 */6 * * *">Toutes les 6 heures</option>
                <option value="0 0 * * *">Chaque jour à minuit</option>
                <option value="0 3 * * *">Chaque jour à 3h</option>
                <option value="0 0 * * 1">Chaque lundi à minuit</option>
                <option value="0 0 1 * *">Le 1er du mois à minuit</option>
            </select>
        </div>
        <div class="form-row" style="grid-template-columns: repeat(5, 1fr);">
            <div class="form-group">
                <label>Minute</label>
                <input type="text" name="minute" value="{{ old('minute', '*') }}" required maxlength="50">
                @error('minute')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Heure</label>
                <input type="text" name="hour"   value="{{ old('hour', '*') }}"   required maxlength="50">
                @error('hour')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Jour</label>
                <input type="text" name="day"    value="{{ old('day', '*') }}"    required maxlength="50">
                @error('day')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Mois</label>
                <input type="text" name="month"  value="{{ old('month', '*') }}"  required maxlength="50">
                @error('month')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Jour sem.</label>
                <input type="text" name="weekday" value="{{ old('weekday', '*') }}" required maxlength="50">
                @error('weekday')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label>Commande</label>
            <input type="text" name="command" value="{{ old('command') }}" required maxlength="1024" placeholder="/usr/bin/php /home/user/public_html/script.php">
            @error('command')<div class="form-error">{{ $message }}</div>@enderror
            <p class="text-muted text-sm mt-1">Les caractères <code>; & | ` $ &gt; &lt;</code> sont interdits.</p>
        </div>
        <div>
            <p class="text-muted text-sm mb-3">Raccourcis : <code>*</code> = toutes, <code>*/5</code> = toutes les 5, <code>1,15</code> = le 1er et 15ème</p>
            <button type="submit" class="btn btn-primary">Créer la tâche</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-title">Tâches cron ({{ count($jobs) }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Expression</th>
                    <th>Commande</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $index => $job)
                    <tr>
                        <td>
                            <span class="code">
                                {{ $job['minute'] ?? '*' }}
                                {{ $job['hour']   ?? '*' }}
                                {{ $job['day']    ?? '*' }}
                                {{ $job['month']  ?? '*' }}
                                {{ $job['weekday']?? '*' }}
                            </span>
                        </td>
                        <td class="text-muted text-sm" style="word-break: break-all;">{{ $job['command'] ?? '—' }}</td>
                        <td>
                            <form action="{{ route('cron.destroy') }}" method="POST" onsubmit="return confirm('Supprimer cette tâche cron ?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="line" value="{{ $job['linekey'] ?? $index }}">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted" style="text-align:center;padding:24px;">Aucune tâche cron.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('cron-preset').addEventListener('change', function() {
    if (!this.value) return;
    var parts = this.value.split(' ');
    ['minute','hour','day','month','weekday'].forEach(function(name, i) {
        var el = document.querySelector('input[name="' + name + '"]');
        if (el) el.value = parts[i] !== undefined ? parts[i] : '*';
    });
    this.value = '';
});
</script>
@endpush
