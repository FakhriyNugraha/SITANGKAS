<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminResultController extends Controller
{
    public function index(Request $request): View
    {
        $query = SimulationSession::with(['user', 'awarenessScore']);

        if ($level = $request->get('level')) {
            $query->whereHas('awarenessScore', fn($q) => $q->where('awareness_level', $level));
        }
        if ($status = $request->get('status')) $query->where('status', $status);
        if ($from = $request->get('from')) $query->whereDate('started_at', '>=', $from);
        if ($to = $request->get('to')) $query->whereDate('started_at', '<=', $to);
        if ($q = $request->get('q')) {
            $query->whereHas('user', fn($qq) => $qq->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%"));
        }

        return view('admin.results.index', [
            'sessions' => $query->latest('id')->paginate(15)->withQueryString(),
            'filters' => $request->only(['level', 'status', 'from', 'to', 'q']),
        ]);
    }

    public function show(SimulationSession $session): View
    {
        $session->load(['user', 'answers.cyberCase', 'awarenessScore']);

        return view('admin.results.show', [
            'session' => $session,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }
}
