<?php

namespace App\Http\Controllers;

use App\Services\CpanelService;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        private CpanelService $cpanel,
        private LoggerService $logger
    ) {}

    public function index(Request $request)
    {
        try {
            $diskResult  = $this->cpanel->call('Quota', 'get_quota_info');
            $bwResult    = $this->cpanel->call('Bandwidth', 'query', [
                'grouping' => 'domain',
            ]);
            $statsResult = $this->cpanel->call('StatsBar', 'get_stats', [
                'display' => implode('|', [
                    'bandwidthusage',
                    'diskusage',
                    'emailaccounts',
                    'mysqldatabases',
                    'ftpaccounts',
                    'subdomains',
                ]),
            ]);

            $diskUsage = $diskResult['data'] ?? [];
            $bandwidth = $bwResult['data'] ?? [];
            $stats     = $statsResult['data'] ?? [];

            // Tri bande passante du plus gros au plus petit
            if (is_array($bandwidth)) {
                arsort($bandwidth);
            }

            $this->logger->success('view_stats', 'stats', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('view_stats', 'stats', $e->getMessage(), null, [], $request);
            $diskUsage = $bandwidth = $stats = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('stats.index', compact('diskUsage', 'bandwidth', 'stats'));
    }

    public function domainDetail(Request $request, string $domain)
    {
        // Validation basique du domaine
        if (! preg_match('/^[a-zA-Z0-9._-]+\.[a-zA-Z]{2,}$/', $domain)) {
            abort(404);
        }

        try {
            $bwResult = $this->cpanel->call('Bandwidth', 'query', [
                'grouping'  => 'protocol',
                'domains'   => $domain,
            ]);

            $protocols = $bwResult['data'] ?? [];

            // Récupérer la consommation totale
            $totalResult = $this->cpanel->call('Bandwidth', 'query', [
                'grouping' => 'domain',
            ]);
            $allBandwidth = $totalResult['data'] ?? [];
            $totalBytes   = $allBandwidth[$domain] ?? 0;

            if (is_array($protocols)) {
                arsort($protocols);
            }

            $this->logger->success('view_domain_bandwidth', 'stats', $domain, ['domain' => $domain], $request);
        } catch (\Throwable $e) {
            $this->logger->error('view_domain_bandwidth', 'stats', $e->getMessage(), $domain, ['domain' => $domain], $request);
            $protocols  = [];
            $totalBytes = 0;
            session()->flash('error', e($e->getMessage()));
        }

        return view('stats.domain', compact('domain', 'protocols', 'totalBytes'));
    }
}
