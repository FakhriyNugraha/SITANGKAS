@extends('layouts.admin')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Indikator Fuzzy Matching</h1>
        <p class="text-navy-500 text-sm">Kamus indikator normal dan variasi katanya.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.imports.index') }}" class="btn-secondary">Import CSV</a>
        <a href="{{ route('admin.indicators.create') }}" class="btn-primary">+ Tambah</a>
    </div>
</div>

<form class="card mb-4 flex gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari indikator..." class="form-input">
    <button class="btn-primary">Cari</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Indikator Normal</th><th>Variasi Kata</th><th>Kategori</th><th>Bobot</th><th>Aktif</th><th></th>
        </tr></thead>
        <tbody>
            @forelse($indicators as $i)
                <tr>
                    <td class="font-semibold">{{ $i->normal_indicator }}</td>
                    <td class="text-navy-600">{{ $i->keyword_variation }}</td>
                    <td class="text-navy-500 text-xs">{{ $i->relevant_category ?? '-' }}</td>
                    <td>{{ $i->risk_weight }}</td>
                    <td>{!! $i->is_active ? '<span class="text-emerald-600">●</span>' : '<span class="text-rose-400">●</span>' !!}</td>
                    <td class="whitespace-nowrap space-x-2">
                        <a href="{{ route('admin.indicators.edit', $i) }}" class="text-orange-600 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('admin.indicators.destroy', $i) }}" class="inline" onsubmit="return confirm('Hapus indikator ini?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-navy-400 py-6">Belum ada indikator.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $indicators->links() }}</div>
@endsection
