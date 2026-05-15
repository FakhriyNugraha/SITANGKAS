<?php

namespace App\Services;

use App\Models\LearningMaterial;
use App\Models\MaterialView;
use App\Models\UserAnswer;
use App\Models\UserAwarenessScore;

class RecommendationService
{
    /**
     * Cari kategori terlemah berdasarkan agregasi skor per kategori.
     * @return array daftar slug kategori urut dari terlemah.
     */
    public function findWeakCategories(int $userId, int $sessionId): array
    {
        $awareness = UserAwarenessScore::where('session_id', $sessionId)->first();
        $scores = $awareness?->category_scores ?? [];

        if (empty($scores)) {
            // fallback: hitung dari user_answers
            $rows = UserAnswer::with('cyberCase')
                ->where('session_id', $sessionId)
                ->get()
                ->groupBy(fn($a) => $a->cyberCase->category ?? 'unknown');

            $scores = [];
            foreach ($rows as $cat => $list) {
                $scores[$cat] = round($list->avg('case_score'), 2);
            }
        }

        asort($scores);
        return array_keys(array_filter($scores, fn($s) => $s < 80));
    }

    public function recommendMaterials(array $weakCategories, string $level, int $userId): array
    {
        if (empty($weakCategories)) {
            // fallback: rekomendasikan materi sesuai level
            return LearningMaterial::where('is_active', true)
                ->whereIn('target_level', [$level, 'all'])
                ->limit(3)
                ->get()
                ->all();
        }

        $viewed = MaterialView::where('user_id', $userId)
            ->pluck('learning_material_id')
            ->all();

        $materials = LearningMaterial::where('is_active', true)
            ->whereIn('category', $weakCategories)
            ->whereIn('target_level', [$level, 'all'])
            ->orderByRaw("FIELD(category, '".implode("','", $weakCategories)."')")
            ->orderByRaw('learning_materials.id NOT IN ('.(empty($viewed) ? '0' : implode(',', $viewed)).')')
            ->limit(5)
            ->get();

        if ($materials->isEmpty()) {
            $materials = LearningMaterial::where('is_active', true)
                ->whereIn('category', $weakCategories)
                ->limit(5)
                ->get();
        }

        return $materials->all();
    }

    public function generateTutorSummary(int $sessionId): string
    {
        $awareness = UserAwarenessScore::where('session_id', $sessionId)->first();
        if (! $awareness) return 'Lanjutkan latihan untuk meningkatkan pemahamanmu.';

        $level = $awareness->awareness_level;
        $weak = $this->findWeakCategories($awareness->user_id, $sessionId);

        $msg = "Level cyber awareness kamu saat ini: ".strtoupper($level).". ";
        if (empty($weak)) {
            $msg .= "Pertahankan pemahamanmu dan coba simulasi level lebih sulit.";
        } else {
            $msg .= "Kategori yang masih perlu ditingkatkan: ".implode(', ', $weak).".";
        }
        return $msg;
    }
}
