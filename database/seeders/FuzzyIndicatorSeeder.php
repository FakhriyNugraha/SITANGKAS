<?php

namespace Database\Seeders;

use App\Models\FuzzyIndicator;
use Illuminate\Database\Seeder;

class FuzzyIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('fuzzy_indicator_dictionary_id.csv');
        if (! is_file($path)) {
            return;
        }

        $h = fopen($path, 'r');
        $header = fgetcsv($h); // skip header
        while (($row = fgetcsv($h)) !== false) {
            if (count($row) < 4 || trim($row[0]) === '') {
                continue;
            }
            FuzzyIndicator::updateOrCreate(
                [
                    'normal_indicator' => trim($row[0]),
                    'keyword_variation' => trim($row[1]),
                ],
                [
                    'relevant_category' => trim($row[2]) ?: null,
                    'risk_weight' => (int) $row[3] ?: 10,
                    'is_active' => true,
                ]
            );
        }
        fclose($h);
    }
}
