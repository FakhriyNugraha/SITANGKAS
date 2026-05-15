<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SimulationSettingSeeder::class,
            FuzzyIndicatorSeeder::class,
            CyberCaseSeeder::class,
            KnnTrainingSeeder::class,
            LearningMaterialSeeder::class,
        ]);
    }
}
