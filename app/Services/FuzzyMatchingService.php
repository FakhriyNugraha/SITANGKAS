<?php

namespace App\Services;

use App\Models\FuzzyIndicator;
use App\Models\SimulationSetting;
use Illuminate\Support\Collection;

class FuzzyMatchingService
{
    protected int $matchThreshold;
    protected int $partialThreshold;

    public function __construct()
    {
        $setting = SimulationSetting::current();
        $this->matchThreshold = $setting->fuzzy_match_threshold;
        $this->partialThreshold = $setting->fuzzy_partial_threshold;
    }

    public function preprocessText(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
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
        $distance = levenshtein(
            substr($reason, 0, 255),
            substr($keyword, 0, 255)
        );
        $levenshteinScore = $maxLen > 0 ? (1 - ($distance / $maxLen)) * 100 : 0;

        // Token-based check: cek apakah semua kata di keyword muncul di reason
        $keywordTokens = array_filter(explode(' ', $keyword));
        $reasonTokens = explode(' ', $reason);
        $tokenScore = 0;
        if (! empty($keywordTokens)) {
            $hits = 0;
            foreach ($keywordTokens as $tok) {
                if (strlen($tok) < 3) continue;
                foreach ($reasonTokens as $rTok) {
                    if (str_contains($rTok, $tok) || str_contains($tok, $rTok)) {
                        $hits++;
                        break;
                    }
                }
            }
            $tokenScore = (count($keywordTokens) > 0)
                ? ($hits / count($keywordTokens)) * 100
                : 0;
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

        // Ambil semua variasi kata dari kamus fuzzy
        $variations = FuzzyIndicator::where('is_active', true)
            ->get()
            ->groupBy('normal_indicator');

        foreach ($idealIndicators as $indicator) {
            $name = is_array($indicator) ? ($indicator['name'] ?? '') : $indicator;
            $weight = is_array($indicator) ? ($indicator['weight'] ?? 10) : 10;

            if ($name === '') continue;

            $bestScore = $this->calculateSimilarity($reason, $name);
            $matchedVariation = null;

            // cek variasi-variasi dari kamus
            $relatedVariations = $variations->get($name, collect());
            foreach ($relatedVariations as $v) {
                $s = $this->calculateSimilarity($reason, $v->keyword_variation);
                if ($s > $bestScore) {
                    $bestScore = $s;
                    $matchedVariation = $v->keyword_variation;
                }
            }

            $entry = [
                'indicator' => $name,
                'weight' => (int) $weight,
                'similarity' => round($bestScore, 2),
                'matched_variation' => $matchedVariation,
            ];

            if ($bestScore >= $this->matchThreshold) {
                $detected[] = $entry;
            } elseif ($bestScore >= $this->partialThreshold) {
                // partial - half weight
                $entry['partial'] = true;
                $entry['weight'] = (int) round($weight * 0.5);
                $detected[] = $entry;
            } else {
                $missed[] = $entry;
            }
        }

        return [
            'detected' => $detected,
            'missed' => $missed,
        ];
    }

    public function calculateFuzzyScore(array $detected, array $idealIndicators): float
    {
        $totalIdeal = 0;
        foreach ($idealIndicators as $ind) {
            $totalIdeal += is_array($ind) ? ($ind['weight'] ?? 10) : 10;
        }
        if ($totalIdeal === 0) return 0.0;

        $totalDetected = 0;
        foreach ($detected as $d) {
            $totalDetected += $d['weight'] ?? 0;
        }

        return round(($totalDetected / $totalIdeal) * 100, 2);
    }

    public function reasonStatus(float $fuzzyScore): string
    {
        return match (true) {
            $fuzzyScore >= 80 => 'sangat sesuai',
            $fuzzyScore >= 60 => 'cukup sesuai',
            $fuzzyScore >= 40 => 'kurang sesuai',
            default => 'belum sesuai',
        };
    }
}
