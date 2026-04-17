<?php

namespace App\Http\Controllers;

use App\Services\LoggerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function __construct(
        private LoggerService $logger
    ) {}

    public function index()
    {
        return view('terminal.index');
    }

    public function exec(Request $request): JsonResponse
    {
        $data = $request->validate([
            'command' => ['required', 'string', 'max:2048'],
            'cwd'     => ['nullable', 'string', 'max:1024'],
        ]);

        $command = $data['command'];
        $cwd     = $data['cwd'] ?? '~';

        $result = $this->executeViaSSH($cwd, $command);

        $this->logger->success('terminal_exec', 'terminal', $command, ['cwd' => $cwd], $request);

        return response()->json([
            'output'  => $result['output'],
            'error'   => $result['error'],
            'cwd'     => $result['cwd'],
            'success' => $result['success'],
        ]);
    }

    private function executeViaSSH(string $cwd, string $command): array
    {
        $host    = config('ssh.host');
        $port    = config('ssh.port', 22);
        $user    = config('ssh.username');
        $keyPath = config('ssh.key_path');

        if (empty($host) || empty($user) || empty($keyPath)) {
            return [
                'output'  => '',
                'error'   => 'Terminal SSH non configuré. Vérifiez SSH_HOST, SSH_USERNAME et SSH_KEY_PATH dans le fichier .env.',
                'cwd'     => $cwd,
                'success' => false,
            ];
        }

        if (! file_exists($keyPath)) {
            return [
                'output'  => '',
                'error'   => 'Clé SSH introuvable : ' . $keyPath,
                'cwd'     => $cwd,
                'success' => false,
            ];
        }

        // Build the command executed on the remote host:
        // cd to current directory, execute the command, then print the new pwd.
        // The full remote command is passed as a single escaped argument to SSH,
        // so shell metacharacters in $command are evaluated by the remote shell
        // (intentional for a terminal). The cwd is separately escaped.
        $remoteCmd = sprintf(
            'cd %s 2>/dev/null || true; %s; echo "__CWD__:$(pwd)"',
            escapeshellarg($cwd),
            $command
        );

        $sshCmd = sprintf(
            'ssh -o StrictHostKeyChecking=accept-new -o BatchMode=yes -o ConnectTimeout=10 -i %s -p %d %s@%s %s',
            escapeshellarg($keyPath),
            (int) $port,
            escapeshellarg($user),
            escapeshellarg($host),
            escapeshellarg($remoteCmd)
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($sshCmd, $descriptors, $pipes);

        if (! is_resource($process)) {
            return [
                'output'  => '',
                'error'   => 'Impossible d\'ouvrir la connexion SSH.',
                'cwd'     => $cwd,
                'success' => false,
            ];
        }

        fclose($pipes[0]);

        $timeout = 30;
        stream_set_timeout($pipes[1], $timeout);
        $stdout   = stream_get_contents($pipes[1]);
        $timedOut = stream_get_meta_data($pipes[1])['timed_out'];

        if ($timedOut) {
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_terminate($process);
            proc_close($process);

            return [
                'output'  => $stdout,
                'error'   => 'La commande a dépassé le délai d\'exécution (30 s).',
                'cwd'     => $cwd,
                'success' => false,
            ];
        }

        stream_set_timeout($pipes[2], $timeout);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        // Extract the new working directory from the marker line
        $newCwd = $cwd;
        if (preg_match('/__CWD__:(.+)$/m', $stdout, $matches)) {
            $newCwd = trim($matches[1]);
            $stdout = preg_replace('/__CWD__:.+$/m', '', $stdout);
            $stdout = rtrim($stdout);
        }

        return [
            'output'  => $stdout,
            'error'   => $stderr,
            'cwd'     => $newCwd,
            'success' => $exitCode === 0,
        ];
    }
}
