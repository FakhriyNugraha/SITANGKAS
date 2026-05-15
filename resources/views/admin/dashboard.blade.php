@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard Admin</h1>
    <p class="text-navy-500 text-sm">Ringkasan operasi sistem SITANGKAS.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total User', $totalUsers, 'orange'],
        ['Total Kasus', $totalCases, 'navy'],
        ['Total Materi', $totalMaterials, 'navy'],
        ['Total Sesi Selesai', $completedSessions, 'orange'],
    ] as [$lbl, $val, $color])
        <div class="card card-hover">
            <div class="text-xs uppercase tracking-wider text-navy-500 mb-1">{{ $lbl }}</div>
            <div class="text-3xl font-bold count-up {{ $color === 'orange' ? 'text-orange-600' : 'text-navy-700' }}">{{ $val }}</div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-4 mb-6">
    <div class="card">
        <div class="font-semibold mb-3">Distribusi Level Awareness</div>
        <canvas id="levelChart" height="200"></canvas>
    </div>
    <div class="card lg:col-span-2">
        <div class="font-semibold mb-3">Statistik Cepat</div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="p-3 bg-navy-50 rounded-lg">
                <div class="text-xs uppercase text-navy-500">Total Sesi (Semua Status)</div>
                <div class="text-xl font-bold">{{ $totalSessions }}</div>
            </div>
            <div class="p-3 bg-navy-50 rounded-lg">
                <div class="text-xs uppercase text-navy-500">Rata-rata Skor Selesai</div>
                <div class="text-xl font-bold">{{ $avgScore }}</div>
            </div>
            <div class="p-3 bg-navy-50 rounded-lg">
                <div class="text-xs uppercase text-navy-500">Indikator Fuzzy</div>
                <div class="text-xl font-bold">{{ $totalIndicators }}</div>
            </div>
            <div class="p-3 bg-navy-50 rounded-lg">
                <div class="text-xs uppercase text-navy-500">Profil Training KNN</div>
                <div class="text-xl font-bold">{{ $totalKnn }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="flex items-center justify-between mb-3">
        <div class="font-semibold">Sesi Terbaru</div>
        <a href="{{ route('admin.results.index') }}" class="text-orange-600 text-sm font-semibold">Lihat semua &rsaquo;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>User</th><th>Mode</th><th>Skor</th><th>Status</th><th>Level</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
                @forelse($latestSessions as $s)
                    <tr>
                        <td>{{ $s->user->name ?? '-' }}</td>
                        <td class="capitalize text-navy-500">{{ $s->mode }}</td>
                        <td class="font-semibold">{{ $s->total_score }}</td>
                        <td><span class="badge badge-{{ $s->status === 'completed' ? 'advanced' : 'mencurigakan' }}">{{ $s->status }}</span></td>
                        <td>
                            @if($s->awarenessScore)
                                <span class="badge badge-{{ $s->awarenessScore->awareness_level }}">{{ $s->awarenessScore->awareness_level }}</span>
                            @else &mdash; @endif
                        </td>
                        <td class="text-navy-500">{{ optional($s->started_at)->format('d M Y H:i') }}</td>
                        <td><a class="text-orange-600 font-semibold" href="{{ route('admin.results.show', $s) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-navy-400 py-6">Belum ada sesi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('levelChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Beginner', 'Intermediate', 'Advanced'],
        datasets: [{
            data: [{{ $levelDistribution['beginner'] ?? 0 }}, {{ $levelDistribution['intermediate'] ?? 0 }}, {{ $levelDistribution['advanced'] ?? 0 }}],
            backgroundColor: ['#f59e0b', '#3a527d', '#1b2a4a'],
            borderWidth: 0,
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
@endsection
