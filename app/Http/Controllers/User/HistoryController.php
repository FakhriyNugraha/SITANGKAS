<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $sessions = SimulationSession::with('awarenessScore')
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(12);

        return view('user.history.index', [
            'sessions' => $sessions,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function show(Request $request, SimulationSession $session): View
    {
        abort_if($session->user_id !== $request->user()->id, 403);

        $session->load(['answers.cyberCase', 'awarenessScore']);

        return view('user.history.show', [
            'session' => $session,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }
}
