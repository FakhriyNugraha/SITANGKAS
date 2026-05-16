<?php

namespace App\Services;

use App\Models\SimulationSession;
use App\Models\UserAnswer;

class ScoringService
{
    public function calculateActionScore(bool $isCorrect): float
    {
        return $isCorrect ? 100.0 : 0.0;
    }

    /**
     * Skor akhir sebuah kasus.
     * - Tindakan benar = fondasi nilai (60%).
     * - Kualitas alasan = 40%, dengan apresiasi: jika tindakan benar, alasan
     *   minimal dihargai 55, dan bila mengenali >=1 tanda bahaya minimal 70.
     */
    public function calculateCaseScore(float $actionScore, float $reasonScore,
                                       bool $isCorrect = false, int $detectedCount = 0): float
    {
        if ($isCorrect) {
            $floor = $detectedCount >= 1 ? 70.0 : 55.0;
            $reasonScore = max($reasonScore, $floor);
        }
        return round(($actionScore * 0.6) + ($reasonScore * 0.4), 2);
    }

    /**
     * Hitung skor agregat per kategori KNN dari semua jawaban sesi.
     * Output: [phishing_score, otp_score, password_score, marketplace_score, pinjol_score,
     *          wrong_count, avg_time_seconds, help_opened_count, category_scores]
     */
    public function calculateCategoryScores(int $sessionId): array
    {
        $answers = UserAnswer::with('cyberCase')
            ->where('session_id', $sessionId)
            ->get();

        $catMap = [
            'phishing_link' => 'phishing_score',
            'fake_giveaway' => 'phishing_score',
            'apk_malware' => 'phishing_score',
            'job_scam' => 'phishing_score',
            'qris_scam' => 'marketplace_score',
            'otp_fraud' => 'otp_score',
            'password_security' => 'password_score',
            'marketplace_scam' => 'marketplace_score',
            'pinjol_ilegal' => 'pinjol_score',
            'legitimate' => 'phishing_score',
        ];

        $buckets = [
            'phishing_score' => [],
            'otp_score' => [],
            'password_score' => [],
            'marketplace_score' => [],
            'pinjol_score' => [],
        ];

        $categoryScores = [];
        $wrong = 0;
        $totalTime = 0;
        $help = 0;

        foreach ($answers as $a) {
            $cat = $a->cyberCase->category ?? null;
            $featureCol = $cat ? ($catMap[$cat] ?? null) : null;
            if ($featureCol) {
                $buckets[$featureCol][] = (float) $a->case_score;
            }
            if ($cat) {
                $categoryScores[$cat][] = (float) $a->case_score;
            }
            if (! $a->is_correct) $wrong++;
            $totalTime += (int) $a->answer_time_seconds;
            if ($a->help_opened) $help++;
        }

        $result = [];
        foreach ($buckets as $col => $vals) {
            $result[$col] = empty($vals) ? 0 : round(array_sum($vals) / count($vals), 2);
        }

        $perCategory = [];
        foreach ($categoryScores as $c => $vals) {
            $perCategory[$c] = round(array_sum($vals) / max(count($vals), 1), 2);
        }

        $result['wrong_count'] = $wrong;
        $result['avg_time_seconds'] = $answers->count() > 0
            ? round($totalTime / $answers->count(), 2)
            : 0;
        $result['help_opened_count'] = $help;
        $result['category_scores'] = $perCategory;

        return $result;
    }

    public function recalculateSessionTotal(int $sessionId): float
    {
        $sum = UserAnswer::where('session_id', $sessionId)->sum('case_score');
        $count = UserAnswer::where('session_id', $sessionId)->count();
        $avg = $count > 0 ? round($sum / $count, 2) : 0;

        SimulationSession::where('id', $sessionId)->update([
            'total_score' => $avg,
            'completed_cases' => $count,
        ]);

        return (float) $avg;
    }
}
