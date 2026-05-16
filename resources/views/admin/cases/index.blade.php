@extends('layouts.admin')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Kelola Kasus Simulasi</h1>
        <p class="text-navy-500 text-sm">Daftar skenario yang dilatih ke pengguna.</p>
    </div>
    <a href="{{ route('admin.cases.create') }}" class="btn-primary">+ Tambah Kasus</a>
</div>

<form class="card mb-4 flex flex-wrap items-end gap-3">
    <div class="flex-1 min-w-50">
        <label class="text-xs uppercase text-navy-500 block mb-1">Cari</label>
        <input type="text" name="q" value="{{ $q }}" placeholder="Kode atau isi skenario..." class="form-input">
    </div>
    <div class="min-w-50">
        <label class="text-xs uppercase text-navy-500 block mb-1">Kategori</label>
        <select name="category" class="form-select">
            <option value="">Semua kategori</option>
            @foreach($categoryMap as $k => $v)
                <option value="{{ $k }}" @selected($activeCategory === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn-primary">Terapkan</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Kode</th><th>Kategori</th><th>Skenario</th><th class="text-center">Risiko</th><th class="text-center">Status</th><th class="text-center">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($cases as $c)
                <tr>
                    <td class="font-mono text-xs">{{ $c->case_code }}</td>
                    <td>
                        <div class="font-medium">{{ $c->category_name }}</div>
                        <div class="text-xs text-navy-400">{{ $c->channel }} · {{ ucfirst($c->difficulty_level) }}</div>
                    </td>
                    <td class="max-w-sm"><div class="truncate text-navy-600">{{ $c->scenario_text }}</div></td>
                    <td class="text-center"><span class="badge badge-{{ $c->risk_label }}">{{ ucfirst($c->risk_label) }}</span></td>
                    <td class="text-center">
                        @if($c->is_active)
                            <span class="badge badge-aman">Aktif</span>
                        @else
                            <span class="badge badge-berbahaya">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.cases.edit', $c) }}" class="btn-secondary text-xs py-1.5 px-3">Edit</a>
                            <form method="POST" action="{{ route('admin.cases.destroy', $c) }}"
                                  onsubmit="return confirm('{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }} kasus ini?')">
                                @csrf @method('DELETE')
                                <button class="btn-secondary text-xs py-1.5 px-3">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-navy-400 py-6">Belum ada kasus. <a href="{{ route('admin.cases.create') }}" class="text-orange-600">Tambah sekarang</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $cases->links() }}</div>
@endsection
