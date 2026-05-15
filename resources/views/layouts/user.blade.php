@extends('layouts.app')

@section('body')
<div class="min-h-screen flex flex-col md:flex-row">
    {{-- Sidebar --}}
    <aside class="navy-gradient text-white md:w-64 md:min-h-screen md:sticky md:top-0 flex md:flex-col">
        <div class="px-5 py-5 flex items-center gap-3 border-b border-white/10">
            <div class="w-9 h-9 orange-gradient rounded-lg flex items-center justify-center font-bold shield-glow">S</div>
            <div>
                <div class="font-bold text-lg leading-none">SITANGKAS</div>
                <div class="text-[10px] text-navy-200 tracking-wider uppercase">Cyber Awareness</div>
            </div>
        </div>
        <nav class="px-3 py-4 flex-1 space-y-1">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'sidebar-link-active' : '' }}">Dashboard</a>
            <a href="{{ route('user.simulations.index') }}" class="sidebar-link {{ request()->routeIs('user.simulations.*') ? 'sidebar-link-active' : '' }}">Simulasi</a>
            <a href="{{ route('user.materials.index') }}" class="sidebar-link {{ request()->routeIs('user.materials.*') ? 'sidebar-link-active' : '' }}">Materi</a>
            <a href="{{ route('user.history.index') }}" class="sidebar-link {{ request()->routeIs('user.history.*') ? 'sidebar-link-active' : '' }}">Riwayat</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Panel Admin &rsaquo;</a>
            @endif
        </nav>
        <div class="px-3 py-4 border-t border-white/10">
            <div class="text-xs text-navy-200 px-3 mb-2">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 px-4 sm:px-8 py-6 max-w-full">
        @if(session('status'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
@endsection
