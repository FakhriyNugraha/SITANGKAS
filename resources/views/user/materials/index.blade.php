@extends('layouts.user')

@section('content')
<div class="mb-5">
    <h1 class="text-xl font-extrabold">Materi Edukasi</h1>
    <p class="text-sm text-[#6b7896]">Bacaan singkat untuk mengenali dan menghindari penipuan digital.</p>
</div>

<form class="mb-4">
    <select name="category" class="form-select max-w-xs" onchange="this.form.submit()">
        <option value="">Semua kategori</option>
        @foreach($categoryMap as $k => $v)
            <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
        @endforeach
    </select>
</form>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($materials as $m)
        <a href="{{ route('user.materials.show', $m) }}" class="u-card p-4 card-hover block">
            <div class="text-xs uppercase tracking-wider text-[#e67e22] mb-1">{{ $categoryMap[$m->category] ?? $m->category }}</div>
            <div class="font-bold text-sm mb-1">{{ $m->title }}</div>
            <p class="text-xs text-[#6b7896]">{{ \Illuminate\Support\Str::limit($m->summary, 95) }}</p>
        </a>
    @empty
        <div class="text-[#6b7896] text-sm">Belum ada materi.</div>
    @endforelse
</div>
<div class="mt-4">{{ $materials->links() }}</div>
@endsection
