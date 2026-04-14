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

            $this->logger->success('view_stats', 'stats', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('view_stats', 'stats', $e->getMessage(), null, [], $request);
            $diskUsage = $bandwidth = $stats = [];
            session()->flash('error', e($e->getMessage()));
        }

        return view('stats.index', compact('diskUsage', 'bandwidth', 'stats'));
    }
}
