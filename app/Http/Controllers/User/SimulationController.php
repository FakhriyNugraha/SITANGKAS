<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\SimulationSession;
use App\Models\SimulationSetting;
use App\Models\UserAnswer;
use App\Services\FuzzyMatchingService;
use App\Services\KNNService;
use App\Services\RecommendationService;
use App\Services\ScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SimulationController extends Controller
{
    public function show(Request $request, SimulationSession $simulation): View|RedirectResponse
    {
        $this->authorizeOwn($request, $simulation);

        if ($simulation->status === 'completed') {
            return redirect()->route('user.simulations.result', $simulation);
        }

        $order = $simulation->case_order ?? [];
        $idx = $simulation->current_index;
        if ($idx >= count($order)) {
            return $this->finalize($simulation);
        }

        $case = CyberCase::with('options')->find($order[$idx]);
        if (! $case) {
            return $this->finalize($simulation);
        }

        return view('user.simulations.case', [
            'session' => $simulation,
            'case' => $case,
            'currentNumber' => $idx + 1,
            'totalCases' => count($order),
            'startTime' => now()->timestamp,
        ]);
    }

    public function answer(Request $request, SimulationSession $simulation,
                           FuzzyMatchingService $fuzzy, ScoringService $scoring): RedirectResponse
    {
        $this->authorizeOwn($request, $simulation);
        abort_if($simulation->status === 'completed', 400);

        $data = $request->validate([
            'cyber_case_id' => ['required', 'exists:cyber_cases,id'],
            'selected_option_id' => ['required', 'exists:case_options,id'],
            'reason_text' => ['required', 'string', 'min:3', 'max:1000'],
            'answer_time_seconds' => ['nullable', 'integer', 'min:0'],
            'help_opened' => ['nullable', 'boolean'],
        ]);

        $case = CyberCase::with('options')->findOrFail($data['cyber_case_id']);
        $option = $case->options->firstWhere('id', $data['selected_option_id']);
        abort_if(! $option, 404);

        $isCorrect = (bool) $option->is_correct;
        $actionScore = $scoring->calculateActionScore($isCorrect);

        $ideal = $case->ideal_indicators ?? [];
        $matching = $fuzzy->detectIndicators($data['reason_text'], $ideal);
        $fuzzyScore = $fuzzy->calculateFuzzyScore($matching['detected'], $ideal);
        $caseScore = $scoring->calculateCaseScore($actionScore, $fuzzyScore);

        DB::transaction(function () use ($simulation, $data, $case, $option, $isCorrect, $actionScore, $fuzzyScore, $caseScore, $matching) {
            UserAnswer::create([
                'session_id' => $simulation->id,
                'user_id' => $simulation->user_id,
                'cyber_case_id' => $case->id,
                'selected_option_id' => $option->id,
                'selected_action_text' => $option->option_text,
                'reason_text' => $data['reason_text'],
                'is_correct' => $isCorrect,
                'action_score' => $actionScore,
                'fuzzy_score' => $fuzzyScore,
                'case_score' => $caseScore,
                'detected_indicators' => $matching['detected'],
                'missed_indicators' => $matching['missed'],
                'answer_time_seconds' => (int) ($data['answer_time_seconds'] ?? 0),
                'help_opened' => (bool) ($data['help_opened'] ?? false),
            ]);

            $simulation->update([
                'completed_cases' => $simulation->completed_cases + 1,
            ]);
        });

        return redirect()->route('user.simulations.feedback', [
            'simulation' => $simulation,
            'case' => $case->id,
        ]);
    }

    public function feedback(Request $request, SimulationSession $simulation, int $case): View
    {
        $this->authorizeOwn($request, $simulation);

        $answer = UserAnswer::with(['cyberCase', 'selectedOption'])
            ->where('session_id', $simulation->id)
            ->where('cyber_case_id', $case)
            ->latest()
            ->firstOrFail();

        return view('user.simulations.feedback', [
            'session' => $simulation,
            'answer' => $answer,
            'case' => $answer->cyberCase,
            'isLast' => ($simulation->current_index + 1) >= count($simulation->case_order ?? []),
        ]);
    }

    public function next(Request $request, SimulationSession $simulation): RedirectResponse
    {
        $this->authorizeOwn($request, $simulation);

        $simulation->update(['current_index' => $simulation->current_index + 1]);

        $order = $simulation->case_order ?? [];
        if ($simulation->current_index >= count($order)) {
            return $this->finalize($simulation);
        }

        return redirect()->route('user.simulations.show', $simulation);
    }

    public function result(Request $request, SimulationSession $simulation): View|RedirectResponse
    {
        $this->authorizeOwn($request, $simulation);

        if ($simulation->status !== 'completed') {
            return $this->finalize($simulation);
        }

        $awareness = $simulation->awarenessScore;
        $answers = $simulation->answers()->with('cyberCase')->get();

        $rec = app(RecommendationService::class);
        $weak = $rec->findWeakCategories($simulation->user_id, $simulation->id);
        $recommendations = $awareness
            ? $rec->recommendMaterials($weak, $awareness->awareness_level, $simulation->user_id)
            : [];
        $tutorSummary = $rec->generateTutorSummary($simulation->id);

        return view('user.simulations.result', [
            'session' => $simulation,
            'awareness' => $awareness,
            'answers' => $answers,
            'weakCategories' => $weak,
            'recommendations' => $recommendations,
            'tutorSummary' => $tutorSummary,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    protected function finalize(SimulationSession $session): RedirectResponse
    {
        $scoring = app(ScoringService::class);
        $knn = app(KNNService::class);

        $scoring->recalculateSessionTotal($session->id);
        $aggregates = $scoring->calculateCategoryScores($session->id);

        $userVector = $knn->buildUserVector($aggregates);
        $classification = $knn->classify($userVector);

        \App\Models\UserAwarenessScore::updateOrCreate(
            ['session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'phishing_score' => $aggregates['phishing_score'],
                'otp_score' => $aggregates['otp_score'],
                'password_score' => $aggregates['password_score'],
                'marketplace_score' => $aggregates['marketplace_score'],
                'pinjol_score' => $aggregates['pinjol_score'],
                'wrong_count' => $aggregates['wrong_count'],
                'avg_time_seconds' => $aggregates['avg_time_seconds'],
                'help_opened_count' => $aggregates['help_opened_count'],
                'awareness_level' => $classification['level'],
                'knn_neighbors' => $classification['neighbors'],
                'category_scores' => $aggregates['category_scores'],
            ]
        );

        $session->update([
            'status' => 'completed',
            'finished_at' => now(),
        ]);

        return redirect()->route('user.simulations.result', $session);
    }

    protected function authorizeOwn(Request $request, SimulationSession $session): void
    {
        abort_if($session->user_id !== $request->user()->id, 403);
    }
}
