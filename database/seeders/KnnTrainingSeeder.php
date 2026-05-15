<?php

namespace Database\Seeders;

use App\Models\KnnTrainingProfile;
use Illuminate\Database\Seeder;

class KnnTrainingSeeder extends Seeder
{
    public function run(): void
    {
        // Generate dataset 60 profiles dengan distribusi: 20 beginner, 30 intermediate, 10 advanced
        $rows = [];
        $idx = 1;

        // Beginner (skor rendah, salah banyak, waktu lama, banyak buka bantuan)
        for ($i = 0; $i < 20; $i++) {
            $rows[] = [
                'profile_code' => 'KNN-B'.str_pad((string)$idx++, 3, '0', STR_PAD_LEFT),
                'phishing_score' => rand(20, 55),
                'otp_score' => rand(20, 55),
                'password_score' => rand(20, 55),
                'marketplace_score' => rand(20, 55),
                'pinjol_score' => rand(20, 55),
                'wrong_count' => rand(5, 10),
                'avg_time_seconds' => rand(35, 70),
                'help_opened_count' => rand(3, 8),
                'awareness_level' => 'beginner',
            ];
        }

        // Intermediate (skor sedang)
        for ($i = 0; $i < 30; $i++) {
            $rows[] = [
                'profile_code' => 'KNN-I'.str_pad((string)$idx++, 3, '0', STR_PAD_LEFT),
                'phishing_score' => rand(55, 80),
                'otp_score' => rand(55, 80),
                'password_score' => rand(55, 80),
                'marketplace_score' => rand(55, 80),
                'pinjol_score' => rand(55, 80),
                'wrong_count' => rand(2, 5),
                'avg_time_seconds' => rand(20, 40),
                'help_opened_count' => rand(1, 4),
                'awareness_level' => 'intermediate',
            ];
        }

        // Advanced (skor tinggi, salah sedikit, waktu cepat)
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'profile_code' => 'KNN-A'.str_pad((string)$idx++, 3, '0', STR_PAD_LEFT),
                'phishing_score' => rand(85, 100),
                'otp_score' => rand(85, 100),
                'password_score' => rand(85, 100),
                'marketplace_score' => rand(80, 100),
                'pinjol_score' => rand(80, 100),
                'wrong_count' => rand(0, 2),
                'avg_time_seconds' => rand(8, 22),
                'help_opened_count' => rand(0, 2),
                'awareness_level' => 'advanced',
            ];
        }

        foreach ($rows as $r) {
            KnnTrainingProfile::updateOrCreate(['profile_code' => $r['profile_code']], $r);
        }
    }
}
