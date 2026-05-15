@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-bold">Laporan</h1>
    <p class="text-navy-500 text-sm">Export hasil latihan ke PDF/Excel.</p>
</div>

<form method="GET" class="card mb-4 grid grid-cols-2 lg:grid-cols-5 gap-3 items-end">
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Level</label>
        <select name="level" class="form-select">
            <option value="">Semua</option>
            @foreach(['beginner','intermediate','advanced'] as $l)
                <option value="{{ $l }}" @selected(($filters['level'] ?? '') === $l)>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua</option>
            <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
            <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>Berjalan</option>
        </select>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Dari</label>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-input">
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Sampai</label>
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-input">
    </div>
    <div class="flex gap-2">
        <button class="btn-primary">Preview</button>
    </div>
</form>

<div class="flex gap-2 mb-4">
    <a href="{{ route('admin.reports.pdf', $filters) }}" target="_blank" class="btn-primary">Export PDF</a>
    <a href="{{ route('admin.reports.excel', $filters) }}" class="btn-secondary">Export Excel/CSV</a>
</div>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr><th>ID</th><th>User</th><th>Mode</th><th>Skor</th><th>Level</th><th>Selesai</th></tr></thead>
        <tbody>
            @forelse($sessions as $s)
                <tr>
                    <td>#{{ $s->id }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>{{ $s->mode }}</td>
                    <td>{{ $s->total_score }}</td>
                    <td>{{ optional($s->awarenessScore)->awareness_level ?? '-' }}</td>
                    <td>{{ optional($s->finished_at)->format('d M Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-navy-400 py-6">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
