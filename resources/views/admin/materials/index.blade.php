@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Materi Edukasi</h1>
        <p class="text-navy-500 text-sm">Kelola materi yang direkomendasikan kepada user.</p>
    </div>
    <a href="{{ route('admin.materials.create') }}" class="btn-primary">+ Tambah Materi</a>
</div>

<form class="card mb-4 flex gap-3">
    <select name="category" class="form-select max-w-xs">
        <option value="">Semua kategori</option>
        @foreach($categoryMap as $k => $v)
            <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr><th>Judul</th><th>Kategori</th><th>Level</th><th>Aktif</th><th></th></tr></thead>
        <tbody>
            @forelse($materials as $m)
                <tr>
                    <td class="font-semibold">{{ $m->title }}</td>
                    <td class="text-navy-500 text-xs">{{ $categoryMap[$m->category] ?? $m->category }}</td>
                    <td><span class="badge badge-{{ $m->target_level === 'all' ? 'mencurigakan' : $m->target_level }}">{{ $m->target_level }}</span></td>
                    <td>{!! $m->is_active ? '<span class="text-emerald-600">●</span>' : '<span class="text-rose-400">●</span>' !!}</td>
                    <td class="space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.materials.edit', $m) }}" class="text-orange-600 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('admin.materials.destroy', $m) }}" class="inline" onsubmit="return confirm('Hapus materi?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-navy-400 py-6">Belum ada materi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $materials->links() }}</div>
@endsection
