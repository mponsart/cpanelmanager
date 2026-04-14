<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class FtpController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $result   = $this->cpanel->call('Ftp', 'list_ftp_with_disk');
            $accounts = $result['data'] ?? [];

            usort($accounts, fn($a, $b) => strcasecmp($a['user'] ?? '', $b['user'] ?? ''));

            $this->logger->success('list_ftp', 'ftp', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_ftp', 'ftp', $e->getMessage(), null, [], $request);
            $accounts = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('ftp.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user'     => ['required', 'string', 'regex:/^[a-zA-Z0-9_\-\.]+$/', 'max:32'],
            'domain'   => ['required', 'string', 'max:253'],
            'password' => ['required', 'string', 'min:12', 'max:255'],
            'quota'    => ['required', 'integer', 'min:0', 'max:10240'],
            'homedir'  => ['required', 'string', 'max:255'],
        ]);

        try {
            $this->cpanel->call('Ftp', 'add_ftp', [
                'user'     => $data['user'],
                'domain'   => $data['domain'],
                'password' => $data['password'],
                'quota'    => $data['quota'],
                'homedir'  => $data['homedir'],
            ]);

            $target = $data['user'] . '@' . $data['domain'];

            $this->logger->success('create_ftp', 'ftp', $target, [
                'user'    => $data['user'],
                'domain'  => $data['domain'],
                'quota'   => $data['quota'],
                'homedir' => $data['homedir'],
            ], $request);

            return redirect()->route('ftp.index')->with('success', 'Compte FTP créé : ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('create_ftp', 'ftp', $e->getMessage(), null, [], $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'user'        => ['required', 'string', 'max:32'],
            'domain'      => ['required', 'string', 'max:253'],
            'destroy_data'=> ['nullable', 'boolean'],
        ]);

        try {
            $this->cpanel->call('Ftp', 'delete_ftp', [
                'user'         => $data['user'],
                'domain'       => $data['domain'],
                'destroy'      => $data['destroy_data'] ? 1 : 0,
            ]);

            $target = $data['user'] . '@' . $data['domain'];

            $this->logger->success('delete_ftp', 'ftp', $target, [
                'user'         => $data['user'],
                'domain'       => $data['domain'],
                'destroy_data' => $data['destroy_data'] ?? false,
            ], $request);

            return redirect()->route('ftp.index')->with('success', 'Compte FTP supprimé : ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('delete_ftp', 'ftp', $e->getMessage(), null, [], $request);
            return back()->with('error', e($e->getMessage()));
        }
    }
}
