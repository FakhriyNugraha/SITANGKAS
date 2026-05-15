<?php

namespace Database\Seeders;

use App\Models\SimulationSetting;
use Illuminate\Database\Seeder;

class SimulationSettingSeeder extends Seeder
{
    public function run(): void
    {
        SimulationSetting::current();
    }
}
