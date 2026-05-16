@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Kelola Materi</h1>
        <p class="text-navy-500 text-sm">Materi edukasi yang dibaca pengguna sebelum berlatih.</p>
    </div>
    <a href="{{ route('admin.materials.create') }}" class="btn-primary">+ Tambah Materi</a>
</div>

<form class="card mb-4 flex gap-2">
    <select name="category" class="form-select max-w-xs">
        <option value="">Semua kategori</option>
        @foreach($categoryMap as $k => $v)
            <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Terapkan</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr><th>Judul</th><th>Kategori</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr></thead>
        <tbody>
            @forelse($materials as $m)
                <tr>
                    <td class="font-semibold">{{ $m->title }}</td>
                    <td class="text-navy-500">{{ $categoryMap[$m->category] ?? $m->category }}</td>
                    <td class="text-center">
                        @if($m->is_active)
                            <span class="badge badge-aman">Aktif</span>
                        @else
                            <span class="badge badge-berbahaya">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.materials.edit', $m) }}" class="btn-secondary text-xs py-1.5 px-3">Edit</a>
                            <form method="POST" action="{{ route('admin.materials.destroy', $m) }}" onsubmit="return confirm('Hapus materi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn-secondary text-xs py-1.5 px-3">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-navy-400 py-6">Belum ada materi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $materials->links() }}</div>
@endsection
