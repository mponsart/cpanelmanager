@extends('layouts.app')

@section('title', 'Terminal')
@section('page-title', 'Terminal SSH')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@5.3.0/css/xterm.min.css">
<style>
    .terminal-card {
        background: #0f172a;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .terminal-titlebar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: rgba(255,255,255,0.04);
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }

    .terminal-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .terminal-dot.red    { background: #ef4444; }
    .terminal-dot.yellow { background: #eab308; }
    .terminal-dot.green  { background: #22c55e; }

    .terminal-cwd {
        margin-left: auto;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        color: rgba(148,163,184,0.7);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
    }

    #terminal-container {
        padding: 8px;
        background: #0f172a;
        min-height: 400px;
    }

    .terminal-status {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.02);
        border-top: 1px solid rgba(255,255,255,0.07);
        font-size: 11px;
        color: rgba(148,163,184,0.6);
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
    }

    .status-dot.disconnected { background: #ef4444; }
    .status-dot.loading      { background: #eab308; animation: blink 1s infinite; }

    @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Terminal SSH</h1>
</div>

<div class="terminal-card">
    <div class="terminal-titlebar">
        <span class="terminal-dot red"></span>
        <span class="terminal-dot yellow"></span>
        <span class="terminal-dot green"></span>
        <span class="terminal-cwd" id="terminal-cwd">~</span>
    </div>
    <div id="terminal-container"></div>
    <div class="terminal-status">
        <span class="status-dot" id="status-dot"></span>
        <span id="status-label">Connexion en cours…</span>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xterm@5.3.0/lib/xterm.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.min.js"></script>
<script>
(function () {
    var CSRF  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var EXEC  = '{{ route('terminal.exec') }}';

    var term = new Terminal({
        cursorBlink:      true,
        fontFamily:       'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace',
        fontSize:         13,
        theme: {
            background:    '#0f172a',
            foreground:    '#e2e8f0',
            cursor:        '#60a5fa',
            selectionBackground: 'rgba(96,165,250,0.3)',
            black:         '#1e293b',
            red:           '#f87171',
            green:         '#4ade80',
            yellow:        '#facc15',
            blue:          '#60a5fa',
            magenta:       '#c084fc',
            cyan:          '#22d3ee',
            white:         '#f8fafc',
            brightBlack:   '#475569',
            brightRed:     '#fca5a5',
            brightGreen:   '#86efac',
            brightYellow:  '#fde047',
            brightBlue:    '#93c5fd',
            brightMagenta: '#d8b4fe',
            brightCyan:    '#67e8f9',
            brightWhite:   '#ffffff',
        },
        convertEol: false,
        scrollback:  2000,
    });

    var fitAddon = new FitAddon.FitAddon();
    term.loadAddon(fitAddon);
    term.open(document.getElementById('terminal-container'));
    fitAddon.fit();

    window.addEventListener('resize', function () { fitAddon.fit(); });

    var cwd         = '~';
    var inputBuffer = '';
    var history     = [];
    var historyIdx  = -1;
    var busy        = false;
    var controller  = null;

    var dotEl    = document.getElementById('status-dot');
    var labelEl  = document.getElementById('status-label');
    var cwdEl    = document.getElementById('terminal-cwd');

    function setStatus(state, text) {
        dotEl.className = 'status-dot' + (state !== 'ok' ? ' ' + state : '');
        labelEl.textContent = text;
    }

    function prompt() {
        term.write('\r\n\x1b[32m' + cwd + '\x1b[0m \x1b[34m$\x1b[0m ');
        cwdEl.textContent = cwd;
    }

    function execCommand(cmd) {
        if (!cmd.trim()) { prompt(); return; }

        history.unshift(cmd);
        historyIdx = -1;

        if (cmd.trim() === 'clear') {
            term.clear();
            prompt();
            return;
        }

        busy = true;
        setStatus('loading', 'Exécution…');

        controller = new AbortController();

        fetch(EXEC, {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     CSRF,
            },
            body: JSON.stringify({ command: cmd, cwd: cwd }),
            signal: controller.signal,
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.output) {
                term.write('\r\n' + data.output.replace(/\n/g, '\r\n'));
            }
            if (data.error) {
                term.write('\r\n\x1b[31m' + data.error.replace(/\n/g, '\r\n') + '\x1b[0m');
            }
            if (data.cwd) {
                cwd = data.cwd;
            }
            setStatus('ok', 'Connecté — ' + '{{ config('ssh.host') ?: 'SSH non configuré' }}');
            prompt();
        })
        .catch(function (err) {
            if (err.name === 'AbortError') return;
            term.write('\r\n\x1b[31mErreur réseau : ' + err.message + '\x1b[0m');
            setStatus('disconnected', 'Erreur');
            prompt();
        })
        .finally(function () { controller = null; busy = false; });
    }

    term.onData(function (data) {
        // Ctrl+C — abort the in-flight request even when busy
        if (data === '\x03') {
            term.write('^C');
            inputBuffer = '';
            if (controller) {
                controller.abort();
                controller = null;
            }
            busy = false;
            setStatus('ok', 'Connecté — ' + '{{ config('ssh.host') ?: 'SSH non configuré' }}');
            prompt();
            return;
        }

        if (busy) return;

        var code = data.charCodeAt(0);

        // Enter
        if (data === '\r') {
            term.write('\r\n');
            var cmd = inputBuffer;
            inputBuffer = '';
            execCommand(cmd);
            return;
        }

        // Ctrl+L
        if (data === '\x0c') {
            term.clear();
            inputBuffer = '';
            prompt();
            return;
        }

        // Backspace
        if (data === '\x7f' || data === '\b') {
            if (inputBuffer.length > 0) {
                inputBuffer = inputBuffer.slice(0, -1);
                term.write('\b \b');
            }
            return;
        }

        // Arrow Up
        if (data === '\x1b[A') {
            if (history.length > 0 && historyIdx < history.length - 1) {
                historyIdx++;
                var prev = history[historyIdx] || '';
                for (var i = 0; i < inputBuffer.length; i++) term.write('\b \b');
                inputBuffer = prev;
                term.write(inputBuffer);
            }
            return;
        }

        // Arrow Down
        if (data === '\x1b[B') {
            if (historyIdx > 0) {
                historyIdx--;
                var next = history[historyIdx] || '';
                for (var j = 0; j < inputBuffer.length; j++) term.write('\b \b');
                inputBuffer = next;
                term.write(inputBuffer);
            } else if (historyIdx === 0) {
                historyIdx = -1;
                for (var k = 0; k < inputBuffer.length; k++) term.write('\b \b');
                inputBuffer = '';
            }
            return;
        }

        // Ignore other escape sequences
        if (code === 27) return;

        // Printable character
        if (code >= 32) {
            inputBuffer += data;
            term.write(data);
        }
    });

    // Welcome message
    term.writeln('\x1b[34m _____ _____ _   _ \x1b[0m');
    term.writeln('\x1b[34m/ ____|  __ \\ \\ | |\x1b[0m');
    term.writeln('\x1b[34m| (__ | |  | |  \\| |\x1b[0m');
    term.writeln('\x1b[34m \\___ \\| |  | | . \` |\x1b[0m');
    term.writeln('\x1b[34m____) | |__| | |\\  |\x1b[0m');
    term.writeln('\x1b[34m|_____/|_____/|_| \\_|\x1b[0m');
    term.writeln('');
    term.writeln('\x1b[2mTerminal SSH — Groupe Speed Cloud\x1b[0m');
    term.writeln('\x1b[2mTapez vos commandes ci-dessous.\x1b[0m');

    @if(empty(config('ssh.key_path')))
    term.writeln('');
    term.writeln('\x1b[33m⚠  Terminal non configuré.\x1b[0m');
    term.writeln('\x1b[2mDéfinissez SSH_HOST, SSH_USERNAME et SSH_KEY_PATH dans .env.\x1b[0m');
    setStatus('disconnected', 'Non configuré — voir .env');
    @else
    setStatus('ok', 'Connecté — {{ config('ssh.host') }}');
    @endif

    prompt();
})();
</script>
@endpush
