@extends('layouts.user')

@php
    $path = app(\App\Services\LearningPathService::class);
    $levels = $path->forUser($user);
    $prog = $path->progress($user);
    $current = collect($levels)->firstWhere('status', 'open') ?? collect($levels)->last();
@endphp

@section('content')
<div class="u-card p-6 mb-5 bounce-in" style="background:linear-gradient(135deg,#1b2a4a,#243b63);color:#fff;border:none">
    <div class="text-orange-300 text-xs font-semibold uppercase tracking-wider mb-1">Selamat datang kembali</div>
    <h1 class="text-2xl font-extrabold">Halo, {{ $user->name }}!</h1>
    <p class="text-[#c7d2e6] text-sm mt-1">Lanjutkan perjalanan belajarmu jadi lebih tanggap terhadap penipuan digital.</p>

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
    <div class="u-card p-4">
        <div class="text-xs text-[#6b7896] uppercase tracking-wider">Level Selesai</div>
        <div class="text-3xl font-extrabold text-[#1b2a4a]">{{ $prog['done'] }}</div>
    </div>
    <div class="u-card p-4">
        <div class="text-xs text-[#6b7896] uppercase tracking-wider">Sesi Latihan</div>
        <div class="text-3xl font-extrabold text-[#1b2a4a]">{{ $totalSessions }}</div>
    </div>
    <div class="u-card p-4">
        <div class="text-xs text-[#6b7896] uppercase tracking-wider">Rata-rata Skor</div>
        <div class="text-3xl font-extrabold text-[#e67e22]">{{ $averageScore }}</div>
    </div>
</div>

@if($current)
<div class="u-card p-5 mb-5 flex items-center gap-4">
    <div class="lvl-node {{ $current['status']==='done'?'is-done':'is-open' }}" style="flex:none">{{ $current['icon'] }}</div>
    <div class="flex-1">
        <div class="text-xs text-[#6b7896]">Lanjut belajar</div>
        <div class="font-bold text-lg">Level {{ $current['index'] }}: {{ $current['title'] }}</div>
        <div class="text-sm text-[#6b7896]">{{ $current['total_cases'] }} latihan menanti</div>
    </div>
    <a href="{{ route('user.levels.show', $current['category']) }}" class="btn-primary">Mulai →</a>
</div>
@endif

<div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">Rekomendasi Materi</h2>
    <a href="{{ route('user.materials.index') }}" class="text-[#e67e22] text-sm font-semibold">Semua materi →</a>
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
