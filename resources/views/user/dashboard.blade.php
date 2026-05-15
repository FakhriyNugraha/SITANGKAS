@extends('layouts.user')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Halo, {{ $user->name }} <span class="ml-1">👋</span></h1>
        <p class="text-navy-500 text-sm">Lanjutkan latihan cyber awareness Anda hari ini.</p>
    </div>
    <a href="{{ route('user.simulations.index') }}" class="btn-primary">+ Mulai Simulasi</a>
</div>

<div class="grid lg:grid-cols-4 gap-4 mb-6">
    <div class="card lg:col-span-2 navy-gradient text-white scan-line-bg">
        <div class="text-xs uppercase tracking-wider text-orange-200 mb-1">Level Cyber Awareness</div>
        @if($latestAwareness)
            <div class="text-4xl font-extrabold count-up capitalize">{{ $latestAwareness->awareness_level }}</div>
            <div class="text-navy-100 text-sm mt-2">{{ \Illuminate\Support\Str::limit($latestSession->mode ?? '', 50) }} &middot; {{ optional($latestSession?->finished_at)->diffForHumans() }}</div>
        @else
            <div class="text-2xl font-bold count-up">Belum dimulai</div>
            <div class="text-navy-100 text-sm mt-2">Selesaikan minimal 1 sesi simulasi untuk mendapatkan level.</div>
        @endif
    </div>
    <div class="card">
        <div class="text-xs uppercase tracking-wider text-navy-500 mb-1">Sesi Selesai</div>
        <div class="text-3xl font-bold count-up">{{ $totalSessions }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase tracking-wider text-navy-500 mb-1">Rata-rata Skor</div>
        <div class="text-3xl font-bold count-up text-orange-600">{{ $averageScore }}</div>
    </div>
</div>

@if(! empty($categoryScores))
    <div class="card mb-6">
        <div class="font-semibold mb-3">Progress per Kategori (sesi terakhir)</div>
        <div class="grid md:grid-cols-2 gap-3">
            @foreach($categoryScores as $cat => $score)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $categoryMap[$cat] ?? $cat }}</span>
                        <b>{{ round($score) }}</b>
                    </div>
                    <div class="progress-track"><div class="progress-bar" style="width: {{ min(100, $score) }}%"></div></div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="card">
    <div class="flex items-center justify-between mb-3">
        <div class="font-semibold">Rekomendasi Materi</div>
        <a href="{{ route('user.materials.index') }}" class="text-orange-600 text-sm font-semibold">Lihat semua &rsaquo;</a>
    </div>
    <div class="grid md:grid-cols-3 gap-3">
        @forelse($recommendations as $m)
            <a href="{{ route('user.materials.show', $m) }}" class="card card-hover block">
                <div class="text-xs uppercase tracking-wider text-orange-600 mb-2">{{ $categoryMap[$m->category] ?? $m->category }}</div>
                <div class="font-semibold mb-2">{{ $m->title }}</div>
                <p class="text-sm text-navy-500">{{ \Illuminate\Support\Str::limit($m->summary, 90) }}</p>
            </a>
        @empty
            <div class="text-navy-400 text-sm">Belum ada rekomendasi.</div>
        @endforelse
    </div>
</div>
@endsection
