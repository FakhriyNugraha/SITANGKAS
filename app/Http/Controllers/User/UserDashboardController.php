<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\LearningMaterial;
use App\Models\SimulationSession;
use App\Services\KNNService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(Request $request, RecommendationService $rec, KNNService $knn): View
    {
        $user = $request->user();

        $totalSessions = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')->count();

        $averageScore = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')->avg('total_score') ?? 0;

        // Tingkat kemampuan keseluruhan (hasil algoritma diterjemahkan ramah).
        $assessment = $knn->classifyForUser($user->id);

        $latestSession = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')->latest('finished_at')->first();

        if ($latestSession) {
            $weak = $rec->findWeakCategories($user->id, $latestSession->id);
            $recommendations = $rec->recommendMaterials(
                $weak,
                $assessment['level'] ?? 'beginner',
                $user->id
            );
        } else {
            $recommendations = LearningMaterial::where('is_active', true)
                ->limit(3)->get()->all();
        }

        return view('user.dashboard', [
            'user' => $user,
            'totalSessions' => $totalSessions,
            'averageScore' => round($averageScore, 1),
            'assessment' => $assessment,
            'recommendations' => $recommendations,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }
}
