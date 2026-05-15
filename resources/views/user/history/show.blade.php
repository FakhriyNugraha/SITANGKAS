@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('user.history.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali ke riwayat</a>
    <h1 class="text-2xl font-bold mt-1 mb-1">Detail Sesi #{{ $session->id }}</h1>
    <p class="text-navy-500 text-sm mb-4">{{ optional($session->started_at)->format('d M Y H:i') }} &middot; {{ $session->mode }}</p>

    <div class="grid lg:grid-cols-4 gap-4 mb-5">
        <div class="card">
            <div class="text-xs uppercase text-navy-500">Skor</div>
            <div class="text-3xl font-bold count-up">{{ $session->total_score }}</div>
        </div>
        <div class="card">
            <div class="text-xs uppercase text-navy-500">Level</div>
            <div class="text-xl font-bold count-up capitalize">{{ optional($session->awarenessScore)->awareness_level ?? '-' }}</div>
        </div>
        <div class="card">
            <div class="text-xs uppercase text-navy-500">Benar</div>
            <div class="text-3xl font-bold count-up">{{ $session->answers->where('is_correct', true)->count() }}/{{ $session->answers->count() }}</div>
        </div>
        <div class="card">
            <div class="text-xs uppercase text-navy-500">Rata Waktu</div>
            <div class="text-3xl font-bold count-up">{{ round($session->answers->avg('answer_time_seconds') ?? 0) }}s</div>
        </div>
    </div>

    <div class="card">
        <div class="font-semibold mb-3">Jawaban Per Kasus</div>
        @foreach($session->answers as $a)
            <details class="border border-navy-100 rounded-lg mb-2">
                <summary class="cursor-pointer p-3 flex flex-wrap items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $a->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{!! $a->is_correct ? '✓' : '✕' !!}</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm">{{ $a->cyberCase->category_name }}</div>
                        <div class="text-xs text-navy-500 truncate">{{ \Illuminate\Support\Str::limit($a->cyberCase->scenario_text, 90) }}</div>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-navy-500">Skor</div>
                        <div class="font-bold">{{ $a->case_score }}</div>
                    </div>
                </summary>
                <div class="p-3 border-t border-navy-100 text-sm space-y-2">
                    <div><b>Tindakan kamu:</b> {{ $a->selected_action_text }}</div>
                    <div><b>Alasan:</b> "{{ $a->reason_text }}"</div>
                    <div><b>Tindakan benar:</b> {{ $a->cyberCase->correct_action }}</div>
                    <div class="text-xs text-navy-600 bg-navy-50 rounded-lg p-2">{{ $a->cyberCase->tutor_feedback }}</div>
                </div>
            </details>
        @endforeach
    </div>
</div>
@endsection
