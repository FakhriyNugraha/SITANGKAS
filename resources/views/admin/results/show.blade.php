@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <a href="{{ route('admin.results.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali ke daftar</a>
    <h1 class="text-2xl font-bold mt-1">Detail Sesi #{{ $session->id }}</h1>
    <div class="text-navy-500 text-sm">
        {{ $session->user->name }} &middot; {{ $session->user->email }} &middot; {{ optional($session->started_at)->format('d M Y H:i') }}
    </div>
</div>

<div class="grid lg:grid-cols-4 gap-4 mb-5">
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Skor Total</div>
        <div class="text-3xl font-bold count-up">{{ $session->total_score }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Level</div>
        @if($session->awarenessScore)
            <div class="text-2xl font-bold count-up capitalize">{{ $session->awarenessScore->awareness_level }}</div>
        @else <div class="text-navy-400">Belum dihitung</div> @endif
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Jawaban Benar</div>
        <div class="text-3xl font-bold count-up">{{ $session->answers->where('is_correct', true)->count() }}/{{ $session->answers->count() }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Rata Waktu Jawab</div>
        <div class="text-3xl font-bold count-up">{{ round($session->answers->avg('answer_time_seconds') ?? 0) }}s</div>
    </div>
</div>

@if($session->awarenessScore)
    <div class="card mb-5">
        <div class="font-semibold mb-3">Skor per Kategori (KNN Feature)</div>
        <div class="grid grid-cols-5 gap-3 text-sm">
            @foreach(['phishing_score'=>'Phishing','otp_score'=>'OTP','password_score'=>'Password','marketplace_score'=>'Marketplace','pinjol_score'=>'Pinjol'] as $f => $label)
                <div>
                    <div class="text-xs text-navy-500 mb-1">{{ $label }}</div>
                    <div class="font-bold">{{ $session->awarenessScore->{$f} }}</div>
                    <div class="progress-track mt-1"><div class="progress-bar" style="width: {{ min(100, $session->awarenessScore->{$f}) }}%"></div></div>
                </div>
            @endforeach
        </div>

        @if($session->awarenessScore->knn_neighbors)
            <div class="font-semibold mt-5 mb-2 text-sm">3 Tetangga Terdekat KNN</div>
            <table class="table-base text-xs">
                <thead><tr><th>Profile</th><th>Level</th><th>Distance</th></tr></thead>
                <tbody>
                    @foreach($session->awarenessScore->knn_neighbors as $n)
                        <tr>
                            <td>{{ $n['profile_code'] ?? '-' }}</td>
                            <td><span class="badge badge-{{ $n['level'] }}">{{ $n['level'] }}</span></td>
                            <td>{{ $n['distance'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endif

<div class="card">
    <div class="font-semibold mb-3">Jawaban per Kasus</div>
    @foreach($session->answers as $a)
        <div class="border border-navy-100 rounded-lg p-3 mb-3">
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold text-sm">{{ $a->cyberCase->case_code }} &middot; {{ $a->cyberCase->category_name }}</div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-{{ $a->is_correct ? 'aman' : 'berbahaya' }}">{{ $a->is_correct ? 'Benar' : 'Salah' }}</span>
                    <span class="text-xs text-navy-500">Skor: <b>{{ $a->case_score }}</b></span>
                </div>
            </div>
            <div class="text-xs text-navy-500">Skenario: {{ \Illuminate\Support\Str::limit($a->cyberCase->scenario_text, 200) }}</div>
            <div class="text-xs text-navy-700 mt-1">Tindakan dipilih: <em>{{ $a->selected_action_text }}</em></div>
            <div class="text-xs text-navy-700 mt-1">Alasan: <em>"{{ $a->reason_text }}"</em></div>
            <div class="grid grid-cols-2 gap-3 mt-2 text-xs">
                <div>
                    <div class="text-navy-500">Terdeteksi:</div>
                    @forelse($a->detected_indicators ?? [] as $d)
                        <span class="badge badge-aman">{{ $d['indicator'] ?? '-' }} ({{ $d['similarity'] ?? '-' }})</span>
                    @empty
                        <span class="text-navy-400 text-xs">-</span>
                    @endforelse
                </div>
                <div>
                    <div class="text-navy-500">Belum terdeteksi:</div>
                    @forelse($a->missed_indicators ?? [] as $m)
                        <span class="badge badge-berbahaya">{{ $m['indicator'] ?? '-' }}</span>
                    @empty
                        <span class="text-navy-400 text-xs">-</span>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
