@extends('layouts.user')

@php
    $detected = $answer->detected_indicators ?? [];
    $missed = $answer->missed_indicators ?? [];
    $hasIndicators = count($detected) + count($missed) > 0;
    $ok = $answer->is_correct;
@endphp

@section('content')
<div class="max-w-2xl mx-auto bounce-in">
    <div class="u-card p-6 mb-4 text-center" style="border:none;color:#fff;background:linear-gradient(135deg,{{ $ok ? '#16a34a,#15803d' : '#dc2626,#b91c1c' }})">
        <span class="w-14 h-14 rounded-full bg-white/15 inline-flex items-center justify-center mb-2">
            <x-icon name="{{ $ok ? 'check-circle' : 'x-circle' }}" class="w-8 h-8" />
        </span>
        <div class="text-xl font-extrabold">{{ $ok ? 'Tindakanmu Tepat' : 'Tindakanmu Belum Tepat' }}</div>
        <div class="text-sm opacity-90 mt-1">Skor soal ini: {{ round($answer->case_score) }}/100</div>
    </div>

    <div class="u-card p-5 mb-4">
        <div class="text-xs text-[#6b7896] uppercase tracking-wider mb-1">Tindakan paling aman</div>
        <div class="font-bold mb-4 flex items-start gap-2">
            <x-icon name="shield-check" class="w-5 h-5 text-emerald-600 mt-0.5" style="flex:none" />
            <span>{{ $case->correct_action }}</span>
        </div>

        @if($hasIndicators)
            @if(count($detected))
                <div class="mb-3">
                    <div class="text-xs font-semibold text-emerald-700 mb-2">Tanda bahaya yang berhasil kamu kenali</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($detected as $d)
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium">{{ $d['indicator'] }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($missed))
                <div>
                    <div class="text-xs font-semibold text-rose-700 mb-2">Yang juga perlu diwaspadai</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($missed as $m)
                            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-medium border border-rose-200">{{ $m['indicator'] }}</span>
                        @endforeach
                    </div>
                </div>
            @elseif(count($detected))
                <div class="text-sm text-emerald-700 font-medium">Bagus, kamu mengenali semua tanda bahayanya.</div>
            @endif
        @else
            <div class="text-sm text-[#6b7896]">Pesan ini tergolong aman, jadi tidak ada tanda bahaya khusus. Kuncinya tetap tenang dan verifikasi bila ragu.</div>
        @endif
    </div>

    <div class="u-card p-5 mb-5" style="background:#fff7ef;border-color:#f6d9b8">
        <div class="text-xs font-bold text-[#c2611a] uppercase tracking-wider mb-1 flex items-center gap-1.5">
            <x-icon name="sparkles" class="w-4 h-4" /> Penjelasan
        </div>
        <p class="text-sm leading-relaxed text-[#41506b]">{{ $case->tutor_feedback }}</p>
    </div>

    <form method="POST" action="{{ route('user.simulations.next', $session) }}">
        @csrf
        <button class="btn-primary w-full text-base py-3">
            {{ $isLast ? 'Lihat Hasil Level' : 'Lanjut' }} <x-icon name="arrow-right" class="w-4 h-4" />
        </button>
    </form>
</div>
@endsection
