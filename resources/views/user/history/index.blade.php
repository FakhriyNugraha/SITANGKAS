@extends('layouts.user')

@section('content')
<div class="mb-5">
    <h1 class="text-xl font-extrabold">Riwayat Latihan</h1>
    <p class="text-sm text-[#6b7896]">Catatan sesi belajar yang sudah kamu jalani.</p>
</div>

<div class="space-y-2">
    @forelse($sessions as $s)
        @php
            $sc = round($s->total_score);
            $stars = $sc >= 85 ? 3 : ($sc >= 65 ? 2 : ($sc >= 1 ? 1 : 0));
        @endphp
        <div class="u-card p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#fff3e6] text-[#c2611a] flex items-center justify-center font-bold" style="flex:none">
                {{ $sc }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm">{{ $categoryMap[$s->selected_category] ?? ucfirst($s->mode) }}</div>
                <div class="text-xs text-[#6b7896]">
                    {{ optional($s->started_at)->format('d M Y H:i') }} ·
                    {{ $s->completed_cases }}/{{ $s->total_cases }} soal
                </div>
            </div>
            <div class="text-right">
                @if($s->status === 'completed')
                    <div class="flex justify-end text-amber-500 mb-0.5">
                        @for($n = 0; $n < 3; $n++)
                            <x-icon name="star" class="w-3.5 h-3.5 {{ $n < $stars ? '' : 'opacity-25' }}" />
                        @endfor
                    </div>
                    <a class="text-xs text-[#e67e22] font-semibold" href="{{ route('user.history.show', $s) }}">Lihat detail</a>
                @else
                    <a class="text-xs text-[#e67e22] font-semibold" href="{{ route('user.simulations.show', $s) }}">Lanjutkan</a>
                @endif
            </div>
        </div>
    @empty
        <div class="u-card p-8 text-center text-[#6b7896]">
            Belum ada riwayat. <a href="{{ route('user.levels.index') }}" class="text-[#e67e22] font-semibold">Mulai belajar</a>.
        </div>
    @endforelse
</div>
<div class="mt-4">{{ $sessions->links() }}</div>
@endsection
