@extends('layouts.user')

@section('content')
<div class="mb-5">
    <h1 class="text-xl font-extrabold">Jalur Belajar</h1>
    <p class="text-sm text-[#6b7896]">Selesaikan level berurutan, dari yang paling dasar. Tiap level: pelajari materi dulu, lalu latihan dari mudah ke sulit.</p>
</div>

<div class="u-card p-4 mb-6">
    <div class="flex justify-between text-xs text-[#6b7896] mb-1">
        <span>Kemajuanmu</span>
        <span>{{ $progress['done'] }}/{{ $progress['total'] }} level</span>
    </div>
    <div class="u-progress-track"><div class="u-progress-bar" style="width: {{ $progress['total'] ? ($progress['done']/$progress['total']*100) : 0 }}%"></div></div>
</div>

<div class="max-w-md mx-auto">
    @foreach($levels as $i => $lvl)
        <div class="flex items-center gap-4 {{ $i % 2 === 0 ? '' : 'flex-row-reverse' }}">
            {{-- node --}}
            <div class="flex flex-col items-center" style="flex:none">
                @if($lvl['status'] === 'locked')
                    <div class="lvl-node is-locked" title="Terkunci">🔒</div>
                @else
                    <a href="{{ route('user.levels.show', $lvl['category']) }}"
                       class="lvl-node {{ $lvl['status']==='done' ? 'is-done' : 'is-open' }}"
                       title="{{ $lvl['title'] }}">
                        {{ $lvl['icon'] }}
                        @if($lvl['status'] === 'done')
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 text-white text-[10px] rounded-full flex items-center justify-center border-2 border-white">✓</span>
                        @endif
                    </a>
                @endif
            </div>

            {{-- label --}}
            <div class="flex-1 py-3">
                <div class="text-[11px] text-[#9aa6bd] font-semibold uppercase tracking-wider">Level {{ $lvl['index'] }}</div>
                <div class="font-bold {{ $lvl['status']==='locked' ? 'text-[#9aa6bd]' : 'text-[#1b2a4a]' }}">{{ $lvl['title'] }}</div>
                @if($lvl['status'] === 'locked')
                    <div class="text-xs text-[#9aa6bd]">Selesaikan level sebelumnya</div>
                @elseif($lvl['best_score'] !== null)
                    <div class="text-xs text-[#6b7896]">
                        Skor terbaik: <b>{{ $lvl['best_score'] }}</b>
                        <span class="ml-1">{!! str_repeat('⭐', $lvl['stars']) !!}{!! str_repeat('☆', 3 - $lvl['stars']) !!}</span>
                    </div>
                @else
                    <div class="text-xs text-[#6b7896]">{{ $lvl['total_cases'] }} latihan · siap dimulai</div>
                @endif
            </div>
        </div>

        @if(! $loop->last)
            <div class="lvl-connector {{ $lvl['status']==='done' ? 'filled' : '' }}"></div>
        @endif
    @endforeach
</div>
@endsection
