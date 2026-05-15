<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\FuzzyIndicator;
use App\Models\KnnTrainingProfile;
use App\Models\LearningMaterial;
use App\Models\SimulationSession;
use App\Models\User;
use App\Models\UserAwarenessScore;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalCases = CyberCase::count();
        $totalIndicators = FuzzyIndicator::count();
        $totalMaterials = LearningMaterial::count();
        $totalKnn = KnnTrainingProfile::count();
        $totalSessions = SimulationSession::count();
        $completedSessions = SimulationSession::where('status', 'completed')->count();
        $avgScore = round(SimulationSession::where('status', 'completed')->avg('total_score') ?? 0, 2);

        $levelDistribution = UserAwarenessScore::selectRaw('awareness_level, COUNT(*) as total')
            ->groupBy('awareness_level')
            ->pluck('total', 'awareness_level')
            ->toArray();

        $latestSessions = SimulationSession::with(['user', 'awarenessScore'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalCases', 'totalIndicators', 'totalMaterials', 'totalKnn',
            'totalSessions', 'completedSessions', 'avgScore', 'levelDistribution', 'latestSessions'
        ));
    }
}
