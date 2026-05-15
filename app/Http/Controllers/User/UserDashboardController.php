<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\LearningMaterial;
use App\Models\SimulationSession;
use App\Models\UserAwarenessScore;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(Request $request, RecommendationService $rec): View
    {
        $user = $request->user();
        $latestSession = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('finished_at')
            ->first();

        $latestAwareness = UserAwarenessScore::where('user_id', $user->id)
            ->latest()
            ->first();

        $totalSessions = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $averageScore = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->avg('total_score') ?? 0;

        $recommendations = [];
        if ($latestSession && $latestAwareness) {
            $weak = $rec->findWeakCategories($user->id, $latestSession->id);
            $recommendations = $rec->recommendMaterials($weak, $latestAwareness->awareness_level, $user->id);
        } else {
            $recommendations = LearningMaterial::where('is_active', true)
                ->where('target_level', 'beginner')
                ->limit(3)
                ->get()
                ->all();
        }

        $categoryScores = $latestAwareness?->category_scores ?? [];

        return view('user.dashboard', [
            'user' => $user,
            'latestSession' => $latestSession,
            'latestAwareness' => $latestAwareness,
            'totalSessions' => $totalSessions,
            'averageScore' => round($averageScore, 2),
            'recommendations' => $recommendations,
            'categoryScores' => $categoryScores,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }
}
