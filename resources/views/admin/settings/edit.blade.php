@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-bold">Pengaturan Simulasi</h1>
    <p class="text-navy-500 text-sm">Atur perilaku global sistem.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="card max-w-2xl space-y-4">
    @csrf @method('PUT')

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Jumlah Kasus per Sesi</label>
            <input type="number" name="default_case_count" min="1" max="50" value="{{ old('default_case_count', $setting->default_case_count) }}" class="form-input" required>
            <p class="text-xs text-navy-500 mt-1">Default: 10. Setiap sesi user akan mendapatkan jumlah ini.</p>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Nilai K (KNN)</label>
            <input type="number" name="knn_k_value" min="1" max="15" value="{{ old('knn_k_value', $setting->knn_k_value) }}" class="form-input" required>
            <p class="text-xs text-navy-500 mt-1">PRD merekomendasikan K = 3.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Fuzzy Match Threshold</label>
            <input type="number" name="fuzzy_match_threshold" min="30" max="100" value="{{ old('fuzzy_match_threshold', $setting->fuzzy_match_threshold) }}" class="form-input" required>
            <p class="text-xs text-navy-500 mt-1">Default: 70.</p>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Fuzzy Partial Threshold</label>
            <input type="number" name="fuzzy_partial_threshold" min="20" max="100" value="{{ old('fuzzy_partial_threshold', $setting->fuzzy_partial_threshold) }}" class="form-input" required>
            <p class="text-xs text-navy-500 mt-1">Default: 60. Skor partial diberi bobot setengah.</p>
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_mixed_mode_enabled" value="1" @checked($setting->is_mixed_mode_enabled)> Mode campuran aktif</label>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="randomize_cases" value="1" @checked($setting->randomize_cases)> Randomisasi kasus per sesi</label>

    <button class="btn-primary">Simpan Pengaturan</button>
</form>
@endsection
