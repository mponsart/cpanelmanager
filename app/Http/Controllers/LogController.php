<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionLog::with('user')->orderByDesc('created_at');

        if ($module = $request->input('module')) {
            $query->where('module', $module);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', (int) $userId);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs    = $query->paginate(50)->withQueryString();
        $modules = ActionLog::distinct()->pluck('module')->sort()->values();

        return view('logs.index', compact('logs', 'modules'));
    }

    public function show(ActionLog $log)
    {
        return view('logs.show', compact('log'));
    }
}
