<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SimulationSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => SimulationSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'default_case_count' => ['required', 'integer', 'min:1', 'max:50'],
            'fuzzy_match_threshold' => ['required', 'integer', 'min:30', 'max:100'],
            'fuzzy_partial_threshold' => ['required', 'integer', 'min:20', 'max:100'],
            'knn_k_value' => ['required', 'integer', 'min:1', 'max:15'],
            'is_mixed_mode_enabled' => ['nullable', 'boolean'],
            'randomize_cases' => ['nullable', 'boolean'],
        ]);

        $setting = SimulationSetting::current();
        $setting->update(array_merge($data, [
            'is_mixed_mode_enabled' => $request->boolean('is_mixed_mode_enabled'),
            'randomize_cases' => $request->boolean('randomize_cases'),
        ]));

        return back()->with('status', 'Pengaturan simulasi berhasil disimpan.');
    }
}
