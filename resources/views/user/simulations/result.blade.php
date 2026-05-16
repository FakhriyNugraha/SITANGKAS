@extends('layouts.user')

@php
    $path = app(\App\Services\LearningPathService::class);
    $cat = $session->selected_category;
    $levels = $path->forUser($session->user);
    $curIdx = collect($levels)->search(fn ($l) => $l['category'] === $cat);
    $curLevel = $curIdx !== false ? $levels[$curIdx] : null;
    $nextLevel = ($curIdx !== false && isset($levels[$curIdx + 1])) ? $levels[$curIdx + 1] : null;

    $score = round($session->total_score);
    $correct = $session->answers->where('is_correct', true)->count();
    $total = $session->answers->count();
    $stars = $score >= 85 ? 3 : ($score >= 65 ? 2 : ($score >= 1 ? 1 : 0));

    [$icon, $title, $desc] = match (true) {
        $score >= 85 => ['shield-check', 'Luar biasa!', 'Kamu sangat tanggap menghadapi modus ini.'],
        $score >= 65 => ['check-circle', 'Bagus!', 'Pemahamanmu sudah cukup baik, sedikit lagi sempurna.'],
        $score >= 40 => ['sparkles', 'Terus berlatih', 'Beberapa tanda bahaya masih terlewat. Ulangi untuk lebih mantap.'],
        default => ['book', 'Jangan menyerah', 'Pelajari lagi materinya, lalu coba sekali lagi.'],
    };
@endphp

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="u-card p-7 mb-5 text-center bounce-in" style="border:none;color:#fff;background:linear-gradient(135deg,#1b2a4a,#243b63)">
        <span class="w-16 h-16 rounded-full bg-white/12 inline-flex items-center justify-center mb-2">
            <x-icon name="{{ $icon }}" class="w-9 h-9" />
        </span>
        <h1 class="text-2xl font-extrabold">{{ $title }}</h1>
        <p class="text-sm text-[#c7d2e6] mt-1">{{ $desc }}</p>

        <div class="flex justify-center gap-1 mt-4 text-amber-400">
            @for($s = 0; $s < 3; $s++)
                <x-icon name="star" class="w-7 h-7 {{ $s < $stars ? '' : 'opacity-25' }}" />
            @endfor
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5 max-w-xs mx-auto">
            <div class="bg-white/10 rounded-xl py-3">
                <div class="text-2xl font-extrabold">{{ $score }}</div>
                <div class="text-[11px] text-[#c7d2e6] uppercase tracking-wider">Skor</div>
            </div>
            <div class="bg-white/10 rounded-xl py-3">
                <div class="text-2xl font-extrabold">{{ $correct }}/{{ $total }}</div>
                <div class="text-[11px] text-[#c7d2e6] uppercase tracking-wider">Jawaban Benar</div>
            </div>
        </div>
    </div>

    <div class="u-card p-5 mb-5">
        <div class="font-bold mb-3">Ringkasan Latihan</div>
        <div class="space-y-2">
            @foreach($session->answers as $i => $a)
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-[#f6f8fc]">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center {{ $a->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        <x-icon name="{{ $a->is_correct ? 'check-circle' : 'x-circle' }}" class="w-4 h-4" />
                    </span>
                    <div class="flex-1 min-w-0 text-sm truncate">Soal {{ $i + 1 }} · {{ \Illuminate\Support\Str::limit($a->cyberCase->scenario_text, 55) }}</div>
                    <span class="text-xs font-bold text-[#6b7896]">{{ round($a->case_score) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    @if($score < 75 && ! empty($recommendations))
        <div class="u-card p-5 mb-5" style="background:#fff7ef;border-color:#f6d9b8">
            <div class="text-sm font-bold text-[#c2611a] mb-2 flex items-center gap-1.5">
                <x-icon name="book" class="w-4 h-4" /> Disarankan pelajari lagi
            </div>
            <div class="space-y-2">
                @foreach(array_slice($recommendations, 0, 2) as $m)
                    <a href="{{ route('user.materials.show', $m) }}" class="block bg-white rounded-xl p-3 border border-[#f6d9b8]">
                        <div class="font-semibold text-sm">{{ $m->title }}</div>
                        <div class="text-xs text-[#6b7896]">{{ \Illuminate\Support\Str::limit($m->summary, 80) }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="space-y-2">
        @if($nextLevel && $nextLevel['status'] !== 'locked')
            <a href="{{ route('user.levels.show', $nextLevel['category']) }}" class="btn-primary w-full text-base py-3">
                Lanjut ke Level {{ $nextLevel['index'] }}: {{ $nextLevel['title'] }} <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        @elseif($nextLevel)
            <a href="{{ route('user.levels.index') }}" class="btn-primary w-full text-base py-3">Buka Jalur Simulasi</a>
        @else
            <div class="u-card p-5 text-center" style="background:#ecfdf5;border-color:#a7f3d0">
                <x-icon name="cap" class="w-7 h-7 text-emerald-600 mx-auto mb-1" />
                <div class="font-bold text-emerald-800">Kamu menyelesaikan semua level!</div>
            </div>
        @endif

        @if($curLevel)
            <a href="{{ route('user.levels.show', $curLevel['category']) }}" class="btn-secondary w-full">Ulangi level ini</a>
        @endif
        <a href="{{ route('user.levels.index') }}" class="block text-center text-sm text-[#6b7896] py-2">Kembali ke jalur simulasi</a>
    </div>
</div>
@endsection
