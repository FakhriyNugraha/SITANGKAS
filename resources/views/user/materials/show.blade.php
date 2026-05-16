@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('user.materials.index') }}" class="text-sm text-[#6b7896]">← Daftar materi</a>

    <div class="u-card p-6 mt-3 bounce-in">
        <div class="text-xs uppercase tracking-wider text-[#e67e22] mb-1">{{ $categoryMap[$material->category] ?? $material->category }}</div>
        <h1 class="text-2xl font-extrabold mb-1">{{ $material->title }}</h1>
        <p class="text-[#6b7896] mb-5">{{ $material->summary }}</p>
        <div class="text-[15px]">
            {!! \App\Support\MaterialFormatter::toHtml($material->content) !!}
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('user.levels.index') }}" class="btn-primary">Ke Jalur Belajar</a>
        <a href="{{ route('user.materials.index') }}" class="btn-secondary">Materi Lain</a>
    </div>
</div>
@endsection
