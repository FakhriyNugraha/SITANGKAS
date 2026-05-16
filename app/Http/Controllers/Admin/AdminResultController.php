<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SimulationSession;
use App\Models\User;
use App\Services\KNNService;
use App\Services\LearningPathService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminResultController extends Controller
{
    public function index(Request $request, KNNService $knn): View
    {
        $query = User::where('role', 'user');
        if ($q = $request->get('q')) {
            $query->where(fn ($w) => $w->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%"));
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        $rows = $users->getCollection()->map(function (User $u) use ($knn) {
            $completed = SimulationSession::where('user_id', $u->id)->where('status', 'completed');
            $assess = $knn->classifyForUser($u->id);
            return [
                'user' => $u,
                'sessions' => (clone $completed)->count(),
                'avg' => round((clone $completed)->avg('total_score') ?? 0, 1),
                'level' => $assess['label'] ?? 'Belum dinilai',
            ];
        });

        return view('admin.results.index', [
            'users' => $users,
            'rows' => $rows,
            'filters' => $request->only('q'),
        ]);
    }

    public function show(User $user, KNNService $knn, LearningPathService $path): View
    {
        abort_if($user->role !== 'user', 404);

        $sessions = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('answers:id,session_id,is_correct')
            ->latest('finished_at')
            ->get();

        $assess = $knn->classifyForUser($user->id);

        // Ringkasan per level (kategori) yang relevan untuk stakeholder.
        $levels = [];
        foreach (LearningPathService::PATH as [$cat, $title, $icon, $color]) {
            $catSessions = $sessions->where('selected_category', $cat);
            $levels[] = [
                'title' => $title,
                'attempts' => $catSessions->count(),
                'best' => $catSessions->count() ? round($catSessions->max('total_score')) : null,
            ];
        }

        return view('admin.results.show', [
            'user' => $user,
            'sessions' => $sessions,
            'assess' => $assess,
            'levels' => $levels,
            'avg' => round($sessions->avg('total_score') ?? 0, 1),
        ]);
    }
}
