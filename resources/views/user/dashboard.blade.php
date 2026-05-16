@extends('layouts.user')

@php
    $path = app(\App\Services\LearningPathService::class);
    $levels = $path->forUser($user);
    $prog = $path->progress($user);
    $current = collect($levels)->firstWhere('status', 'open') ?? collect($levels)->last();

    $levelText = [
        'beginner' => ['Pemula', 'Terus berlatih untuk mengenali lebih banyak modus penipuan.'],
        'intermediate' => ['Menengah', 'Pemahamanmu sudah baik. Tingkatkan ke kasus yang lebih sulit.'],
        'advanced' => ['Mahir', 'Hebat! Kamu sudah sangat tanggap terhadap penipuan digital.'],
    ];
    $lv = $assessment['level'] ?? null;
    [$lvLabel, $lvDesc] = $levelText[$lv] ?? ['Belum dinilai', 'Selesaikan minimal satu simulasi untuk melihat tingkat kemampuanmu.'];
@endphp

@section('content')
<div class="u-card p-6 mb-5 bounce-in" style="background:linear-gradient(135deg,#1b2a4a 0%,#243b63 60%,#7a3f12 140%);color:#fff;border:none">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="text-orange-300 text-xs font-semibold uppercase tracking-wider mb-1">Selamat datang kembali</div>
            <h1 class="text-2xl font-extrabold">Halo, {{ $user->name }}</h1>
            <p class="text-[#c7d2e6] text-sm mt-1">Lanjutkan latihan agar makin tanggap terhadap penipuan digital.</p>
        </div>
        <div class="bg-white/10 rounded-2xl px-5 py-3 text-center min-w-[140px]">
            <div class="text-[11px] uppercase tracking-wider text-[#c7d2e6]">Tingkat Kemampuan</div>
            <div class="text-2xl font-extrabold">{{ $lvLabel }}</div>
        </div>
    </div>
    <p class="text-[#c7d2e6] text-xs mt-3 max-w-xl">{{ $lvDesc }}</p>

    <div class="mt-4">
        <div class="flex justify-between text-xs text-[#c7d2e6] mb-1">
            <span>Progress jalur belajar</span>
            <span>{{ $prog['done'] }} dari {{ $prog['total'] }} level</span>
        </div>
        <div class="u-progress-track bg-white/15">
            <div class="u-progress-bar" style="width: {{ $prog['total'] ? ($prog['done']/$prog['total']*100) : 0 }}%"></div>
        </div>
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-3 mb-5">
    <div class="u-card p-4 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-[#eef4ff] text-[#2563eb] flex items-center justify-center"><x-icon name="cap" class="w-5 h-5" /></span>
        <div>
            <div class="text-xs text-[#6b7896] uppercase tracking-wider">Level Selesai</div>
            <div class="text-2xl font-extrabold text-[#1b2a4a]">{{ $prog['done'] }}</div>
        </div>
    </div>
    <div class="u-card p-4 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-[#eef4ff] text-[#2563eb] flex items-center justify-center"><x-icon name="list" class="w-5 h-5" /></span>
        <div>
            <div class="text-xs text-[#6b7896] uppercase tracking-wider">Sesi Latihan</div>
            <div class="text-2xl font-extrabold text-[#1b2a4a]">{{ $totalSessions }}</div>
        </div>
    </div>
    <div class="u-card p-4 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-[#fff3e6] text-[#e67e22] flex items-center justify-center"><x-icon name="chart" class="w-5 h-5" /></span>
        <div>
            <div class="text-xs text-[#6b7896] uppercase tracking-wider">Rata-rata Skor</div>
            <div class="text-2xl font-extrabold text-[#e67e22]">{{ $averageScore }}</div>
        </div>
    </div>
</div>

@if($current)
<div class="u-card p-5 mb-5 flex items-center gap-4">
    <span class="lvl-node {{ $current['status']==='done'?'is-done':'is-open' }}" style="flex:none">
        <x-icon name="{{ $current['icon'] }}" class="w-7 h-7" />
    </span>
    <div class="flex-1">
        <div class="text-xs text-[#6b7896]">Lanjut belajar</div>
        <div class="font-bold text-lg">Level {{ $current['index'] }}: {{ $current['title'] }}</div>
        <div class="text-sm text-[#6b7896]">{{ $current['total_cases'] }} latihan menanti</div>
    </div>
    <a href="{{ route('user.levels.show', $current['category']) }}" class="btn-primary">
        Mulai <x-icon name="arrow-right" class="w-4 h-4" />
    </a>
</div>
@endif

<div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">Rekomendasi Materi</h2>
    <a href="{{ route('user.materials.index') }}" class="text-[#e67e22] text-sm font-semibold inline-flex items-center gap-1">
        Semua materi <x-icon name="arrow-right" class="w-3.5 h-3.5" />
    </a>
</div>
<div class="grid sm:grid-cols-3 gap-3">
    @forelse($recommendations as $m)
        <a href="{{ route('user.materials.show', $m) }}" class="u-card p-4 card-hover block">
            <div class="text-xs uppercase tracking-wider text-[#e67e22] mb-1">{{ $categoryMap[$m->category] ?? $m->category }}</div>
            <div class="font-bold text-sm mb-1">{{ $m->title }}</div>
            <p class="text-xs text-[#6b7896]">{{ \Illuminate\Support\Str::limit($m->summary, 80) }}</p>
        </a>
    @empty
        <div class="text-[#6b7896] text-sm">Belum ada rekomendasi.</div>
    @endforelse
</div>
@endsection
