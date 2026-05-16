<?php

namespace App\Services;

use App\Models\CyberCase;
use App\Models\LearningMaterial;
use App\Models\SimulationSession;
use App\Models\User;

class LearningPathService
{
    /**
     * Urutan jenjang belajar dari paling dasar ke paling menantang.
     * Setiap entri: [category, judul, ikon, warna heks].
     */
    public const PATH = [
        ['legitimate',        'Mengenali Pesan Aman',     '🛡️', '#16a34a'],
        ['phishing_link',     'Link Phishing',            '🔗', '#e67e22'],
        ['fake_giveaway',     'Hadiah Palsu',             '🎁', '#db2777'],
        ['otp_fraud',         'Penipuan OTP & PIN',       '🔑', '#dc2626'],
        ['password_security', 'Keamanan Password',        '🔒', '#2563eb'],
        ['marketplace_scam',  'Belanja Online Aman',      '🛒', '#7c3aed'],
        ['apk_malware',       'Bahaya File APK',          '📱', '#0891b2'],
        ['pinjol_ilegal',     'Pinjol Ilegal',            '💸', '#b45309'],
        ['job_scam',          'Lowongan Kerja Palsu',     '💼', '#0d9488'],
        ['qris_scam',         'QRIS & Bukti Transfer',    '🧾', '#9333ea'],
    ];

    /**
     * Bangun jalur belajar lengkap dengan status setiap level.
     */
    public function forUser(User $user): array
    {
        $completedCats = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('selected_category')
            ->filter()
            ->unique()
            ->all();

        $bestScores = SimulationSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->selectRaw('selected_category, MAX(total_score) as best')
            ->groupBy('selected_category')
            ->pluck('best', 'selected_category')
            ->toArray();

        $caseCounts = CyberCase::where('is_active', true)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $levels = [];
        $prevDone = true; // level pertama selalu terbuka

        foreach (self::PATH as $i => [$cat, $title, $icon, $color]) {
            $isDone = in_array($cat, $completedCats, true);
            $status = $isDone ? 'done' : ($prevDone ? 'open' : 'locked');
            $best = isset($bestScores[$cat]) ? round((float) $bestScores[$cat]) : null;

            $levels[] = [
                'index' => $i + 1,
                'category' => $cat,
                'title' => $title,
                'icon' => $icon,
                'color' => $color,
                'status' => $status,
                'best_score' => $best,
                'stars' => $this->stars($best),
                'total_cases' => $caseCounts[$cat] ?? 0,
                'material' => LearningMaterial::where('category', $cat)->where('is_active', true)->first(),
            ];

            $prevDone = $isDone; // level berikutnya terbuka hanya jika level ini selesai
        }

        return $levels;
    }

    public function levelFor(User $user, string $category): ?array
    {
        foreach ($this->forUser($user) as $level) {
            if ($level['category'] === $category) {
                return $level;
            }
        }
        return null;
    }

    public function progress(User $user): array
    {
        $levels = $this->forUser($user);
        $done = count(array_filter($levels, fn ($l) => $l['status'] === 'done'));
        return ['done' => $done, 'total' => count($levels)];
    }

    protected function stars(?int $score): int
    {
        if ($score === null) return 0;
        return match (true) {
            $score >= 85 => 3,
            $score >= 65 => 2,
            $score >= 1  => 1,
            default => 0,
        };
    }
}
