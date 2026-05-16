@extends('layouts.user')

@php $mat = $level['material']; @endphp

@section('content')
<a href="{{ route('user.levels.index') }}" class="text-sm text-[#6b7896]">← Jalur belajar</a>

<div class="u-card p-6 mt-3 mb-5 bounce-in" style="background:linear-gradient(135deg,{{ $level['color'] }},#1b2a4a);color:#fff;border:none">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center text-3xl">{{ $level['icon'] }}</div>
        <div>
            <div class="text-xs uppercase tracking-wider opacity-80">Level {{ $level['index'] }}</div>
            <h1 class="text-2xl font-extrabold">{{ $level['title'] }}</h1>
            <div class="text-sm opacity-90">{{ $level['total_cases'] }} latihan · dari mudah ke sulit</div>
        </div>
    </div>
</div>

@if($mat)
<div class="u-card p-6 mb-5">
    <div class="flex items-center gap-2 mb-1">
        <span class="text-xs font-bold text-[#e67e22] uppercase tracking-wider">📖 Pelajari dulu</span>
    </div>
    <h2 class="text-lg font-extrabold mb-1">{{ $mat->title }}</h2>
    <p class="text-sm text-[#6b7896] mb-4">{{ $mat->summary }}</p>
    <div class="text-[15px]">
        {!! \App\Support\MaterialFormatter::toHtml($mat->content) !!}
    </div>
</div>
@endif

<div class="u-card p-6 text-center">
    <div class="text-2xl mb-1">🎯</div>
    <h3 class="font-extrabold text-lg">Sudah paham materinya?</h3>
    <p class="text-sm text-[#6b7896] mb-4">Sekarang uji pemahamanmu lewat {{ $level['total_cases'] }} latihan kasus nyata. Selesaikan semuanya untuk membuka level berikutnya.</p>
    <form method="POST" action="{{ route('user.levels.start', $level['category']) }}">
        @csrf
        <button class="btn-primary text-base px-7 py-3">Mulai Latihan →</button>
    </form>
</div>
@endsection
