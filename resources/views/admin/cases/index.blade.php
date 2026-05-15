@extends('layouts.admin')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Kasus Simulasi</h1>
        <p class="text-navy-500 text-sm">Kelola bank skenario cyber awareness.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.imports.index') }}" class="btn-secondary">Import CSV</a>
        <a href="{{ route('admin.cases.create') }}" class="btn-primary">+ Tambah Kasus</a>
    </div>
</div>

<form class="card mb-4 flex flex-wrap items-end gap-3">
    <div class="flex-1 min-w-[200px]">
        <label class="text-xs uppercase text-navy-500">Cari</label>
        <input type="text" name="q" value="{{ $q }}" placeholder="Kode atau skenario..." class="form-input">
    </div>
    <div class="min-w-[200px]">
        <label class="text-xs uppercase text-navy-500">Kategori</label>
        <select name="category" class="form-select">
            <option value="">Semua</option>
            @foreach($categoryMap as $k => $v)
                <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn-primary">Filter</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Kode</th><th>Kanal</th><th>Kategori</th><th>Skenario</th><th>Risiko</th><th>Level</th><th>Aktif</th><th></th>
        </tr></thead>
        <tbody>
            @forelse($cases as $c)
                <tr>
                    <td class="font-mono text-xs">{{ $c->case_code }}</td>
                    <td>{{ $c->channel }}</td>
                    <td>{{ $c->category_name }}</td>
                    <td class="max-w-md"><div class="truncate">{{ $c->scenario_text }}</div></td>
                    <td><span class="badge badge-{{ $c->risk_label }}">{{ $c->risk_label }}</span></td>
                    <td><span class="badge badge-{{ $c->difficulty_level }}">{{ $c->difficulty_level }}</span></td>
                    <td>{!! $c->is_active ? '<span class="text-emerald-600">●</span>' : '<span class="text-rose-400">●</span>' !!}</td>
                    <td class="space-x-1 whitespace-nowrap">
                        <a href="{{ route('admin.cases.edit', $c) }}" class="text-orange-600 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('admin.cases.destroy', $c) }}" class="inline" onsubmit="return confirm('Toggle status kasus ini?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 font-semibold">Toggle</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-navy-400 py-6">Belum ada kasus.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $cases->links() }}</div>
@endsection
