@extends('layouts.user')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Riwayat Latihan</h1>
    <p class="text-navy-500 text-sm">Semua sesi simulasi yang pernah Anda jalankan.</p>
</div>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Tanggal</th><th>Mode</th><th>Kasus</th><th>Skor</th><th>Level</th><th>Status</th><th></th>
        </tr></thead>
        <tbody>
            @forelse($sessions as $s)
                <tr>
                    <td class="text-navy-500 text-xs">{{ optional($s->started_at)->format('d M Y H:i') }}</td>
                    <td class="capitalize">{{ $s->mode }}{{ $s->selected_category ? ' · '.($categoryMap[$s->selected_category] ?? $s->selected_category) : '' }}</td>
                    <td>{{ $s->completed_cases }}/{{ $s->total_cases }}</td>
                    <td class="font-bold">{{ $s->total_score }}</td>
                    <td>@if($s->awarenessScore)<span class="badge badge-{{ $s->awarenessScore->awareness_level }}">{{ $s->awarenessScore->awareness_level }}</span>@else &mdash; @endif</td>
                    <td><span class="badge badge-{{ $s->status === 'completed' ? 'advanced' : 'mencurigakan' }}">{{ $s->status }}</span></td>
                    <td>
                        @if($s->status === 'completed')
                            <a class="text-orange-600 font-semibold" href="{{ route('user.history.show', $s) }}">Detail</a>
                        @else
                            <a class="text-orange-600 font-semibold" href="{{ route('user.simulations.show', $s) }}">Lanjutkan</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-navy-400 py-6">Belum ada riwayat. <a href="{{ route('user.simulations.index') }}" class="text-orange-600">Mulai sekarang</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $sessions->links() }}</div>
@endsection
