<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $dbResult   = $this->cpanel->call('Mysql', 'list_databases');
            $userResult = $this->cpanel->call('Mysql', 'list_users');

            $databases = $dbResult['data'] ?? [];
            $dbUsers   = $userResult['data'] ?? [];

            usort($databases, fn($a, $b) => strcasecmp($a['database'] ?? '', $b['database'] ?? ''));
            usort($dbUsers, fn($a, $b) => strcasecmp($a['user'] ?? '', $b['user'] ?? ''));

            $this->logger->success('list_databases', 'database', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_databases', 'database', $e->getMessage(), null, [], $request);
            $databases = $dbUsers = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('database.index', compact('databases', 'dbUsers'));
    }

    public function createDatabase(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:64'],
        ]);

        try {
            $this->cpanel->call('Mysql', 'create_database', ['name' => $data['name']]);

            $this->logger->success('create_database', 'database', $data['name'], $data, $request);

            return redirect()->route('database.index')->with('success', 'Base de données créée : ' . e($data['name']));
        } catch (\Throwable $e) {
            $this->logger->error('create_database', 'database', $e->getMessage(), $data['name'], $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:16'],
            'password' => ['required', 'string', 'min:12', 'max:255'],
        ]);

        try {
            $this->cpanel->call('Mysql', 'create_user', [
                'name'     => $data['name'],
                'password' => $data['password'],
            ]);

            $this->logger->success('create_db_user', 'database', $data['name'], ['name' => $data['name']], $request);

            return redirect()->route('database.index')->with('success', 'Utilisateur MySQL créé : ' . e($data['name']));
        } catch (\Throwable $e) {
            $this->logger->error('create_db_user', 'database', $e->getMessage(), $data['name'], [], $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function assignPrivileges(Request $request)
    {
        $data = $request->validate([
            'database'   => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:64'],
            'dbuser'     => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:16'],
            'privileges' => ['required', 'string', 'max:255'],
        ]);

        try {
            $this->cpanel->call('Mysql', 'set_privileges_on_database', [
                'user'       => $data['dbuser'],
                'database'   => $data['database'],
                'privileges' => $data['privileges'],
            ]);

            $this->logger->success('assign_db_privileges', 'database', $data['database'], $data, $request);

            return redirect()->route('database.index')->with('success', 'Privilèges assignés.');
        } catch (\Throwable $e) {
            $this->logger->error('assign_db_privileges', 'database', $e->getMessage(), $data['database'], $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }
}
