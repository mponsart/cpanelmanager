<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private LoggerService $logger) {}

    public function index(Request $request)
    {
        $recentLogs = ActionLog::with('user')
                               ->orderByDesc('created_at')
                               ->limit(15)
                               ->get();

        $stats = [
            'total_actions' => ActionLog::count(),
            'errors'        => ActionLog::where('status', 'error')->count(),
            'denied'        => ActionLog::where('status', 'denied')->count(),
            'today'         => ActionLog::whereDate('created_at', today())->count(),
        ];

        return view('dashboard', compact('recentLogs', 'stats'));
    }
}
