<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class CronController extends Controller
{
    // Expression cron minimale : validation basique
    private const CRON_PATTERN = '/^(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)$/';

    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $result = $this->cpanel->call('Cron', 'list_cron');
            $jobs   = $result['data'] ?? [];

            usort($jobs, fn($a, $b) => strcasecmp($a['command'] ?? '', $b['command'] ?? ''));

            $this->logger->success('list_cron', 'cron', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_cron', 'cron', $e->getMessage(), null, [], $request);
            $jobs = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('cron.index', compact('jobs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'minute'    => ['required', 'string', 'max:50'],
            'hour'      => ['required', 'string', 'max:50'],
            'day'       => ['required', 'string', 'max:50'],
            'month'     => ['required', 'string', 'max:50'],
            'weekday'   => ['required', 'string', 'max:50'],
            'command'   => ['required', 'string', 'max:1024'],
        ]);

        // Validation anti-injection commande
        if (preg_match('/[;&|`$><]/', $data['command'])) {
            return back()->withInput()->with('error', 'La commande contient des caractères non autorisés.');
        }

        try {
            $this->cpanel->call('Cron', 'add_line', [
                'minute'  => $data['minute'],
                'hour'    => $data['hour'],
                'day'     => $data['day'],
                'month'   => $data['month'],
                'weekday' => $data['weekday'],
                'command' => $data['command'],
            ]);

            $cronExpr = implode(' ', [$data['minute'], $data['hour'], $data['day'], $data['month'], $data['weekday']]);

            $this->logger->success('create_cron', 'cron', $cronExpr, [
                'expression' => $cronExpr,
                'command'    => $data['command'],
            ], $request);

            return redirect()->route('cron.index')->with('success', 'Tâche cron créée.');
        } catch (\Throwable $e) {
            $this->logger->error('create_cron', 'cron', $e->getMessage(), null, $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'line' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $this->cpanel->call('Cron', 'remove_line', ['linekey' => $data['line']]);

            $this->logger->success('delete_cron', 'cron', (string) $data['line'], $data, $request);

            return redirect()->route('cron.index')->with('success', 'Tâche cron supprimée.');
        } catch (\Throwable $e) {
            $this->logger->error('delete_cron', 'cron', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }
}
