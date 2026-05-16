@extends('layouts.user')

@php
    $icons = [
        'phishing_link'=>'link','fake_giveaway'=>'gift',
        'otp_fraud'=>'key','password_security'=>'lock','marketplace_scam'=>'cart',
        'apk_malware'=>'phone','pinjol_ilegal'=>'cash','job_scam'=>'briefcase','qris_scam'=>'qr',
    ];
@endphp

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('user.materials.index') }}" class="text-sm text-[#6b7896] inline-flex items-center gap-1">
        <x-icon name="arrow-left" class="w-4 h-4" /> Daftar materi
    </a>

    <div class="u-card overflow-hidden mt-3 bounce-in">
        <div class="p-6" style="background:linear-gradient(135deg,#1b2a4a,#243b63);color:#fff">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center">
                    <x-icon name="{{ $icons[$material->category] ?? 'book' }}" class="w-6 h-6" />
                </span>
                <span class="text-xs uppercase tracking-wider opacity-80">{{ $categoryMap[$material->category] ?? $material->category }}</span>
            </div>
            <h1 class="text-2xl font-extrabold leading-tight">{{ $material->title }}</h1>
            <p class="text-sm text-[#c7d2e6] mt-2">{{ $material->summary }}</p>
        </div>
        <div class="p-6 text-[15px]">
            {!! \App\Support\MaterialFormatter::toHtml($material->content) !!}
        </div>
    </div>

    <div class="u-card p-5 mt-4 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <div class="font-bold text-sm">Sudah paham materi ini?</div>
            <div class="text-xs text-[#6b7896]">Uji pemahamanmu lewat simulasi kasus nyata.</div>
        </div>
        <a href="{{ route('user.levels.index') }}" class="btn-primary">
            <x-icon name="play" class="w-4 h-4" /> Mulai Simulasi
        </a>
        <a href="{{ route('user.materials.index') }}" class="btn-secondary">Materi Lain</a>
    </div>
</div>
@endsection
