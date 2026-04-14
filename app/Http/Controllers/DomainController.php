<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $result     = $this->cpanel->call('DomainInfo', 'list_domains');

            $domains    = $result['data']['addon_domains'] ?? [];
            $subdomains = $result['data']['sub_domains'] ?? [];

            $this->logger->success('list_domains', 'domain', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_domains', 'domain', $e->getMessage(), null, [], $request);
            $domains = $subdomains = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('domain.index', compact('domains', 'subdomains'));
    }

    public function createAddon(Request $request)
    {
        $data = $request->validate([
            'newdomain'  => ['required', 'string', 'max:253'],
            'subdomain'  => ['required', 'string', 'regex:/^[a-zA-Z0-9\-]+$/', 'max:63'],
            'dir'        => ['required', 'string', 'max:255'],
        ]);

        try {
            $this->cpanel->callApi2('AddonDomain', 'addaddondomain', [
                'newdomain' => $data['newdomain'],
                'subdomain' => $data['subdomain'],
                'dir'       => $data['dir'],
            ]);

            $this->logger->success('create_addon_domain', 'domain', $data['newdomain'], $data, $request);

            return redirect()->route('domain.index')->with('success', 'Domaine addon créé : ' . e($data['newdomain']));
        } catch (\Throwable $e) {
            $this->logger->error('create_addon_domain', 'domain', $e->getMessage(), $data['newdomain'], $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function createSubdomain(Request $request)
    {
        $data = $request->validate([
            'domain'     => ['required', 'string', 'regex:/^[a-zA-Z0-9\-]+$/', 'max:63'],
            'rootdomain' => ['required', 'string', 'max:253'],
            'dir'        => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->cpanel->call('SubDomain', 'addsubdomain', [
                'domain'     => $data['domain'],
                'rootdomain' => $data['rootdomain'],
                'dir'        => $data['dir'] ?? '',
            ]);

            $target = $data['domain'] . '.' . $data['rootdomain'];

            $this->logger->success('create_subdomain', 'domain', $target, $data, $request);

            return redirect()->route('domain.index')->with('success', 'Sous-domaine créé : ' . e($target));
        } catch (\Throwable $e) {
            $this->logger->error('create_subdomain', 'domain', $e->getMessage(), null, $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }
}
