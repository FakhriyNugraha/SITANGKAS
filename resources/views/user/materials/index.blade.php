@extends('layouts.user')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Materi Edukasi</h1>
    <p class="text-navy-500 text-sm">Pelajari ancaman digital dan cara aman menghadapinya.</p>
</div>

<form class="mb-4 flex gap-2 flex-wrap">
    <select name="category" class="form-select max-w-xs" onchange="this.form.submit()">
        <option value="">Semua kategori</option>
        @foreach($categoryMap as $k => $v)
            <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
        @endforeach
    </select>
</form>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($materials as $m)
        <a href="{{ route('user.materials.show', $m) }}" class="card card-hover block">
            <div class="text-xs uppercase tracking-wider text-orange-600 mb-2">{{ $categoryMap[$m->category] ?? $m->category }}</div>
            <div class="font-semibold mb-2">{{ $m->title }}</div>
            <p class="text-sm text-navy-500">{{ \Illuminate\Support\Str::limit($m->summary, 110) }}</p>
            <div class="mt-3"><span class="badge badge-{{ $m->target_level === 'all' ? 'mencurigakan' : $m->target_level }}">{{ $m->target_level }}</span></div>
        </a>
    @empty
        <div class="text-navy-400 text-sm col-span-3">Belum ada materi.</div>
    @endforelse
</div>
<div class="mt-4">{{ $materials->links() }}</div>
@endsection
