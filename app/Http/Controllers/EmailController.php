<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $result = $this->cpanel->call('Email', 'list_pops', [
                'domain' => config('cpanel.domain'),
            ]);

            $emails = $result['data'] ?? [];

            $this->logger->success('list_emails', 'email', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_emails', 'email', $e->getMessage(), null, [], $request);
            $emails = [];
            session()->flash('error', 'Impossible de récupérer la liste des e-mails : ' . e($e->getMessage()));
        }

        return view('email.index', compact('emails'));
    }

    public function create()
    {
        return view('email.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'string', 'regex:/^[a-zA-Z0-9._\-]+$/', 'max:64'],
            'domain'   => ['required', 'string', 'max:253'],
            'password' => ['required', 'string', 'min:12', 'max:255'],
            'quota'    => ['required', 'integer', 'min:0', 'max:10240'],
        ]);

        try {
            $this->cpanel->call('Email', 'add_pop', [
                'email'    => $data['email'],
                'domain'   => $data['domain'],
                'password' => $data['password'],
                'quota'    => $data['quota'],
            ]);

            $target = $data['email'] . '@' . $data['domain'];

            $this->logger->success('create_email', 'email', $target, [
                'email'  => $data['email'],
                'domain' => $data['domain'],
                'quota'  => $data['quota'],
            ], $request);

            return redirect()->route('email.index')->with('success', 'Adresse e-mail créée : ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('create_email', 'email', $e->getMessage(), $data['email'] . '@' . $data['domain'], $data, $request);
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . e($e->getMessage()));
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'email'  => ['required', 'string', 'max:64'],
            'domain' => ['required', 'string', 'max:253'],
        ]);

        try {
            $this->cpanel->call('Email', 'delete_pop', [
                'email'  => $data['email'],
                'domain' => $data['domain'],
            ]);

            $target = $data['email'] . '@' . $data['domain'];

            $this->logger->success('delete_email', 'email', $target, $data, $request);

            return redirect()->route('email.index')->with('success', 'Adresse e-mail supprimée : ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('delete_email', 'email', $e->getMessage(), $data['email'] . '@' . $data['domain'], $data, $request);
            return back()->with('error', 'Erreur lors de la suppression : ' . e($e->getMessage()));
        }
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'string', 'max:64'],
            'domain'   => ['required', 'string', 'max:253'],
            'password' => ['required', 'string', 'min:12', 'max:255'],
        ]);

        try {
            $this->cpanel->call('Email', 'passwd_pop', [
                'email'    => $data['email'],
                'domain'   => $data['domain'],
                'password' => $data['password'],
            ]);

            $target = $data['email'] . '@' . $data['domain'];

            $this->logger->success('reset_email_password', 'email', $target, [
                'email'  => $data['email'],
                'domain' => $data['domain'],
            ], $request);

            return redirect()->route('email.index')->with('success', 'Mot de passe réinitialisé pour ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('reset_email_password', 'email', $e->getMessage(), null, [], $request);
            return back()->with('error', 'Erreur : ' . e($e->getMessage()));
        }
    }

    public function forwarders(Request $request)
    {
        try {
            $result = $this->cpanel->call('Email', 'list_forwarders', [
                'domain' => config('cpanel.domain'),
            ]);

            $forwarders = $result['data'] ?? [];

            $this->logger->success('list_forwarders', 'email', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_forwarders', 'email', $e->getMessage(), null, [], $request);
            $forwarders = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('email.forwarders', compact('forwarders'));
    }

    public function addForwarder(Request $request)
    {
        $data = $request->validate([
            'address'     => ['required', 'string', 'max:255'],
            'forwardto'   => ['required', 'email', 'max:255'],
        ]);

        try {
            $this->cpanel->call('Email', 'add_forwarder', [
                'domain'    => config('cpanel.domain'),
                'email'     => $data['address'],
                'fwdopt'    => 'fwd',
                'fwdemail'  => $data['forwardto'],
            ]);

            $this->logger->success('add_forwarder', 'email', $data['address'], $data, $request);

            return redirect()->route('email.forwarders')->with('success', 'Redirection créée.');
        } catch (\Throwable $e) {
            $this->logger->error('add_forwarder', 'email', $e->getMessage(), null, $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }
}
