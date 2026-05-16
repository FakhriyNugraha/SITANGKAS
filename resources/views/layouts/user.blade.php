@extends('layouts.app')

@php
    $path = app(\App\Services\LearningPathService::class);
    $prog = auth()->check() ? $path->progress(auth()->user()) : ['done'=>0,'total'=>10];
@endphp

@section('body')
<div class="min-h-screen learn-bg">
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-[#e7ecf5]">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center gap-4">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 orange-gradient rounded-xl flex items-center justify-center text-white font-bold text-sm">S</div>
                <span class="font-extrabold tracking-tight">SITANGKAS</span>
            </a>

            <nav class="hidden sm:flex items-center gap-1 ml-2">
                <a href="{{ route('user.dashboard') }}" class="u-nav-link {{ request()->routeIs('user.dashboard') ? 'u-nav-active' : '' }}">Beranda</a>
                <a href="{{ route('user.levels.index') }}" class="u-nav-link {{ request()->routeIs('user.levels.*','user.simulations.*') ? 'u-nav-active' : '' }}">Belajar</a>
                <a href="{{ route('user.materials.index') }}" class="u-nav-link {{ request()->routeIs('user.materials.*') ? 'u-nav-active' : '' }}">Materi</a>
                <a href="{{ route('user.history.index') }}" class="u-nav-link {{ request()->routeIs('user.history.*') ? 'u-nav-active' : '' }}">Riwayat</a>
            </nav>

            <div class="ml-auto flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-[#c2611a] bg-[#fff3e6] px-3 py-1.5 rounded-full">
                    <span>⭐</span> {{ $prog['done'] }}/{{ $prog['total'] }} level
                </div>
                <div x-data="{o:false}" class="relative">
                    <button @click="o=!o" class="w-8 h-8 rounded-full bg-[#1b2a4a] text-white text-xs font-bold flex items-center justify-center">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </button>
                    <div x-show="o" @click.outside="o=false" x-cloak class="absolute right-0 mt-2 w-44 bg-white border border-[#e7ecf5] rounded-xl shadow-lg py-1 text-sm">
                        <div class="px-3 py-2 text-xs text-[#6b7896] border-b border-[#eef2f7]">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="w-full text-left px-3 py-2 hover:bg-[#f6f8fc]">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- nav mobile --}}
        <nav class="sm:hidden flex items-center justify-around border-t border-[#eef2f7] text-xs py-1.5">
            <a href="{{ route('user.dashboard') }}" class="px-3 py-1 {{ request()->routeIs('user.dashboard') ? 'text-[#c2611a] font-bold' : 'text-[#6b7896]' }}">Beranda</a>
            <a href="{{ route('user.levels.index') }}" class="px-3 py-1 {{ request()->routeIs('user.levels.*','user.simulations.*') ? 'text-[#c2611a] font-bold' : 'text-[#6b7896]' }}">Belajar</a>
            <a href="{{ route('user.materials.index') }}" class="px-3 py-1 {{ request()->routeIs('user.materials.*') ? 'text-[#c2611a] font-bold' : 'text-[#6b7896]' }}">Materi</a>
            <a href="{{ route('user.history.index') }}" class="px-3 py-1 {{ request()->routeIs('user.history.*') ? 'text-[#c2611a] font-bold' : 'text-[#6b7896]' }}">Riwayat</a>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @if(session('status'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm bounce-in">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none!important}</style>
@endsection
