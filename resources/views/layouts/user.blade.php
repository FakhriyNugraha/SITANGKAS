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
                <img src="/images/logo_sistem.png" alt="SITANGKAS" class="w-10 h-10 rounded-lg">
                <span class="font-extrabold tracking-tight">SITANGKAS</span>
            </a>

            <nav class="hidden sm:flex items-center gap-1 ml-2">
                <a href="{{ route('user.dashboard') }}" class="u-nav-link {{ request()->routeIs('user.dashboard') ? 'u-nav-active' : '' }}">
                    <x-icon name="home" class="w-4 h-4" /> Beranda
                </a>
                <a href="{{ route('user.levels.index') }}" class="u-nav-link {{ request()->routeIs('user.levels.*','user.simulations.*') ? 'u-nav-active' : '' }}">
                    <x-icon name="play" class="w-4 h-4" /> Simulasi
                </a>
                <a href="{{ route('user.materials.index') }}" class="u-nav-link {{ request()->routeIs('user.materials.*') ? 'u-nav-active' : '' }}">
                    <x-icon name="book" class="w-4 h-4" /> Materi
                </a>
                <a href="{{ route('user.history.index') }}" class="u-nav-link {{ request()->routeIs('user.history.*') ? 'u-nav-active' : '' }}">
                    <x-icon name="list" class="w-4 h-4" /> Riwayat
                </a>
            </nav>

            <div class="ml-auto flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-[#c2611a] bg-[#fff3e6] px-3 py-1.5 rounded-full">
                    <x-icon name="star" class="w-3.5 h-3.5" /> {{ $prog['done'] }}/{{ $prog['total'] }} level
                </div>
                <div x-data="{o:false}" class="relative">
                    <button @click="o=!o" type="button"
                            class="flex items-center gap-2 pl-1 pr-2.5 py-1 rounded-full border border-[#e7ecf5] bg-white hover:bg-[#f6f8fc] hover:border-[#d6deeb] transition cursor-pointer">
                        <span class="w-8 h-8 rounded-full bg-[#1b2a4a] text-white text-xs font-bold flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </span>
                        <span class="hidden sm:block text-sm font-semibold text-[#1b2a4a] max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        <x-icon name="chevron-down" class="w-4 h-4 text-[#6b7896] transition-transform" ::class="o && 'rotate-180'" />
                    </button>
                    <div x-show="o" x-transition.opacity @click.outside="o=false" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-[#e7ecf5] rounded-xl shadow-lg py-1 text-sm">
                        <div class="px-3 py-2 border-b border-[#eef2f7]">
                            <div class="text-sm font-semibold text-[#1b2a4a] truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-[#6b7896] truncate">{{ auth()->user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="w-full text-left px-3 py-2 hover:bg-[#f6f8fc] flex items-center gap-2 text-[#dc2626]">
                                <x-icon name="logout" class="w-4 h-4" /> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <nav class="sm:hidden grid grid-cols-4 border-t border-[#eef2f7] text-[11px]">
            <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('user.dashboard') ? 'text-[#c2611a]' : 'text-[#6b7896]' }}">
                <x-icon name="home" class="w-5 h-5" /> Beranda
            </a>
            <a href="{{ route('user.levels.index') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('user.levels.*','user.simulations.*') ? 'text-[#c2611a]' : 'text-[#6b7896]' }}">
                <x-icon name="play" class="w-5 h-5" /> Simulasi
            </a>
            <a href="{{ route('user.materials.index') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('user.materials.*') ? 'text-[#c2611a]' : 'text-[#6b7896]' }}">
                <x-icon name="book" class="w-5 h-5" /> Materi
            </a>
            <a href="{{ route('user.history.index') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('user.history.*') ? 'text-[#c2611a]' : 'text-[#6b7896]' }}">
                <x-icon name="list" class="w-5 h-5" /> Riwayat
            </a>
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
