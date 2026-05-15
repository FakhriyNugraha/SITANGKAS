@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto pop-in">
    <div class="card mb-4 {{ $answer->is_correct ? 'border-emerald-300 bg-emerald-50' : 'border-rose-300 bg-rose-50 shake' }}">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $answer->is_correct ? 'bg-emerald-200 text-emerald-700' : 'bg-rose-200 text-rose-700' }}">
                {!! $answer->is_correct ? '✓' : '✕' !!}
            </div>
            <div>
                <div class="font-bold text-lg">Jawaban kamu {{ $answer->is_correct ? 'benar' : 'kurang tepat' }}.</div>
                <div class="text-sm text-navy-500">Skor kasus: <b>{{ $answer->case_score }}</b> (tindakan: {{ $answer->action_score }} · alasan: {{ $answer->fuzzy_score }})</div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="text-xs uppercase tracking-wider text-navy-500 mb-1">Tindakan Benar</div>
        <div class="font-semibold mb-3">{{ $case->correct_action }}</div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <div class="text-xs uppercase tracking-wider text-emerald-700 mb-2">Indikator yang kamu kenali</div>
                @forelse($answer->detected_indicators ?? [] as $d)
                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs mr-1 mb-1">
                        ✓ {{ $d['indicator'] }} <span class="text-emerald-600 text-[10px]">({{ round($d['similarity'] ?? 0) }}%)</span>
                    </div>
                @empty
                    <div class="text-navy-400 text-sm">Belum ada indikator yang dikenali.</div>
                @endforelse
            </div>
            <div>
                <div class="text-xs uppercase tracking-wider text-rose-700 mb-2">Belum kamu sebutkan</div>
                @forelse($answer->missed_indicators ?? [] as $m)
                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs mr-1 mb-1">
                        ! {{ $m['indicator'] }}
                    </div>
                @empty
                    <div class="text-emerald-600 text-sm">Mantap! Semua indikator terdeteksi.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-navy-50 border border-navy-100 rounded-lg p-4">
            <div class="text-xs uppercase tracking-wider text-orange-600 mb-1">Feedback Tutor</div>
            <p class="text-sm leading-relaxed">{{ $case->tutor_feedback }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('user.simulations.next', $session) }}" class="text-right">
        @csrf
        <button class="btn-primary">{{ $isLast ? 'Lihat Hasil Akhir →' : 'Kasus Berikutnya →' }}</button>
    </form>
</div>
@endsection
