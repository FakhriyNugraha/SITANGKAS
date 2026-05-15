@extends('layouts.user')

@section('content')
<div class="mb-4">
    <div class="flex items-center justify-between mb-2">
        <div class="text-xs uppercase tracking-wider text-navy-500">Kasus {{ $currentNumber }} dari {{ $totalCases }}</div>
        <div class="text-xs text-navy-500">Sesi #{{ $session->id }}</div>
    </div>
    <div class="progress-track">
        <div class="progress-bar" style="width: {{ ($currentNumber / max($totalCases,1)) * 100 }}%"></div>
    </div>
</div>

<div class="card pop-in">
    <div class="flex flex-wrap items-center gap-2 mb-4">
        <span class="badge" style="background:#1b2a4a; color:white;">{{ $case->channel }}</span>
        <span class="badge badge-{{ $case->risk_label }}">{{ $case->risk_label }}</span>
        <span class="badge badge-{{ $case->difficulty_level }}">{{ $case->difficulty_level }}</span>
        <span class="text-xs text-navy-500 ml-auto">{{ $case->category_name }}</span>
    </div>

    <div class="bg-navy-50 border border-navy-100 rounded-xl p-5 mb-5 leading-relaxed text-navy-700">
        <div class="text-xs uppercase tracking-wider text-navy-500 mb-2">Skenario</div>
        {!! nl2br(e($case->scenario_text)) !!}
    </div>

    <form method="POST" action="{{ route('user.simulations.answer', $session) }}" class="space-y-5" x-data="{ start: {{ $startTime }}, help: false }">
        @csrf
        <input type="hidden" name="cyber_case_id" value="{{ $case->id }}">
        <input type="hidden" name="answer_time_seconds" :value="Math.max(0, Math.floor(Date.now()/1000) - start)">
        <input type="hidden" name="help_opened" :value="help ? 1 : 0">

        <div>
            <div class="font-semibold mb-3">Apa tindakan paling aman?</div>
            <div class="space-y-2">
                @foreach($case->options as $opt)
                    <label class="flex items-start gap-3 p-3 border border-navy-100 rounded-lg cursor-pointer hover:border-orange-300 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                        <input type="radio" name="selected_option_id" value="{{ $opt->id }}" required class="mt-1">
                        <span class="text-sm">{{ $opt->option_text }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="font-semibold block mb-2">Tulis alasan kamu memilih tindakan tersebut</label>
            <textarea name="reason_text" rows="3" required minlength="3" class="form-textarea" placeholder="Contoh: karena linknya aneh dan mendesak minta klik dalam 24 jam..."></textarea>
            <p class="text-xs text-navy-500 mt-1">Sebutkan indikator bahaya yang kamu kenali. Sistem akan menganalisis alasan menggunakan Fuzzy Matching.</p>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <button type="button" @click="help = true" class="text-orange-600 text-sm underline" x-show="!help">Butuh bantuan?</button>
            <div x-show="help" x-cloak class="text-xs bg-orange-50 border border-orange-200 text-orange-800 rounded-lg p-3 max-w-md">
                <b>Tips:</b> Perhatikan domain link, permintaan OTP/PIN, harga tidak wajar, atau tekanan waktu. Jangan klik tautan dari nomor tidak dikenal.
            </div>
            <button type="submit" class="btn-primary">Kirim Jawaban &rarr;</button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none}</style>
@endsection
