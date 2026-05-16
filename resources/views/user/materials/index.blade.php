@extends('layouts.user')

@php
    $icons = [
        'legitimate'=>'shield-check','phishing_link'=>'link','fake_giveaway'=>'gift',
        'otp_fraud'=>'key','password_security'=>'lock','marketplace_scam'=>'cart',
        'apk_malware'=>'phone','pinjol_ilegal'=>'cash','job_scam'=>'briefcase','qris_scam'=>'qr',
    ];
@endphp

@section('content')
<div class="mb-5">
    <h1 class="text-xl font-extrabold">Materi Pembelajaran</h1>
    <p class="text-sm text-[#6b7896]">Kurikulum keamanan digital yang tersusun rapi. Pelajari tiap topik dengan contoh nyata.</p>
</div>

<form class="mb-4">
    <select name="category" class="form-select max-w-xs text-sm" onchange="this.form.submit()">
        <option value="">Semua topik</option>
        @foreach($categoryMap as $k => $v)
            <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
        @endforeach
    </select>
</form>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($materials as $i => $m)
        <a href="{{ route('user.materials.show', $m) }}" class="u-card p-5 card-hover block">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-10 h-10 rounded-xl bg-[#fff3e6] text-[#e67e22] flex items-center justify-center">
                    <x-icon name="{{ $icons[$m->category] ?? 'book' }}" class="w-5 h-5" />
                </span>
                <span class="text-[11px] uppercase tracking-wider text-[#9aa6bd] font-semibold">Bab {{ $i + 1 }}</span>
            </div>
            <div class="font-bold mb-1">{{ $m->title }}</div>
            <p class="text-xs text-[#6b7896] leading-relaxed">{{ \Illuminate\Support\Str::limit($m->summary, 100) }}</p>
            <div class="mt-3 text-xs font-semibold text-[#e67e22] flex items-center gap-1">
                Baca materi <x-icon name="arrow-right" class="w-3.5 h-3.5" />
            </div>
        </a>
    @empty
        <div class="text-[#6b7896] text-sm">Belum ada materi.</div>
    @endforelse
</div>
<div class="mt-4">{{ $materials->links() }}</div>
@endsection
