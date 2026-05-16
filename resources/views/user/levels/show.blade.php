@extends('layouts.user')

@php $mat = $level['material']; @endphp

@section('content')
<a href="{{ route('user.levels.index') }}" class="text-sm text-[#6b7896] inline-flex items-center gap-1">
    <x-icon name="arrow-left" class="w-4 h-4" /> Jalur simulasi
</a>

<div class="u-card p-6 mt-3 mb-5 bounce-in" style="background:linear-gradient(135deg,{{ $level['color'] }},#1b2a4a);color:#fff;border:none">
    <div class="flex items-center gap-4">
        <span class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center">
            <x-icon name="{{ $level['icon'] }}" class="w-8 h-8" />
        </span>
        <div>
            <div class="text-xs uppercase tracking-wider opacity-80">Level {{ $level['index'] }}</div>
            <h1 class="text-2xl font-extrabold">{{ $level['title'] }}</h1>
            <div class="text-sm opacity-90">3 latihan kasus</div>
        </div>
    </div>
</div>

@if($mat)
<div class="u-card overflow-hidden mb-5">
    <div class="px-6 py-4 border-b border-[#eef2f7] flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-[#fff3e6] text-[#e67e22] flex items-center justify-center"><x-icon name="book" class="w-4 h-4" /></span>
        <div>
            <div class="text-[11px] font-bold text-[#e67e22] uppercase tracking-wider">Pelajari dulu</div>
            <div class="font-bold text-sm">{{ $mat->title }}</div>
        </div>
    </div>
    <div class="p-6 text-[15px]">
        {!! \App\Support\MaterialFormatter::toHtml($mat->content) !!}
    </div>
</div>
@endif

<div class="u-card p-6 text-center">
    <span class="w-12 h-12 rounded-2xl bg-[#fff3e6] text-[#e67e22] inline-flex items-center justify-center mb-2">
        <x-icon name="play" class="w-6 h-6" />
    </span>
    <h3 class="font-extrabold text-lg">Sudah paham materinya?</h3>
    <p class="text-sm text-[#6b7896] mb-4">Uji pemahamanmu lewat {{ $level['total_cases'] }} latihan kasus. Selesaikan semuanya untuk membuka level berikutnya.</p>
    <form method="POST" action="{{ route('user.levels.start', $level['category']) }}">
        @csrf
        <button class="btn-primary text-base px-7 py-3">Mulai Latihan <x-icon name="arrow-right" class="w-4 h-4" /></button>
    </form>
</div>
@endsection
