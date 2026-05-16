<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\SimulationSession;
use App\Models\SimulationSetting;
use App\Services\LearningPathService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(Request $request, LearningPathService $path): View
    {
        return view('user.levels.index', [
            'levels' => $path->forUser($request->user()),
            'progress' => $path->progress($request->user()),
        ]);
    }

    public function show(Request $request, string $category, LearningPathService $path): View|RedirectResponse
    {
        $level = $path->levelFor($request->user(), $category);
        abort_if(! $level, 404);

        if ($level['status'] === 'locked') {
            return redirect()->route('user.levels.index')
                ->with('status', 'Selesaikan level sebelumnya dulu untuk membuka level ini.');
        }

        return view('user.levels.show', ['level' => $level]);
    }

    public function start(Request $request, string $category, LearningPathService $path): RedirectResponse
    {
        $level = $path->levelFor($request->user(), $category);
        abort_if(! $level, 404);

        if ($level['status'] === 'locked') {
            return redirect()->route('user.levels.index')
                ->with('status', 'Level ini masih terkunci.');
        }

        $cases = CyberCase::where('is_active', true)
            ->where('category', $category)
            ->orderBy('id')
            ->limit(3)
            ->pluck('id')
            ->all();

        if (empty($cases)) {
            return redirect()->route('user.levels.index')
                ->with('status', 'Belum ada latihan untuk level ini.');
        }

        $session = SimulationSession::create([
            'user_id' => $request->user()->id,
            'mode' => 'category',
            'selected_category' => $category,
            'total_cases' => count($cases),
            'case_order' => $cases,
            'current_index' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('user.simulations.show', $session);
    }
}
