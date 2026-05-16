@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('user.history.index') }}" class="text-sm text-[#6b7896]">← Riwayat</a>

    @php
        $sc = round($session->total_score);
        $correct = $session->answers->where('is_correct', true)->count();
    @endphp

    <div class="u-card p-6 mt-3 mb-4 text-center" style="border:none;color:#fff;background:linear-gradient(135deg,#1b2a4a,#243b63)">
        <div class="text-xs uppercase tracking-wider opacity-80">{{ $categoryMap[$session->selected_category] ?? $session->mode }}</div>
        <div class="text-4xl font-extrabold mt-1">{{ $sc }}</div>
        <div class="text-sm opacity-90">{{ $correct }}/{{ $session->answers->count() }} jawaban benar · {{ optional($session->started_at)->format('d M Y') }}</div>
    </div>

    <div class="space-y-2">
        @foreach($session->answers as $i => $a)
            <details class="u-card overflow-hidden">
                <summary class="cursor-pointer p-4 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs {{ $a->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $a->is_correct ? '✓' : '✕' }}</span>
                    <span class="flex-1 text-sm font-semibold">Soal {{ $i + 1 }}</span>
                    <span class="text-xs font-bold text-[#6b7896]">{{ round($a->case_score) }}</span>
                </summary>
                <div class="px-4 pb-4 text-sm space-y-2 border-t border-[#eef2f7] pt-3">
                    <div class="text-[#41506b]">{{ $a->cyberCase->scenario_text }}</div>
                    <div><span class="text-[#6b7896]">Jawabanmu:</span> {{ $a->selected_action_text }}</div>
                    <div><span class="text-[#6b7896]">Alasanmu:</span> "{{ $a->reason_text }}"</div>
                    <div class="bg-[#fff7ef] rounded-lg p-3 text-[#41506b]">{{ $a->cyberCase->tutor_feedback }}</div>
                </div>
            </details>
        @endforeach
    </div>
</div>
@endsection
