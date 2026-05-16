@extends('layouts.admin')

@section('content')
<a href="{{ route('admin.results.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali ke daftar user</a>

<div class="flex flex-wrap items-center justify-between gap-3 mt-1 mb-5">
    <div>
        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
        <p class="text-navy-500 text-sm">{{ $user->email }}</p>
    </div>
    @php $lvl = $assess['label'] ?? 'Belum dinilai';
         $b = ['Pemula'=>'badge-beginner','Menengah'=>'badge-intermediate','Mahir'=>'badge-advanced'][$lvl] ?? 'badge-mencurigakan'; @endphp
    <span class="badge {{ $b }} text-sm px-4 py-1.5">Tingkat: {{ $lvl }}</span>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Sesi Selesai</div>
        <div class="text-3xl font-bold">{{ $sessions->count() }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Rata-rata Skor</div>
        <div class="text-3xl font-bold text-orange-600">{{ $avg }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Level Diselesaikan</div>
        <div class="text-3xl font-bold">{{ collect($levels)->where('attempts','>',0)->count() }}/{{ count($levels) }}</div>
    </div>
    <div class="card">
        <div class="text-xs uppercase text-navy-500">Skor Keseluruhan</div>
        <div class="text-3xl font-bold">{{ $assess['overall_score'] ?? '-' }}</div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-4">
    <div class="card">
        <div class="font-semibold mb-3">Ringkasan per Topik</div>
        <table class="table-base">
            <thead><tr><th>Topik</th><th>Dikerjakan</th><th>Skor Terbaik</th></tr></thead>
            <tbody>
                @foreach($levels as $l)
                    <tr>
                        <td>{{ $l['title'] }}</td>
                        <td>{{ $l['attempts'] }}x</td>
                        <td>{{ $l['best'] !== null ? $l['best'] : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="font-semibold mb-3">Riwayat Sesi</div>
        <table class="table-base">
            <thead><tr><th>Tanggal</th><th>Topik</th><th>Skor</th><th>Benar</th></tr></thead>
            <tbody>
                @forelse($sessions as $s)
                    <tr>
                        <td class="text-navy-500 text-xs">{{ optional($s->finished_at)->format('d M Y H:i') }}</td>
                        <td>{{ $s->selected_category }}</td>
                        <td class="font-semibold">{{ round($s->total_score) }}</td>
                        <td>{{ $s->answers->where('is_correct',true)->count() }}/{{ $s->answers->count() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-navy-400 py-6">Belum ada sesi selesai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
