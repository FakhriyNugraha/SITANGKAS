@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto pop-in">
    <div class="card navy-gradient text-white mb-5 scan-line-bg">
        <div class="text-xs uppercase tracking-wider text-orange-200 mb-2">Hasil Sesi #{{ $session->id }}</div>
        @if($awareness)
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <div class="text-sm text-navy-100 mb-1">Level Cyber Awareness Kamu</div>
                    <div class="text-5xl font-extrabold count-up capitalize">{{ $awareness->awareness_level }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-navy-100 mb-1">Skor Total</div>
                    <div class="text-4xl font-extrabold count-up text-orange-300">{{ $session->total_score }}</div>
                </div>
            </div>
            <p class="text-navy-100 text-sm mt-3">{{ $tutorSummary }}</p>
        @else
            <div class="text-2xl font-bold">Sesi belum memiliki klasifikasi.</div>
        @endif
    </div>

    @if($awareness)
        <div class="card mb-5">
            <div class="font-semibold mb-3">Skor per Kategori (Feature KNN)</div>
            <div class="grid md:grid-cols-5 gap-3 text-sm">
                @foreach(['phishing_score'=>'Phishing','otp_score'=>'OTP','password_score'=>'Password','marketplace_score'=>'Marketplace','pinjol_score'=>'Pinjol'] as $f => $label)
                    <div>
                        <div class="flex justify-between mb-1"><span class="text-navy-500 text-xs">{{ $label }}</span><b>{{ round($awareness->{$f}) }}</b></div>
                        <div class="progress-track"><div class="progress-bar" style="width: {{ min(100, $awareness->{$f}) }}%"></div></div>
                    </div>
                @endforeach
            </div>

            @if(! empty($awareness->knn_neighbors))
                <details class="mt-4 text-sm">
                    <summary class="cursor-pointer text-orange-600 font-semibold">Lihat 3 tetangga KNN terdekat</summary>
                    <table class="table-base mt-2 text-xs">
                        <thead><tr><th>Profile</th><th>Level</th><th>Distance</th></tr></thead>
                        <tbody>
                            @foreach($awareness->knn_neighbors as $n)
                                <tr>
                                    <td>{{ $n['profile_code'] ?? '-' }}</td>
                                    <td><span class="badge badge-{{ $n['level'] }}">{{ $n['level'] }}</span></td>
                                    <td>{{ $n['distance'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </details>
            @endif
        </div>
    @endif

    @if(! empty($weakCategories))
        <div class="card mb-5 border-orange-200">
            <div class="font-semibold mb-2">Kategori yang Masih Perlu Latihan</div>
            <div class="flex flex-wrap gap-2">
                @foreach($weakCategories as $w)
                    <span class="badge badge-mencurigakan">{{ $categoryMap[$w] ?? $w }}</span>
                @endforeach
            </div>
        </div>
    @endif

    @if(! empty($recommendations))
        <div class="card mb-5">
            <div class="font-semibold mb-3">Materi Rekomendasi untuk Kamu</div>
            <div class="grid md:grid-cols-2 gap-3">
                @foreach($recommendations as $m)
                    <a href="{{ route('user.materials.show', $m) }}" class="card card-hover block">
                        <div class="text-xs uppercase text-orange-600 mb-1">{{ $categoryMap[$m->category] ?? $m->category }}</div>
                        <div class="font-semibold mb-1">{{ $m->title }}</div>
                        <p class="text-xs text-navy-500">{{ \Illuminate\Support\Str::limit($m->summary, 100) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card">
        <div class="font-semibold mb-3">Ringkasan Jawaban</div>
        <div class="space-y-2">
            @foreach($answers as $a)
                <div class="flex flex-wrap items-center gap-3 p-3 border border-navy-100 rounded-lg">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $a->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {!! $a->is_correct ? '✓' : '✕' !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold">{{ $a->cyberCase->case_code }} &middot; {{ $a->cyberCase->category_name }}</div>
                        <div class="text-xs text-navy-500 truncate">{{ \Illuminate\Support\Str::limit($a->cyberCase->scenario_text, 90) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-navy-500">Skor</div>
                        <div class="font-bold">{{ $a->case_score }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mt-5">
        <a href="{{ route('user.simulations.index') }}" class="btn-primary">Latihan Lagi</a>
        <a href="{{ route('user.dashboard') }}" class="btn-secondary">Kembali ke Dashboard</a>
        <a href="{{ route('user.history.show', $session) }}" class="btn-secondary">Lihat Detail Riwayat</a>
    </div>
</div>
@endsection
