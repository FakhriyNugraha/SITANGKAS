<?php

namespace App\Services;

use App\Models\FuzzyIndicator;
use App\Models\SimulationSetting;

class FuzzyMatchingService
{
    protected int $matchThreshold;
    protected int $partialThreshold;

    /** Stopword yang tidak dihitung sebagai token bermakna. */
    protected array $stopwords = [
        'yang', 'dan', 'atau', 'itu', 'ini', 'karena', 'dengan', 'untuk', 'pada',
        'di', 'ke', 'dari', 'akan', 'juga', 'saya', 'aku', 'kita', 'kami', 'nya',
        'ada', 'tidak', 'gak', 'ga', 'nggak', 'kok', 'sih', 'aja', 'saja', 'biar',
        'agar', 'jadi', 'lalu', 'terus', 'trus', 'kalo', 'kalau', 'apa', 'kah',
    ];

    public function __construct()
    {
        $setting = SimulationSetting::current();
        $this->matchThreshold = $setting->fuzzy_match_threshold;
        $this->partialThreshold = $setting->fuzzy_partial_threshold;
    }

    public function preprocessText(string $text): string
    {
        $text = mb_strtolower($text);
        // samakan beberapa singkatan/typo umum
        $text = strtr($text, [
            'tdk' => 'tidak', 'gk' => 'tidak', 'ga ' => 'tidak ', 'sms' => 'sms',
            'no hp' => 'nomor', 'no.hp' => 'nomor', 'rek ' => 'rekening ',
            'apk' => 'apk', 'wa ' => 'whatsapp ', 'mencurigakn' => 'mencurigakan',
        ]);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/(.)\1{2,}/u', '$1', $text); // huruf berulang: anehhh -> aneh
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /** Token bermakna (tanpa stopword & token pendek). */
    protected function tokens(string $text): array
    {
        $parts = array_filter(explode(' ', $text), fn ($t) => mb_strlen($t) >= 3 && ! in_array($t, $this->stopwords, true));
        return array_values($parts);
    }

    /** Rasio kemiripan dua token (0-1) berbasis levenshtein. */
    protected function tokenRatio(string $a, string $b): float
    {
        if ($a === $b) return 1.0;
        if (str_contains($a, $b) || str_contains($b, $a)) return 0.92;
        $max = max(strlen($a), strlen($b));
        if ($max === 0) return 0.0;
        $d = levenshtein($a, $b);
        return 1 - ($d / $max);
    }

    public function calculateSimilarity(string $reason, string $keyword): float
    {
        $reason = $this->preprocessText($reason);
        $keyword = $this->preprocessText($keyword);

        if ($reason === '' || $keyword === '') {
            return 0.0;
        }

        if (str_contains($reason, $keyword)) {
            return 100.0;
        }

        similar_text($reason, $keyword, $similarPercent);

        $maxLen = max(strlen($reason), strlen($keyword));
        $distance = levenshtein(substr($reason, 0, 255), substr($keyword, 0, 255));
        $levenshteinScore = $maxLen > 0 ? (1 - ($distance / $maxLen)) * 100 : 0;

        // Token fuzzy: tiap kata kunci dicocokkan ke kata terbaik di alasan,
        // toleran typo (rasio >= 0.74) sehingga "linkny", "mncurigakan" tetap kena.
        $keywordTokens = $this->tokens($keyword);
        $reasonTokens = $this->tokens($reason);
        $tokenScore = 0;
        if (! empty($keywordTokens) && ! empty($reasonTokens)) {
            $sum = 0;
            foreach ($keywordTokens as $kt) {
                $best = 0;
                foreach ($reasonTokens as $rt) {
                    $best = max($best, $this->tokenRatio($kt, $rt));
                }
                // hanya hitung jika cukup mirip
                $sum += $best >= 0.74 ? $best : 0;
            }
            $tokenScore = ($sum / count($keywordTokens)) * 100;
        }

        return (float) max($similarPercent, $levenshteinScore, $tokenScore);
    }

    /**
     * Deteksi indikator ideal dalam alasan user.
     *
     * @param  array  $idealIndicators  ex: [["name"=>"link tidak resmi","weight"=>10], ...]
     */
    public function detectIndicators(string $reason, array $idealIndicators): array
    {
        $detected = [];
        $missed = [];

        $variations = FuzzyIndicator::where('is_active', true)
            ->get()
            ->groupBy('normal_indicator');

        foreach ($idealIndicators as $indicator) {
            $name = is_array($indicator) ? ($indicator['name'] ?? '') : $indicator;
            $weight = is_array($indicator) ? ($indicator['weight'] ?? 10) : 10;

            if ($name === '') continue;

            $bestScore = $this->calculateSimilarity($reason, $name);

            // cek seluruh variasi kata yang punya indikator normal sama
            foreach ($variations->get($name, collect()) as $v) {
                $bestScore = max($bestScore, $this->calculateSimilarity($reason, $v->keyword_variation));
                if ($bestScore >= 100) break;
            }

            $entry = [
                'indicator' => $name,
                'weight' => (int) $weight,
                'similarity' => round($bestScore, 2),
            ];

            if ($bestScore >= $this->matchThreshold) {
                $detected[] = $entry;
            } elseif ($bestScore >= $this->partialThreshold) {
                $entry['partial'] = true;
                $entry['weight'] = (int) round($weight * 0.5);
                $detected[] = $entry;
            } else {
                $missed[] = $entry;
            }
        }

        return ['detected' => $detected, 'missed' => $missed];
    }

    public function calculateFuzzyScore(array $detected, array $idealIndicators): float
    {
        $totalIdeal = 0;
        foreach ($idealIndicators as $ind) {
            $totalIdeal += is_array($ind) ? ($ind['weight'] ?? 10) : 10;
        }
        if ($totalIdeal === 0) return 100.0; // kasus aman tanpa indikator: alasan dianggap penuh

        $totalDetected = 0;
        foreach ($detected as $d) {
            $totalDetected += $d['weight'] ?? 0;
        }

        $score = ($totalDetected / $totalIdeal) * 100;

        // Apresiasi: mengenali minimal satu tanda bahaya sudah bernilai layak.
        if (count($detected) >= 1) {
            $score = max($score, 65);
        }

        return round(min(100, $score), 2);
    }
}
