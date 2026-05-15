@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('user.materials.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali ke daftar materi</a>
    <div class="card mt-3 pop-in">
        <div class="text-xs uppercase tracking-wider text-orange-600 mb-2">{{ $categoryMap[$material->category] ?? $material->category }}</div>
        <h1 class="text-2xl font-bold mb-2">{{ $material->title }}</h1>
        <div class="flex items-center gap-2 mb-5">
            <span class="badge badge-{{ $material->target_level === 'all' ? 'mencurigakan' : $material->target_level }}">{{ $material->target_level }}</span>
        </div>

        <p class="text-navy-600 italic mb-5">{{ $material->summary }}</p>

        <div class="prose prose-sm max-w-none text-navy-700 leading-relaxed whitespace-pre-line">{{ $material->content }}</div>
    </div>
    <div class="mt-4 flex gap-2">
        <a href="{{ route('user.simulations.index') }}" class="btn-primary">Coba Simulasi Terkait</a>
        <a href="{{ route('user.materials.index') }}" class="btn-secondary">Materi Lainnya</a>
    </div>
</div>
@endsection
