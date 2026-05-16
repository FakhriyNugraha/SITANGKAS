<?php

namespace App\Services;

use App\Models\KnnTrainingProfile;
use App\Models\SimulationSetting;
use App\Models\UserAnswer;
use App\Models\SimulationSession;

class KNNService
{
    protected int $k;

    /** Label ramah untuk ditampilkan ke pengguna/admin (tanpa istilah teknis). */
    public const LABELS = [
        'beginner' => 'Pemula',
        'intermediate' => 'Menengah',
        'advanced' => 'Mahir',
    ];

    public static function label(?string $level): string
    {
        return self::LABELS[$level] ?? 'Belum dinilai';
    }

    /** Feature columns dalam urutan tetap. */
    public const FEATURES = [
        'phishing_score',
        'otp_score',
        'password_score',
        'marketplace_score',
        'pinjol_score',
        'wrong_count',
        'avg_time_seconds',
        'help_opened_count',
    ];

    public function __construct()
    {
        $this->k = (int) (SimulationSetting::current()->knn_k_value ?: 3);
    }

    /**
     * Bangun vector user dari hasil ScoringService::calculateCategoryScores().
     */
    public function buildUserVector(array $aggregates): array
    {
        $vec = [];
        foreach (self::FEATURES as $f) {
            $vec[$f] = (float) ($aggregates[$f] ?? 0);
        }
        return $vec;
    }

    /**
     * Hitung min-max dari training + user, lalu normalisasi.
     */
    public function buildNormalizedDataset(array $userVector): array
    {
        $training = KnnTrainingProfile::all();
        if ($training->isEmpty()) {
            return ['user' => $userVector, 'training' => []];
        }

        $min = [];
        $max = [];
        foreach (self::FEATURES as $f) {
            $vals = $training->pluck($f)->map(fn($v) => (float) $v)->all();
            $vals[] = $userVector[$f];
            $min[$f] = min($vals);
            $max[$f] = max($vals);
        }

        $normalizeRow = function (array $row) use ($min, $max) {
            $out = [];
            foreach (self::FEATURES as $f) {
                $range = $max[$f] - $min[$f];
                $out[$f] = $range > 0 ? (($row[$f] - $min[$f]) / $range) : 0.0;
            }
            return $out;
        };

        $userNorm = $normalizeRow($userVector);

        $trainingNorm = [];
        foreach ($training as $t) {
            $row = [];
            foreach (self::FEATURES as $f) $row[$f] = (float) $t->{$f};
            $trainingNorm[] = [
                'vector' => $normalizeRow($row),
                'level' => $t->awareness_level,
                'profile_code' => $t->profile_code,
            ];
        }

        return ['user' => $userNorm, 'training' => $trainingNorm];
    }

    public function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        foreach (self::FEATURES as $f) {
            $diff = ($a[$f] ?? 0) - ($b[$f] ?? 0);
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }

    /**
     * Klasifikasi level user. Return ['level','neighbors','k'].
     */
    public function classify(array $userVector): array
    {
        $ds = $this->buildNormalizedDataset($userVector);

        if (empty($ds['training'])) {
            return [
                'level' => 'beginner',
                'neighbors' => [],
                'k' => $this->k,
                'note' => 'Belum ada data training KNN.',
            ];
        }

        $distances = [];
        foreach ($ds['training'] as $row) {
            $distances[] = [
                'distance' => $this->euclideanDistance($ds['user'], $row['vector']),
                'level' => $row['level'],
                'profile_code' => $row['profile_code'],
            ];
        }

        usort($distances, fn($x, $y) => $x['distance'] <=> $y['distance']);
        $nearest = array_slice($distances, 0, $this->k);

        $votes = [];
        $sumDist = [];
        foreach ($nearest as $n) {
            $votes[$n['level']] = ($votes[$n['level']] ?? 0) + 1;
            $sumDist[$n['level']] = ($sumDist[$n['level']] ?? 0) + $n['distance'];
        }

        arsort($votes);
        $topVotes = array_keys($votes, max($votes));
        if (count($topVotes) === 1) {
            $level = $topVotes[0];
        } else {
            // tie-break: rata-rata jarak terkecil
            $best = null;
            $bestAvg = INF;
            foreach ($topVotes as $lvl) {
                $avg = $sumDist[$lvl] / max($votes[$lvl], 1);
                if ($avg < $bestAvg) {
                    $bestAvg = $avg;
                    $best = $lvl;
                }
            }
            $level = $best ?: $topVotes[0];
        }

        return [
            'level' => $level,
            'neighbors' => array_map(fn($n) => [
                'profile_code' => $n['profile_code'],
                'level' => $n['level'],
                'distance' => round($n['distance'], 4),
            ], $nearest),
            'k' => $this->k,
        ];
    }

    /**
     * Klasifikasi level keseluruhan seorang user dari SELURUH jawaban yang
     * sudah selesai. Fitur kategori yang belum pernah dilatih diisi dengan
     * rata-rata performa user (bukan 0) supaya tidak bias ke "Pemula".
     *
     * @return array|null  ['level', 'scores'=>[...], 'sessions'=>int] atau null bila belum ada data.
     */
    public function classifyForUser(int $userId): ?array
    {
        $answers = UserAnswer::with('cyberCase')
            ->where('user_id', $userId)
            ->whereHas('session', fn ($q) => $q->where('status', 'completed'))
            ->get();

        if ($answers->isEmpty()) {
            return null;
        }

        $catToFeature = [
            'phishing_link' => 'phishing_score',
            'fake_giveaway' => 'phishing_score',
            'apk_malware' => 'phishing_score',
            'job_scam' => 'phishing_score',
            'qris_scam' => 'marketplace_score',
            'otp_fraud' => 'otp_score',
            'password_security' => 'password_score',
            'marketplace_scam' => 'marketplace_score',
            'pinjol_ilegal' => 'pinjol_score',
        ];

        $buckets = [
            'phishing_score' => [], 'otp_score' => [], 'password_score' => [],
            'marketplace_score' => [], 'pinjol_score' => [],
        ];
        foreach ($answers as $a) {
            $f = $catToFeature[$a->cyberCase->category ?? ''] ?? null;
            if ($f) $buckets[$f][] = (float) $a->case_score;
        }

        // Rata-rata keseluruhan untuk imputasi fitur yang kosong.
        $allScores = $answers->pluck('case_score')->map(fn ($v) => (float) $v)->all();
        $overallAvg = $allScores ? array_sum($allScores) / count($allScores) : 0.0;

        $vector = [];
        foreach ($buckets as $f => $vals) {
            $vector[$f] = $vals ? round(array_sum($vals) / count($vals), 2) : round($overallAvg, 2);
        }

        $sessions = SimulationSession::where('user_id', $userId)
            ->where('status', 'completed')->get();
        $sessionCount = max($sessions->count(), 1);

        $vector['wrong_count'] = round($answers->where('is_correct', false)->count() / $sessionCount, 2);
        $vector['avg_time_seconds'] = round($answers->avg('answer_time_seconds') ?? 0, 2);
        $vector['help_opened_count'] = round($answers->where('help_opened', true)->count() / $sessionCount, 2);

        $result = $this->classify($vector);

        return [
            'level' => $result['level'],
            'label' => self::label($result['level']),
            'scores' => $vector,
            'overall_score' => round($overallAvg, 1),
            'sessions' => $sessions->count(),
        ];
    }
}
