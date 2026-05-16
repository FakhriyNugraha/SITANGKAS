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

        $setting = SimulationSetting::current();

        // Kasus inti level ini, diurut dari termudah ke tersulit.
        $core = CyberCase::where('is_active', true)
            ->where('category', $category)
            ->orderByRaw("FIELD(difficulty_level,'mudah','sedang','sulit')")
            ->orderBy('id')
            ->limit(max($setting->default_case_count - 1, 1))
            ->pluck('id')
            ->all();

        // Sisipkan 1 kasus pembanding agar tiap level punya variasi
        // (ada yang memang aman & ada yang memang penipuan).
        if ($category === 'legitimate') {
            $extra = CyberCase::where('is_active', true)
                ->where('category', '!=', 'legitimate')
                ->where('difficulty_level', 'mudah')
                ->inRandomOrder()->value('id');
        } else {
            $extra = CyberCase::where('is_active', true)
                ->where('category', 'legitimate')
                ->inRandomOrder()->value('id');
        }

        $cases = collect($core);
        if ($extra) {
            // taruh kasus pembanding di tengah supaya tidak ketebak
            $pos = intdiv($cases->count(), 2);
            $cases->splice($pos, 0, [$extra]);
        }

        if ($cases->isEmpty()) {
            return redirect()->route('user.levels.index')
                ->with('status', 'Belum ada latihan untuk level ini.');
        }

        $session = SimulationSession::create([
            'user_id' => $request->user()->id,
            'mode' => 'category',
            'selected_category' => $category,
            'total_cases' => $cases->count(),
            'case_order' => $cases->all(),
            'current_index' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('user.simulations.show', $session);
    }
}
