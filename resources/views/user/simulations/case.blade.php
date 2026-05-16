@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- progress --}}
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('user.levels.index') }}" class="text-sm text-[#6b7896]">←</a>
        <div class="flex-1">
            <div class="flex justify-between text-xs text-[#6b7896] mb-1">
                <span>{{ $case->category_name }}</span>
                <span>Soal {{ $currentNumber }}/{{ $totalCases }}</span>
            </div>
            <div class="u-progress-track"><div class="u-progress-bar" style="width: {{ ($currentNumber / max($totalCases,1)) * 100 }}%"></div></div>
        </div>
    </div>

    <div class="text-center mb-4 bounce-in">
        <div class="text-xs text-[#9aa6bd] uppercase tracking-wider mb-1">Lewat {{ $case->channel }}</div>
        <h2 class="font-extrabold text-lg">Aman atau tidak pesan ini?</h2>
    </div>

    {{-- skenario mirip aslinya --}}
    <div class="mb-6">
        @include('user.simulations._scenario', ['case' => $case])
    </div>

    <form method="POST" action="{{ route('user.simulations.answer', $session) }}" class="space-y-5"
          x-data="{ start: {{ $startTime }} }">
        @csrf
        <input type="hidden" name="cyber_case_id" value="{{ $case->id }}">
        <input type="hidden" name="answer_time_seconds" :value="Math.max(0, Math.floor(Date.now()/1000) - start)">
        <input type="hidden" name="help_opened" value="0">

        <div>
            <div class="font-bold mb-2 text-center">Apa yang sebaiknya kamu lakukan?</div>
            <div class="space-y-2">
                @foreach($case->options as $opt)
                    <label class="opt-pill flex items-center gap-3">
                        <input type="radio" name="selected_option_id" value="{{ $opt->id }}" required class="accent-[#e67e22]">
                        <span class="text-sm">{{ $opt->option_text }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="font-bold block mb-2">Kenapa kamu memilih itu?</label>
            <textarea name="reason_text" rows="3" required minlength="3" class="form-textarea"
                placeholder="Tulis alasanmu, misalnya: linknya aneh dan memaksa cepat-cepat..."></textarea>
        </div>

        <button type="submit" class="btn-primary w-full text-base py-3">Periksa Jawaban →</button>
    </form>
</div>
@endsection
