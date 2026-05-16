@extends('layouts.user')

@php
    $detected = $answer->detected_indicators ?? [];
    $missed = $answer->missed_indicators ?? [];
    $hasIndicators = count($detected) + count($missed) > 0;
@endphp

@section('content')
<div class="max-w-2xl mx-auto bounce-in">
    {{-- status --}}
    <div class="u-card p-6 mb-4 text-center"
         style="border:none;color:#fff;background:linear-gradient(135deg,{{ $answer->is_correct ? '#16a34a,#15803d' : '#dc2626,#b91c1c' }})">
        <div class="text-5xl mb-2">{{ $answer->is_correct ? '🎉' : '😅' }}</div>
        <div class="text-xl font-extrabold">{{ $answer->is_correct ? 'Tepat!' : 'Belum tepat' }}</div>
        <div class="text-sm opacity-90 mt-1">Skor soal ini: {{ round($answer->case_score) }}/100</div>
    </div>

    {{-- tindakan benar --}}
    <div class="u-card p-5 mb-4">
        <div class="text-xs text-[#6b7896] uppercase tracking-wider mb-1">Tindakan paling aman</div>
        <div class="font-bold mb-4">{{ $case->correct_action }}</div>

        @if($hasIndicators)
            @if(count($detected))
                <div class="mb-3">
                    <div class="text-xs font-semibold text-emerald-700 mb-2">✓ Hal berbahaya yang berhasil kamu kenali</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($detected as $d)
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium">{{ $d['indicator'] }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($missed))
                <div class="mb-1">
                    <div class="text-xs font-semibold text-rose-700 mb-2">Yang sebenarnya juga perlu diwaspadai</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($missed as $m)
                            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-medium border border-rose-200">{{ $m['indicator'] }}</span>
                        @endforeach
                    </div>
                </div>
            @elseif(count($detected))
                <div class="text-sm text-emerald-700 font-medium">👏 Mantap! Kamu mengenali semua tanda bahayanya.</div>
            @endif
        @else
            <div class="text-sm text-[#6b7896]">Pesan ini tergolong aman, jadi tidak ada tanda bahaya khusus. Kuncinya: tetap tenang dan verifikasi bila ragu.</div>
        @endif
    </div>

    {{-- penjelasan tutor --}}
    <div class="u-card p-5 mb-5" style="background:#fff7ef;border-color:#f6d9b8">
        <div class="text-xs font-bold text-[#c2611a] uppercase tracking-wider mb-1">💡 Penjelasan</div>
        <p class="text-sm leading-relaxed text-[#41506b]">{{ $case->tutor_feedback }}</p>
    </div>

    <form method="POST" action="{{ route('user.simulations.next', $session) }}">
        @csrf
        <button class="btn-primary w-full text-base py-3">{{ $isLast ? 'Lihat Hasil Level →' : 'Lanjut →' }}</button>
    </form>
</div>
@endsection
